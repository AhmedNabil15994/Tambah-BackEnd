<?php

namespace Modules\Attribute\Repositories\Dashboard;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Attribute\Entities\Attribute;
use Modules\Attribute\Entities\Attribute as Model;
use Modules\Core\Traits\CoreTrait;

class AttributeRepository
{
    use CoreTrait;

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $models = Cache::rememberForever('attributes', function () use ($order, $sort) {
            return $this->model->active()->orderBy($order, $sort)->get();
        });
        return $models;
    }

    public function getAllForForm($with = [])
    {
        return $this->model->with($with)->doesntHave('childAttributes')->active()->latest()->get();
    }

    public function getStatistics()
    {
        $count = $this->model->count();
        return ["count" => $count];
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $models = $this->model->orderBy($order, $sort)->get();
        return $models;
    }

    public function findById($id, $with = [])
    {
        $model = $this->model->with($with)->withDeleted()->findOrFail($id);
        return $model;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'status' => $request->status ? 1 : 0,
                "show_in_search" => $request->show_in_search ? 1 : 0,
                "validation" => $request->validation,
                "json_data" => $request->json_data,
                "type" => $request->type,
                "name" => $request->name,
                'price' => $request->price,
                'sort' => $request->sort ?? $this->model->count() + 1,
            ];

            if (!is_null($request->icon)) {
                $imgName = $this->uploadImage(public_path(config('core.config.attributes_img_path')), $request->icon);
                $data['icon'] = config('core.config.attributes_img_path') . '/' . $imgName;
            } else {
                $data['icon'] = config('core.config.attributes_img_path') . '/default.png';
            }

            $model = $this->model->create($data);
            $allCatalogData = [];
            $catalog_type = $request->catalog_type ?? [];
            if (count($catalog_type)) {
                foreach ($catalog_type as $type) {

                    switch (Attribute::CATALOGS_TYPES[$type]) {
                        case 'multiSelect':
                            if (isset($request->$type) && count($request->$type) > 0) {
                                $model->$type()->attach($request->$type, [
                                    "attribute_type" => 'input',
                                ]);
                            } else {
                                $allCatalogData[] = $type;
                            }
                            break;

                        case 'childAttributes':
                            $this->createChildAttributes($request->$type, $model);
                            // $model->childAttributes()->attach($request->$type, [
                            //     "attribute_type" => 'input'
                            // ]);
                            break;

                        default:
                            $model->catalogs()->create([
                                'catalogable_type' => $type,
                                "attribute_type" => 'input',
                            ]);
                            break;
                    }
                }
            }

            $model->update(['all_catalog_data' => count($allCatalogData) > 0 ? $allCatalogData : null]);

            $this->createOptions($model, $request);

            DB::commit();
            Cache::forget("attributes");
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createChildAttributes($data, $model)
    {
        if (isset($data['attributes'])) {

            foreach ($data['attributes'] as $key => $attributeId) {
                $attr = $this->findById($attributeId);

                $model->childAttributes()->attach($attr->id, [
                    'json_data' => json_encode([
                        'action' => isset($data['action'][$key]) ? $data['action'][$key] : null,
                        'option' => isset($data['option'][$key]) ? $data['option'][$key] : null,
                    ]),
                ]);
            }
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $model = $this->findById($id);
        $request->restore ? $this->restoreSoftDelete($model) : null;

        try {
            $data = [
                'status' => $request->status ? 1 : 0,
                "show_in_search" => $request->show_in_search ? 1 : 0,
                "validation" => $request->validation,
                "json_data" => $request->json_data,
                "type" => $request->type,
                "name" => $request->name,
                'price' => $request->price,
                'sort' => $request->sort ?? $model->sort,
            ];

            if ($request->icon) {
                if (!empty($model->icon) && !in_array($model->icon, config('core.config.special_images'))) {
                    File::delete($model->icon); ### Delete old image
                }
                $imgName = $this->uploadImage(public_path(config('core.config.attributes_img_path')), $request->icon);
                $data['icon'] = config('core.config.attributes_img_path') . '/' . $imgName;
            } else {
                $data['icon'] = $model->icon;
            }

            $model->fill($data);
            $model->save();
            $this->createOptions($model, $request);
            $this->updateOptions($model, $request);
            $this->deleteOptions($model, $request);

            $catalog_type = $request->catalog_type ?? [];
            $original_catalog_type = Attribute::CATALOGS_TYPES;
            $allCatalogData = [];

            foreach ($original_catalog_type as $type => $inputName) {
                if (in_array($type, $catalog_type)) {

                    switch ($inputName) {
                        case 'multiSelect':
                            if (isset($request->$type) && count($request->$type) > 0) {
                                $model->$type()->sync($request->$type, [
                                    "attribute_type" => 'input',
                                ]);
                            } else {
                                $model->$type()->detach();
                                $allCatalogData[] = $type;
                            }

                            break;
                        case 'childAttributes':
                            $this->updateChildAttributes($request->$type, $model);
                            break;
                        default:
                            $model->catalogs()->updateOrCreate(['catalogable_type' => $type, 'attribute_id' => $model->id], [
                                'catalogable_type' => $type,
                                "attribute_type" => 'input',
                            ]);
                            break;
                    }
                } else {
                    if ($inputName) {
                        $model->$type()->detach();
                    } else {
                        $model->catalogs()->GetCatalogAttrByType($type)->delete();
                    }
                }
            }

            $model->update(['all_catalog_data' => count($allCatalogData) > 0 ? $allCatalogData : null]);

            DB::commit();
            Cache::forget("attributes");
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateChildAttributes($data, $model)
    {
        $updateData = [];

        if (isset($data['attributes'])) {

            foreach ($data['attributes'] as $key => $attributeId) {
                $attr = $this->findById($attributeId);

                $updateData[$attr->id] = [
                    'json_data' => json_encode([
                        'action' => isset($data['action'][$key]) ? $data['action'][$key] : null,
                        'option' => isset($data['option'][$key]) ? $data['option'][$key] : null,
                    ]),
                ];
            }

            $model->childAttributes()->sync($updateData);
        }
    }

    public function restoreSoftDelete($model)
    {
        $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findById($id);
            if ($model && !empty($model->icon) && !in_array($model->icon, config('core.config.special_images'))) {
                File::delete($model->icon); ### Delete old image
            }

            if ($model->trashed()):

                $model->forceDelete();
            else:
                $model->delete();
            endif;

            DB::commit();
            Cache::forget("attributes");
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
                    $this->delete($id);
                }

            }

            DB::commit();
            Cache::forget("attributes");
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->model->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere('name', 'like', '%' . $request->input('search.value') . '%');
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // Search Sections by Created Dates
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

        if (isset($request['req']['type'])) {
            $query->where("type", $request['req']['type']);
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '0') {
            $query->unactive();
        }

        return $query;
    }

    public function createOptions(&$model, &$request)
    {
        if (is_array($request->option)) {
            $model->options()->createMany($request->option);
        }
    }

    public function updateOptions(&$model, &$request)
    {
        if (is_array($request->edit_option)) {
            foreach ($request->edit_option as $option) {
                $model->options()->updateOrCreate(
                    [
                        "id" => $option["id"],
                    ],
                    $option
                );
            }
        }
    }

    public function deleteOptions(&$model, &$request)
    {
        if (is_array($request->deleteOptions)) {
            $model->options()->whereIn("id", $request->deleteOptions)->delete();
        }
    }
}
