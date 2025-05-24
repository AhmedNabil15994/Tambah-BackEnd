<?php

namespace Modules\Catalog\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Category;
use Modules\Core\Traits\Attachment\Attachment;
use Modules\Core\Traits\Dashboard\DatatableExportTrait;

class CategoryRepository
{
    use DatatableExportTrait;

    protected $category;

    public function __construct()
    {
        $this->category = new Category;

        $this->setQueryActionsCols([
            '#' => 'id',
            __('catalog::dashboard.categories.datatable.status') => 'print_status',
            __('catalog::dashboard.categories.datatable.title') => 'title',
            __('catalog::dashboard.categories.datatable.type') => 'print_type',
            __('catalog::dashboard.categories.datatable.created_at') => 'created_at',
        ]);
        $this->exportFileName = 'categories';
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        return $this->category->orderBy($order, $sort)->get();
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->category->active()->orderBy($order, $sort)->get();
    }

    public function mainCategories($order = 'sort', $sort = 'asc')
    {
        $categories = $this->category->mainCategories()->orderBy($order, $sort)->get();
        return $categories;
    }

    public function findById($id)
    {
        $category = $this->category->withDeleted()->find($id);
        return $category;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $category = $this->category->create([
                'image' => $request->image ? Attachment::addAttachment($request['image'], 'categories') : url(config('setting.logo')),
                'banner_image' => $request->banner_image ? Attachment::addAttachment($request['banner_image'], 'categories') : null,
                'cover' => $request->cover ? path_without_domain($request->cover) : url(config('setting.logo')),
                'status' => $request->status ? 1 : 0,
                'open_sub_category' => $request->open_sub_category ? 1 : 0,
                'show_in_home' => $request->show_in_home ? 1 : 0,
                'category_id' => ($request->category_id != "null" && $request->category_id != 1) ? $request->category_id : null,
                'color' => $request->color ?? null,
                'sort' => $request->sort ?? 0,
                "title" => $request->title,
                "seo_description" => $request->seo_description,
                "seo_keywords" => $request->seo_keywords,
            ]);

            DB::commit();
            $this->refreshCategories();
            return $category;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $category = $this->findById($id);
        $request->restore ? $this->restoreSoftDelete($category) : null;

        try {
            $category->update([
                'banner_image' => $request->banner_image ? Attachment::addAttachment($request['banner_image'], 'categories') : $category->banner_image,
                'image' => $request->image ? Attachment::updateAttachment($request['image'], $category->image, 'categories') : $category->image,
                'cover' => $request->cover ? path_without_domain($request->cover) : $category->cover,
                'status' => $request->status ? 1 : 0,
                'open_sub_category' => $request->open_sub_category ? 1 : 0,
                'show_in_home' => $request->show_in_home ? 1 : 0,
                'category_id' => ($request->category_id != "null" && $request->category_id != 1) ? $request->category_id : null,
                'color' => $request->color ?? null,
                'sort' => $request->sort ?? 0,
                "title" => $request->title,
                "seo_description" => $request->seo_description,
                "seo_keywords" => $request->seo_keywords,
            ]);

            DB::commit();
            $this->refreshCategories();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updatePhoto($request)
    {
        DB::beginTransaction();

        $category = $this->findById($request->photo_id);

        try {

            if (auth()->user()->can('edit_products_image') && $request->image) {

                $category->update([
                    'image' => $request->image ? Attachment::updateAttachment($request['image'], $category->image, 'categories') : $category->image,
                ]);

                DB::commit();
                $category->fresh();
                return asset($category->image);
            }

            return false;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($model)
    {
        $model->restore();
        return true;
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findById($id);
            if ($model->trashed()):
                Attachment::deleteAttachment($model->banner_image);
                Attachment::deleteAttachment($model->image);
                $model->forceDelete();else:
                $model->delete();
            endif;

            DB::commit();
            $this->refreshCategories();
            return true;
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
                if ($id) {
                    $model = $this->delete($id);
                }

            }

            DB::commit();
            $this->refreshCategories();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->category
            ->where(function ($query) use ($request) {
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

        return $query;
    }

    private function refreshCategories()
    {

        foreach ($this->category->all() as $category) {

            $category->has_children = $category->children()->count() ? 1 : 0;
            $category->save();
        }
    }
}
