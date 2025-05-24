<?php

namespace Modules\Occasion\Http\Controllers\WebService;

use Illuminate\Http\Request;

use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Occasion\Transformers\WebService\OccasionResource;
use Modules\Occasion\Http\Requests\WebService\OccasionRequest;
use Modules\Occasion\Repositories\WebService\OccasionRepository as Occasion;

class OccasionController extends WebServiceController
{
    protected $occasion;

    function __construct(Occasion $occasion)
    {
        $this->occasion = $occasion;
    }

    public function index(Request $request)
    {
        $occasions = $this->occasion->getAll();
        $result = $occasions ? OccasionResource::collection($occasions) : [];
        return $this->response($result);
    }

    public function show($id)
    {
        $occasion = $this->occasion->findById($id);
        $result = $occasion ? new OccasionResource($occasion) : null;
        return $this->response($result);
    }

    public function store(OccasionRequest $request)
    {
        $occ = $this->occasion->create($request);
        if ($occ) {
            return $this->response(new OccasionResource($occ));
        } else {
            return $this->error(__('occasion::webservice.occasion.oops_error'));
        }
    }

    public function update(OccasionRequest $request, $id)
    {
        $occ = $this->occasion->update($request, $id);
        if ($occ) {
            return $this->response(new OccasionResource($occ));
        } else {
            return $this->error(__('occasion::webservice.occasion.oops_error'));
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->occasion->delete($id);

            if ($delete) {
                return $this->response([], __('apps::dashboard.general.message_delete_success'));
            }

            return $this->error(__('apps::dashboard.general.message_error'));

        } catch (\Exception $e) {
            return $this->error($e->errorInfo[2]);

        }
    }

}
