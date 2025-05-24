<?php

namespace Modules\Wrapping\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Wrapping\Http\Requests\Dashboard\CardRequest;
use Modules\Wrapping\Transformers\Dashboard\CardResource;
use Modules\Wrapping\Repositories\Dashboard\CardRepository as Card;

class CardController extends Controller
{
    protected $card;

    function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function index()
    {
        return view('wrapping::dashboard.cards.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->card->QueryTable($request));

        $datatable['data'] = CardResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        return view('wrapping::dashboard.cards.create');
    }

    public function store(CardRequest $request)
    {
        try {
            $create = $this->card->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('wrapping::dashboard.cards.show');
    }

    public function edit($id)
    {
        $card = $this->card->findById($id);
        return view('wrapping::dashboard.cards.edit', compact('card'));
    }

    public function clone($id)
    {
        $card = $this->card->findById($id);

        return view('wrapping::dashboard.cards.clone', compact('card'));
    }

    public function update(CardRequest $request, $id)
    {
        try {
            $update = $this->card->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->card->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->card->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
