<?php

namespace Modules\Wrapping\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wrapping\Repositories\FrontEnd\WrappingRepository as Wrapping;

class WrappingController extends Controller
{
    protected $wrapping;

    function __construct(Wrapping $wrapping)
    {
        $this->wrapping = $wrapping;
    }

    public function index(Request $request)
    {
        $items = [];
        $items['gifts'] = $this->wrapping->getAllGifts();
        $items['cards'] = $this->wrapping->getAllCards();
        $items['addons'] = $this->wrapping->getAllAddons();

        return view('wrapping::frontend.wrapping.index', compact('items'));
    }


}
