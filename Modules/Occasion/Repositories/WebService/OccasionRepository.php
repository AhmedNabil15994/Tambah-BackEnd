<?php

namespace Modules\Occasion\Repositories\WebService;

use Modules\Occasion\Entities\Occasion;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\SyncRelationModel;

class OccasionRepository
{
    use SyncRelationModel;

    protected $occasion;

    function __construct(Occasion $occasion)
    {
        $this->occasion = $occasion;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $occasions = $this->occasion->orderBy($order, $sort)->where('user_id', auth()->user()->id)->get();
        return $occasions;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $occasions = $this->occasion->orderBy($order, $sort)->where('user_id', auth()->user()->id)->active()->get();
        return $occasions;
    }

    public function findById($id)
    {
        $occasion = $this->occasion->withDeleted()->with('category')->where('user_id', auth()->user()->id)->find($id);
        return $occasion;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $user_id = auth()->user()->id;
            $occasion = $this->occasion->create([
                'user_id' => $user_id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'occasion_date' => $request->occasion_date,
            ]);

            DB::commit();
            return $occasion;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        $occasion = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($occasion) : null;

        try {
            $occasion->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'occasion_date' => $request->occasion_date,
            ]);

            $updatedOccasion = $this->findById($id);

            DB::commit();
            return $updatedOccasion;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($model)
    {
        return $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);

            if ($model) {
                if ($model->trashed()):
                    $model->forceDelete();
                else:
                    $model->delete();
                endif;
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

}
