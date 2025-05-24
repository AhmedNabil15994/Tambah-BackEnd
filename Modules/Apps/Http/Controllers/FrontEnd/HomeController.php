<?php

namespace Modules\Apps\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Apps\Http\Requests\FrontEnd\ContactUsRequest;
use Modules\Apps\Notifications\FrontEnd\ContactUsNotification;
use Modules\Apps\Repositories\Frontend\AppHomeRepository as Home;
use Modules\Apps\Transformers\Frontend\HomeFilterResource;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository;
use Notification;

class HomeController extends Controller
{
    protected $home;
    protected $slider;
    protected $product;

    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        if (config('setting.other.enable_website') != '1') {
            return view('apps::frontend.landing');
        } else {
            $this->home = new Home;
            $home_sections = $this->home->getAll($request);
            $home_sections = view('apps::frontend.home-sections.section-builder', compact('home_sections'))->render();
            return view('apps::frontend.index', compact('home_sections'));
        }
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function filter(Request $request)
    {
        $results = HomeFilterResource::collection($this->product->autoCompleteSearch($request)->take(30)->get(['title', 'id', 'slug']))->jsonSerialize();
        $response = view('apps::frontend.components.live-search-menu', compact('results'))->render();
        return response()->json(['html' => $response]);
    }

    public function contactUs()
    {
        return view('apps::frontend.contact-us');
    }

    public function sendContactUs(ContactUsRequest $request)
    {
        $request->merge([
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'message' => $request->message,
        ]);
        Notification::route('mail', config('setting.contact_us.email'))
            ->notify((new ContactUsNotification($request))->locale(locale()));

        return redirect()->back()->with(['status' => __('apps::frontend.contact_us.alerts.send_message')]);
    }
}
