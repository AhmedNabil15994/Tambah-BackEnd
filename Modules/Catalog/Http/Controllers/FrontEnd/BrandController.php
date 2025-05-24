<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Catalog\Repositories\FrontEnd\BrandRepository;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;

class BrandController extends Controller
{
    protected $brand;

    function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $articles = $this->brand->getPagination();
            return response()->json([
                'html' => view('catalog::frontend.brands.components.builder', compact('articles'))->render()
            ]);
        }

        return view('catalog::frontend.brands.index');
    }

    public function show($slug)
    {
        $article = $this->brand->findBySlug($slug);
        return view('catalog::frontend.brands.show', compact('article'));
    }
}
