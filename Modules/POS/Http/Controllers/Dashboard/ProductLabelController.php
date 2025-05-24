<?php

namespace Modules\POS\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Modules\Catalog\Transformers\Dashboard\ProductSearchResource;
use Modules\POS\Entities\Barcode;
use Modules\POS\Http\Requests\Dashboard\BarcodeProductLabelRequest;
use Modules\POS\Repositories\Dashboard\LabelRepository as Repo;

class ProductLabelController extends Controller
{
    protected $repo;
    protected $product;

    public function __construct(Repo $repo, Product $product)
    {
        $this->repo = $repo;
        $this->product = $product;
    }

    public function index(Request $request)
    {
        // dd($request->all());
        return view('pos::dashboard.label.index');
    }

    public function search(Request $request)
    {
        $products = $this->product->searchByNameOrSku($request, ["variants"]);
        return response()->json(ProductSearchResource::collection($products));
    }

    public function renderLabel(BarcodeProductLabelRequest $request)
    {
        $productIds = $request->product ? array_column($request->product, "id") : [];
        $barcode_details = Barcode::find($request->barcode_id);
        if (is_null($barcode_details)) {
            return response()->json([false, 'Select barcode settings']);
        }

        // $barcode_details->stickers_in_one_sheet = $barcode_details->is_continuous ? $barcode_details->stickers_in_one_row : $barcode_details->stickers_in_one_sheet;
        // $barcode_details->paper_height = $barcode_details->is_continuous ? $barcode_details->height : $barcode_details->paper_height;
        // dd($barcode_details->toArray());
        if ($barcode_details->stickers_in_one_row == 1) {
            $barcode_details->col_distance = 0;
            $barcode_details->row_distance = 0;
        }
        $margin_top = $barcode_details->is_continuous ? 0 : $barcode_details->top_margin * 1;
        $margin_left = $barcode_details->is_continuous ? 0 : $barcode_details->left_margin * 1;
        $paper_width = $barcode_details->paper_width * 1;

        $total_qty = $this->quantityLabel($request);
        $paper_height = $barcode_details->paper_height ? $barcode_details->paper_height : $total_qty * $barcode_details->height;
        // dd($barcode_details->toArray());
        $proudctsRequest = collect($request->product)->unique("id");
        $products = $this->product->getProductsByIds($productIds, ["variants"]);
        // dd($barcode_details->toArray());
        $html = view(
            "pos::dashboard.label.ajex.labelPrint3",
            compact("request", "products", "proudctsRequest", "barcode_details", "margin_top", "margin_left", "paper_height", "total_qty")
        )->render();
        return response()->json(["html" => $html]);
    }

    public function quantityLabel($request)
    {
        $q = 0;
        foreach ($request->product as $product) {
            # code...]
            $q += $product["num"];
            if (isset($product["variants"]) && is_array($product["variants"])) {
                foreach ($product["variants"] as $variant) {
                    # code...
                    $q += $variant["num"];
                }
            }
        }

        return $q;
    }
}
