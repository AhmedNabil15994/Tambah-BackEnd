<?php

namespace Modules\Catalog\Repositories\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\Catalog\Constants\Product as ConstantsProduct;
use Modules\Catalog\Entities\AddOn;
use Modules\Catalog\Entities\AddOnOption;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\ProductImage;
use Modules\Catalog\Entities\SearchKeyword;
use Modules\Core\Traits\Attachment\Attachment;
use Modules\Core\Traits\CoreTrait;
use Modules\Core\Traits\Dashboard\DatatableExportTrait;
use Modules\Core\Traits\SyncRelationModel;
use Modules\Variation\Entities\OptionValue;
use Modules\Variation\Entities\ProductVariant;

class ProductRepository
{
    use DatatableExportTrait, SyncRelationModel, CoreTrait;

    protected $product;
    protected $addOn;
    protected $addOnOption;
    protected $prdImg;
    protected $optionValue;
    protected $variantPrd;
    protected $imgPath;

    public function __construct()
    {
        $this->product = new Product;
        $this->addOn = new AddOn;
        $this->addOnOption = new AddOnOption;
        $this->prdImg = new ProductImage;
        $this->optionValue = new OptionValue;
        $this->variantPrd = new ProductVariant;
        $this->imgPath = public_path('uploads/products');
        $exportCols = ['#' => 'id'];
        foreach (ConstantsProduct::IMPORT_PRODUCT_COLS as $key) {
            $exportCols[$key] = $key;
        }

        $this->setQueryActionsCols($exportCols);
        $this->exportFileName = 'products';
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $products = $this->product->orderBy($order, $sort)->get();
        return $products;
    }

    public function getAllActive($order = 'id', $sort = 'desc', $exceptedIds = [])
    {
        $products = $this->product->active();
        if (!empty($exceptedIds)) {
            $products = $products->whereNotIn('id', $exceptedIds);
        }
        return $products->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        $product = $this->product->withDeleted()->with([
            'tags', 'images', 'addOns' => function ($q) {
                $q->with('addOnOptions');
            },
        ])->find($id);
        return $product;
    }

    public function findBySku($sku)
    {
        $product = $this->product->withDeleted()->where('sku', $sku)->first();
        return $product;
    }

    public function findBySkuForImage($sku)
    {
        $sku = explode('|KEY', $sku)[0];
        $product = $this->product->withDeleted()->where('sku', $sku)->first();
        return $product;
    }

    public function findAddOnsById($id)
    {
        return $this->addOn->with('addOnOptions')->find($id);
    }

    public function findVariantProductById($id)
    {
        return $this->variantPrd->with('productValues')->find($id);
    }

    public function findProductImgById($id)
    {
        return $this->prdImg->find($id);
    }

    public function findAddOnsOptionById($id)
    {
        return $this->addOnOption->find($id);
    }

    public function setImages($request, $subImageFlag = '-')
    {
        $images = [];

        if (isset($request['images']) && count($request['images'])) {
            foreach ($request['images'] as $image) {

                $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $isSub = str_contains($imageName, $subImageFlag) ? true : false;
                $originalName = $isSub ? explode($subImageFlag, $imageName)[0] : $imageName;
                $originalName .= '|KEY';
                if (array_key_exists($originalName, $images)) {
                    if ($isSub) {
                        array_push($images[$originalName]['additional_photos'], $image);
                    } else {

                        $images[$originalName]['original_photo'] = $image;
                    }
                } else {
                    $images[$originalName] = [
                        'additional_photos' => $isSub ? [$image] : [],
                        'original_photo' => $isSub ? null : $image,
                    ];
                }
            }
        }

        return $images;
    }

    public function importPhotos(Request $request)
    {
        $images = $this->setImages($request);

        if (count($images)) {

            foreach ($images as $sku => $imageCollection) {

                $product = $this->findBySkuForImage($sku);

                if ($product) {

                    if (auth()->user()->can('edit_products_image') && isset($imageCollection['original_photo']) && $imageCollection['original_photo']) {

                        File::delete($product->image); ### Delete old image
                        $imgName = $this->uploadImage($this->imgPath, $imageCollection['original_photo']);
                        $product->image = 'uploads/products/' . $imgName;
                        $product->save();
                    }

                    if (auth()->user()->can('edit_products_gallery') && isset($imageCollection['additional_photos']) && $imageCollection['additional_photos']) {

                        $sync = $this->syncRelation($product, 'images', $imageCollection['additional_photos']);
                        $imgPath = public_path('uploads/products');

                        // Update Old Images
                        if (isset($sync['updated']) && !empty($sync['updated'])) {
                            foreach ($sync['updated'] as $k => $id) {
                                $oldImgObj = $product->images()->find($id);
                                File::delete('uploads/products/' . $oldImgObj->image); ### Delete old image

                                $img = $request->images[$id];
                                $imgName = $img->hashName();
                                $img->move($imgPath, $imgName);

                                $oldImgObj->update([
                                    'image' => $imgName,
                                ]);
                            }
                        }

                        // Add New Images
                        foreach ($imageCollection['additional_photos'] as $k => $img) {
                            if (!in_array($k, $sync['updated'])) {
                                $imgName = $img->hashName();
                                $img->move($imgPath, $imgName);

                                $product->images()->create([
                                    'image' => $imgName,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $data = [
                //'image' => $request->image ? path_without_domain($request->image) : url(config('setting.logo')),
                'status' => $request->status && $request->status == 'on' ? 1 : 0,
                'allow_wrapping' => $request->allow_wrapping && $request->allow_wrapping == 'on' ? 1 : 0,
                'is_new' => $request->is_new && $request->is_new == 'on' ? 1 : 0,
                'all_countries' => $request->all_countries && $request->all_countries == 'on' ? 1 : 0,
                'featured' => $request->featured && $request->featured == 'on' ? 1 : 0,
                'show_product_website' => $request->show_product_website && $request->show_product_website == 'on' ? 1 : 0,
                'show_product_pos' => $request->show_product_pos && $request->show_product_pos == 'on' ? 1 : 0,
                'price' => $request->price,
                'imported_excel' => $request->imported_excel ? $request->imported_excel : 0,
                'sku' => $request->sku,
                "shipment" => $request->shipment,
                'sort' => $request->sort ?? 0,
                'title' => $request->title,
                'description' => $request->description,
                'seo_description' => $request->seo_description,
                'seo_keywords' => $request->seo_keywords,
                'delivery_time' => $request->delivery_time,
                'for_boys_girls' => $request->for_boys_girls ?? 'both',
                'brand_id' => $request->brand_id,
                'vendor_id' => $request->vendor_id,
                'store_location' => $request->store_location,
                'international_code' => $request->international_code,
                'related_products' => $request->related_products,
            ];

            if ($request->manage_qty == 'limited') {
                $data['qty'] = $request->qty;
            } else {
                $data['qty'] = null;
            }

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage($this->imgPath, $request->image);
                $data['image'] = 'uploads/products/' . $imgName;
            } else {
                $data['image'] = url(config('setting.logo'));
            }

            $product = $this->product->create($data);

            if ($request->countries) {
                $product->countries()->attach($request->countries);
            }

            if ($request->category_id) {

                $product->categories()->sync((is_array($request->category_id) ? $request->category_id : int_to_array($request->category_id)));

                if ($request->offer_status && $request->offer_status != "on") {
                    $this->productVariants($product, $request);
                }
            }

            $this->productOffer($product, $request);

            // Add Product Images
            if (isset($request->images) && !empty($request->images)) {
                $imgPath = public_path('uploads/products');

                foreach ($request->images as $k => $img) {
                    $imgName = $img->hashName();
                    $img->move($imgPath, $imgName);

                    $product->images()->create([
                        'image' => $imgName,
                    ]);
                }
            }

            // Add Product Tags
            if (isset($request->tags) && !empty($request->tags)) {
                $tagsCollection = collect($request->tags);
                $filteredTags = $tagsCollection->filter(function ($value, $key) {
                    return $value != null && $value != '';
                });
                $tags = $filteredTags->all();

                $product->tags()->sync($tags);
            }

            // Add Product search keywords
            if (isset($request->search_keywords) && !empty($request->search_keywords)) {
                $searchKeywordsCollection = collect($request->search_keywords);
                $filteredSearchKeywords = $searchKeywordsCollection->filter(function ($value, $key) {
                    return $value != null && $value != '';
                });
                $searchKeywords = $filteredSearchKeywords->all();

                $ids = [];
                foreach ($searchKeywords as $searchKeyword) {
                    $keyword = SearchKeyword::firstOrCreate(
                        ['id' => $searchKeyword],
                        ['title' => $searchKeyword, 'status' => 1]
                    );
                    if ($keyword) {
                        $ids[] = $keyword->id;
                    }
                }
                $product->searchKeywords()->sync($ids);
            }

            $this->saveProductAges($product, $request->ages);

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function saveProductAges($model, $ages)
    {
        if (!empty($ages)) {
            $agesCollection = collect($ages);
            $filteredAges = $agesCollection->filter(function ($value, $key) {
                return $value != null && $value != '';
            });
            $ages = $filteredAges->all();
            $model->ages()->sync($ages);
        } else {
            $model->ages()->detach();
        }
    }

    public function import(Request $request)
    {
        if ($request->has('file_sku') && count($request->file_sku)) {

            DB::beginTransaction();

            try {
                foreach ($request->file_sku as $index => $sku) {
                    $productRequest = new Request();

                    if (isset($request->file_category[$index]) && $request->file_category[$index]) {

                        $categoriesNames = explode(',', $request->file_category[$index]);
                        $categories = Category::whereIn('title->ar', $categoriesNames)->orWhereIn('title->en', $categoriesNames)->pluck('id')->toArray();
                    } else {
                        $categories = $request->category_id;
                    }

                    $productRequest->replace([
                        'category_id' => $categories && count($categories) ? $categories : [1],
                        'imported_excel' => 1,
                        'qty' => isset($request->file_qty[$index]) && $request->file_qty[$index] ? $request->file_qty[$index] : null,
                        'manage_qty' => isset($request->file_qty[$index]) && $request->file_qty[$index] ? 'limited' : null,
                        'status' => isset($request->file_status[$index]) && $request->file_status[$index] ? $request->file_status[$index] : null,
                        'title' => [
                            'en' => isset($request->file_title_en[$index]) && $request->file_title_en[$index] ? $request->file_title_en[$index] : '',
                            'ar' => isset($request->file_title_ar[$index]) && $request->file_title_ar[$index] ? $request->file_title_ar[$index] : '',
                        ],
                        'description' => [
                            'en' => isset($request->file_description_en[$index]) && $request->file_description_en[$index] ? $request->file_description_en[$index] : '',
                            'ar' => isset($request->file_description_ar[$index]) && $request->file_description_ar[$index] ? $request->file_description_ar[$index] : '',
                        ],
                        'store_location' => [
                            'en' => isset($request->file_store_location_en[$index]) && $request->file_store_location_en[$index] ? $request->file_store_location_en[$index] : '',
                            'ar' => isset($request->file_store_location_ar[$index]) && $request->file_store_location_ar[$index] ? $request->file_store_location_ar[$index] : '',
                        ],
                        'price' => isset($request->file_price[$index]) && $request->file_price[$index] ? $request->file_price[$index] : null,
                        'sku' => $sku ? preg_replace('/\s+/', '', $sku) : null,
                        'offer_type' => 'amount',
                        'offer_status' => isset($request->file_offer_price[$index]) && isset($request->file_offer_start_at[$index]) && isset($request->file_offer_end_at[$index])
                        && $request->file_offer_price[$index] && $request->file_offer_start_at[$index] && $request->file_offer_end_at[$index] ?
                        'on' : null,
                        'offer_price' => isset($request->file_offer_price[$index]) && $request->file_offer_price[$index] ? $request->file_offer_price[$index] : null,
                        'start_at' => isset($request->file_offer_start_at[$index]) && $request->file_offer_start_at[$index] ? $request->file_offer_start_at[$index] : null,
                        'end_at' => isset($request->file_offer_end_at[$index]) && $request->file_offer_end_at[$index] ? $request->file_offer_end_at[$index] : null,
                    ]);

                    $model = $productRequest->sku ? $this->findBySku($productRequest->sku) : null;

                    if ($model) {

                        $this->update($productRequest, $model->id);
                    } else {

                        $this->create($productRequest);
                    }
                }

                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        // dd($request->all());
        $product = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelte($product) : null;

        if (isset($request->images) && !empty($request->images)) {
            $sync = $this->syncRelation($product, 'images', $request->images);
        }

        try {
            $data = [
                'featured' => $request->featured == 'on' ? 1 : 0,
                'status' => $request->status == 'on' ? 1 : 0,
                'allow_wrapping' => $request->allow_wrapping == 'on' ? 1 : 0,
                'is_new' => $request->is_new == 'on' ? 1 : 0,
                'show_product_website' => $request->show_product_website == 'on' ? 1 : 0,
                'show_product_pos' => $request->show_product_pos == 'on' ? 1 : 0,
                'all_countries' => $request->all_countries == 'on' ? 1 : 0,
                'sku' => $request->sku,
                "shipment" => $request->shipment,
                'sort' => $request->sort ?? 0,
                'usage' => $request->usage,
                'ingredients' => $request->ingredients,
                'seo_description' => $request->seo_description,
                'seo_keywords' => $request->seo_keywords,
                'delivery_time' => $request->delivery_time,
                'for_boys_girls' => $request->for_boys_girls ?? 'both',
                'brand_id' => $request->brand_id,
                'vendor_id' => $request->vendor_id,
                'store_location' => $request->store_location,
                'international_code' => $request->international_code,
                'related_products' => $request->related_products,
            ];

            if (auth()->user()->can('edit_products_price')) {
                $data['price'] = $request->price;
            }

            if (auth()->user()->can('edit_products_qty')) {
                if ($request->manage_qty == 'limited') {
                    $data['qty'] = $request->qty;
                } else {
                    $data['qty'] = null;
                }
            }

            $product->countries()->sync($request->countries);

            if (auth()->user()->can('edit_products_image')) {
                if ($request->image) {
                    File::delete($product->image); ### Delete old image
                    $imgName = $this->uploadImage($this->imgPath, $request->image);
                    $data['image'] = 'uploads/products/' . $imgName;
                } else {
                    $data['image'] = $product->image;
                }
            }

            if (auth()->user()->can('edit_products_title')) {
                $data["title"] = $request->title;
            }

            if (auth()->user()->can('edit_products_description')) {
                $data["description"] = $request->description;
                $data["short_description"] = $request->short_description;
            }

            $product->update($data);

            if (auth()->user()->can('edit_products_category')) {
                $product->categories()->sync((is_array($request->category_id) ? $request->category_id : int_to_array($request->category_id)));
            }

            if ($request->offer_status == "on") {
                $product->variants()->delete();
            } else {
                $this->productVariants($product, $request);
            }

            if (auth()->user()->can('edit_products_price')) {
                $this->productOffer($product, $request);
            }

            if (auth()->user()->can('edit_products_gallery')) {
                // Create Or Update Product Images
                if (isset($request->images) && !empty($request->images)) {
                    $imgPath = public_path('uploads/products');

                    // Update Old Images
                    if (isset($sync['updated']) && !empty($sync['updated'])) {
                        foreach ($sync['updated'] as $k => $id) {
                            $oldImgObj = $product->images()->find($id);
                            File::delete('uploads/products/' . $oldImgObj->image); ### Delete old image

                            $img = $request->images[$id];
                            $imgName = $img->hashName();
                            $img->move($imgPath, $imgName);

                            $oldImgObj->update([
                                'image' => $imgName,
                            ]);
                        }
                    }

                    // Add New Images
                    foreach ($request->images as $k => $img) {
                        if (!in_array($k, $sync['updated'])) {
                            $imgName = $img->hashName();
                            $img->move($imgPath, $imgName);

                            $product->images()->create([
                                'image' => $imgName,
                            ]);
                        }
                    }
                }
            }

            // Update Product
            if (isset($request->tags) && !empty($request->tags)) {
                $tagsCollection = collect($request->tags);
                $filteredTags = $tagsCollection->filter(function ($value, $key) {
                    return $value != null && $value != '';
                });
                $tags = $filteredTags->all();

                $product->tags()->sync($tags);
            }

            // Add Product search keywords
            if (isset($request->search_keywords) && !empty($request->search_keywords)) {
                $searchKeywordsCollection = collect($request->search_keywords);
                $filteredSearchKeywords = $searchKeywordsCollection->filter(function ($value, $key) {
                    return $value != null && $value != '';
                });
                $searchKeywords = $filteredSearchKeywords->all();

                $ids = [];
                foreach ($searchKeywords as $searchKeyword) {
                    $keyword = SearchKeyword::firstOrCreate(
                        ['id' => $searchKeyword],
                        ['title' => $searchKeyword, 'status' => 1]
                    );
                    if ($keyword) {
                        $ids[] = $keyword->id;
                    }
                }
                $product->searchKeywords()->sync($ids);
            }

            $this->saveProductAges($product, $request->ages);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updatePhoto($request)
    {
        DB::beginTransaction();

        $product = $this->findById($request->photo_id);

        try {

            if (auth()->user()->can('edit_products_image') && $request->image) {

                $product->update([
                    'image' => $request->image ? Attachment::updateAttachment($request['image'], $product->image, 'products') : $product->image,
                ]);

                DB::commit();
                $product->fresh();
                return asset($product->image);
            }

            return false;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function switcher($id, $action)
    {
        DB::beginTransaction();

        try {
            $model = $this->findById($id);

            if ($model->$action == 1) {
                $model->$action = 0;
            } elseif ($model->$action == 0) {
                $model->$action = 1;
            } else {
                return false;
            }

            $model->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function translateAddOnTable($model, $request)
    {
        foreach ($request['option_name'] as $locale => $value) {
            $model->translateOrNew($locale)->name = $value;
        }

        $model->save();
    }

    public function delete($id)
    {

        try {
            $model = $this->findById($id);

            if ($model) {

                DB::beginTransaction();

                if ($model->trashed()):

                    Attachment::deleteAttachment($model->image);
                    $model->forceDelete();
                else:
                    $model->delete();
                endif;

                DB::commit();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {
            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteAddOns($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findAddOnsById($id);

            if ($model) {
                $model->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteAddOnsOption($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findAddOnsOptionById($id);

            if ($model) {
                $model->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteProductImg($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findProductImgById($id);

            if ($model) {
                File::delete('uploads/products/' . $model->image); ### Delete old image
                $model->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createAddOns($request, $id)
    {
        DB::beginTransaction();

        try {
            $product = $this->findById($id);
            if ($product) {
                $dataInput = ["name" => $request->option_name];

                // dd($dataInput, $request->all());

                if (isset($request->add_ons_type) && !empty($request->add_ons_type)) {
                    $dataInput['type'] = $request->add_ons_type;
                    if (isset($request->options_count_select) && $request->options_count_select == 'on') {
                        $dataInput['options_count'] = $request->options_count ?? null;
                    }
                }

                if (isset($request->add_on_id) && !empty($request->add_on_id)) {
                    $addOn = AddOn::find($request->add_on_id);
                    $addOn->update($dataInput);
                } else {
                    $addOn = $product->addOns()->create($dataInput);
                }

                // Add AddOns options

                if (count($request->price) > 0 && count($request->rowId) > 0) {
                    foreach ($request->rowId as $k => $rowID) {
                        if (isset($request->add_on_id) && !empty($request->add_on_id)) {
                            ############################## Start Update Options ##############################

                            $OldAddOnOption = AddOnOption::find($rowID);

                            if ($OldAddOnOption) {
                                // Update Old
                                $OldAddOnOption->update([
                                    'price' => $request->price[$k],
                                    'default' => isset($request->default) && $request->default == $rowID ? true : null,
                                    "option" => $request->option[$rowID],
                                ]);

                                // $optionsArr = array_values($request->option);
                                // $this->translateAddOnOptionTable($OldAddOnOption, $optionsArr[$k]);
                            } else {
                                // Add New One
                                $newAddOnOption = $addOn->addOnOptions()->create([
                                    'price' => $request->price[$k],
                                    'default' => isset($request->default) && $request->default == $rowID ? true : null,
                                    "option" => $request->option[$rowID],
                                ]);

                                // $optionsArr = array_values($request->option);
                                // $this->translateAddOnOptionTable($newAddOnOption, $optionsArr[$k]);
                            }
                            ############################## Start Update Options ##############################
                        } else {
                            ############################## Start Add New Options ##############################
                            $addOnOption = $addOn->addOnOptions()->create([
                                'price' => $request->price[$k],
                                'default' => isset($request->default) && $request->default == $rowID ? true : null,
                                "option" => $request->option[$rowID],
                            ]);

                            $optionsArr = array_values($request->option);
                            // $this->translateAddOnOptionTable($addOnOption, $optionsArr[$k]);
                            ############################## End Add New Options ##############################
                        }
                    }
                }

                DB::commit();
                return $this->findAddOnsById($addOn->id);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getModelTranslatable()
    {
        if (property_exists($this->product, 'translatable')) {
            return $this->product->translatable;
        } else {
            return [];
        }
    }

    public function QueryTable($request)
    {
        $query = $this->product->with(['categories', 'vendor']);

        $query->when($request->input('search.value'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('sku', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('international_code', 'like', '%' . $request->input('search.value') . '%');

                $query->orWhere('title->' . locale(), 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('short_description->' . locale(), 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('description->' . locale(), 'like', '%' . $request->input('search.value') . '%');

                $query->orWhereHas('categories', function ($query) use ($request) {
                    $query->where('title->' . locale(), 'like', '%' . $request->input('search.value') . '%');
                });

                $query->orWhereHas('vendor', function ($query) use ($request) {
                    $query->where('title->' . locale(), 'like', '%' . $request->input('search.value') . '%');
                });
            });
        });

        return $this->filterDataTable($query, $request);
    }

    public function reviewProductsQueryTable($request)
    {
        $query = $this->product->with(['translations']);

        $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
            });
        });

        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        // Search Categories by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only') {
            $query->onlyDeleted();
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with') {
            $query->withDeleted();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '1') {
            $query->active();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '0') {
            $query->unactive();
        }

        if (isset($request['req']['categories']) && $request['req']['categories'] != '') {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('product_categories.category_id', $request['req']['categories']);
            });
        }

        if (isset($request['req']['vendor_id']) && $request['req']['vendor_id'] != '') {
            $query->where('vendor_id', $request['req']['vendor_id']);
        }

        return $query;
    }

    public function productVariants($model, $request)
    {
        $oldValues = isset($request['variants']['_old']) ? $request['variants']['_old'] : [];

        $sync = $this->syncRelation($model, 'variants', $oldValues);

        if ($sync['deleted']) {
            $model->variants()->whereIn('id', $sync['deleted'])->delete();
        }

        if ($sync['updated']) {
            foreach ($sync['updated'] as $id) {
                foreach ($request['upateds_option_values_id'] as $key => $varianteId) {
                    $variation = $model->variants()->find($id);

                    $data = [
                        'sku' => $request['_variation_sku'][$id],
                        'price' => $request['_variation_price'][$id],
                        'status' => isset($request['_variation_status'][$id]) && $request['_variation_status'][$id] == 'on' ? 1 : 0,
                        'qty' => $request['_variation_qty'][$id],
                        "shipment" => isset($request["_vshipment"][$id]) ? $request["_vshipment"][$id] : null,
                        //                        'image' => $request['_v_images'][$id] ? path_without_domain($request['_v_images'][$id]) : $model->image
                    ];

                    if (!is_null($request['_v_images']) && isset($request['_v_images'][$id])) {
                        $imgName = $this->uploadImage($this->imgPath, $request['_v_images'][$id]);
                        $data['image'] = 'uploads/products/' . $imgName;
                    }

                    $variation->update($data);

                    if (isset($request["_v_offers"][$id])) {
                        $this->variationOffer($variation, $request["_v_offers"][$id]);
                    }
                }
            }
        }

        $selectedOptions = [];

        if ($request['option_values_id']) {
            foreach ($request['option_values_id'] as $key => $value) {

                // dd($request->all(), $key);

                $data = [
                    'sku' => $request['variation_sku'][$key],
                    'price' => $request['variation_price'][$key],
                    'status' => isset($request['variation_status'][$key]) && $request['variation_status'][$key] == 'on' ? 1 : 0,
                    'qty' => $request['variation_qty'][$key],
                    "shipment" => isset($request["vshipment"][$key]) ? $request["vshipment"][$key] : null,
                    //                    'image' => $request['v_images'][$key] ? path_without_domain($request['v_images'][$key]) : $model->image
                ];

                if (!is_null($request['v_images']) && isset($request['v_images'][$key])) {
                    $imgName = $this->uploadImage($this->imgPath, $request['v_images'][$key]);
                    $data['image'] = 'uploads/products/' . $imgName;
                } else {
                    $data['image'] = $model->image;
                }

                $variant = $model->variants()->create($data);

                if (isset($request["v_offers"][$key])) {
                    $this->variationOffer($variant, $request["v_offers"][$key]);
                }

                foreach ($value as $key2 => $value2) {
                    $optVal = $this->optionValue->find($value2);
                    if ($optVal) {
                        if (!in_array($optVal->option_id, $selectedOptions)) {
                            array_push($selectedOptions, $optVal->option_id);
                        }
                    }

                    $option = $model->options()->updateOrCreate([
                        'option_id' => $optVal->option_id,
                        'product_id' => $model['id'],
                    ]);

                    $variant->productValues()->create([
                        'product_option_id' => $option['id'],
                        'option_value_id' => $value2,
                        'product_id' => $model['id'],
                    ]);
                }
            }
        }

        /*if (count($selectedOptions) > 0) {
        foreach ($selectedOptions as $option_id) {
        $option = $model->options()->updateOrCreate([
        'option_id' => $option_id,
        'product_id' => $model['id'],
        ]);
        }
        }*/

        /*if (count($selectedOptions) > 0) {
    $model->productOptions()->sync($selectedOptions);
    }*/
    }

    public function productOffer($model, $request)
    {
        if (isset($request->offer_status) && $request->offer_status == 'on') {
            $data = [
                'status' => ($request['offer_status'] == 'on') ? true : false,
                // 'offer_price' => $request['offer_price'] ? $request['offer_price'] : $model->offer->offer_price,
                'start_at' => $request['start_at'] ? $request['start_at'] : $model->offer->start_at,
                'end_at' => $request['end_at'] ? $request['end_at'] : $model->offer->end_at,
            ];

            if ($request['offer_type'] == 'amount' && !is_null($request['offer_price'])) {
                $data['offer_price'] = $request['offer_price'];
                $data['percentage'] = null;
            } elseif ($request['offer_type'] == 'percentage' && !is_null($request['offer_percentage'])) {
                $data['offer_price'] = null;
                $data['percentage'] = $request['offer_percentage'];
            } else {
                $data['offer_price'] = null;
                $data['percentage'] = null;
            }

            $model->offer()->updateOrCreate(['product_id' => $model->id], $data);
        } else {
            if ($model->offer) {
                $model->offer()->delete();
            }
        }
    }

    public function variationOffer($model, $request)
    {
        if (isset($request['status']) && $request['status'] == 'on') {
            $model->offer()->updateOrCreate(
                ['product_variant_id' => $model->id],
                [
                    'status' => ($request['status'] == 'on') ? true : false,
                    'offer_price' => $request['offer_price'] ? $request['offer_price'] : $model->offer->offer_price,
                    'start_at' => $request['start_at'] ? $request['start_at'] : $model->offer->start_at,
                    'end_at' => $request['end_at'] ? $request['end_at'] : $model->offer->end_at,
                ]
            );
        } else {
            if ($model->offer) {
                $model->offer()->delete();
            }
        }
    }

    public function getProductDetailsById($id)
    {
        $product = $this->product->query();

        $product = $product->with([
            'categories',
            'tags',
            'images',
            'offer',
            'variants' => function ($q) {
                $q->with([
                    'offer', 'productValues' => function ($q) {
                        $q->with(['productOption.option', 'optionValue']);
                    },
                ]);
            },
            'addOns' => function ($q) {
                $q->with('addOnOptions');
            },
        ]);

        $product = $product->find($id);
        return $product;
    }

    public function approveProduct($request, $id)
    {
        DB::beginTransaction();
        $product = $this->findById($id);

        try {
            $data = [];
            if (auth()->user()->can('review_products')) {
                $data['pending_for_approval'] = !is_null($request->pending_for_approval) ? true : false;
                $product->update($data);
            } else {
                return false;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function searchByNameOrSku($request, $with = [])
    {
        return $this->product->where(function ($query) use (&$request) {
            $query
                ->where("id", $request->search)
                ->orWhere("sku", 'like', '%' . $request->search . '%')
                ->orWhere(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->input('search') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->input('search') . '%');
                });
        })
            ->when(is_array($request->excludeIds), function ($query) use (&$request) {
                $query->whereNotIn("id", $request->excludeIds);
            })
            ->with($with)
            ->take(20)
            ->get();
    }

    public function getProductsByIds($ids, $with = [])
    {
        return $this->product->whereIn("id", $ids)->with($with)->get();
    }
}
