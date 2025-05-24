<div>
    <button class="btn btn-block btn-info btn-lg printButton " style="margin-bottom: 10px" >Print <i class="fa fa-print"></i></button>
    <div class="printer-container"  >
        @php($loop_count = 0)
         {{-- check is is new paper --}}

        @foreach ($products as $product)

            @php($productRequest = $proudctsRequest->firstWhere("id", $product->id))





            {{-- render itme product --}}
            @if($productRequest)

                {{-- product --}}
                @if($productRequest["num"] >= 1)
                    @foreach (range(1,$productRequest["num"] ) as $counterVariation)


                        @php($loop_count++)
                        @if (is_new_paper($barcode_details, $loop_count))
                        {{-- Actual Paper --}}
                            <div style="@if(!$barcode_details->is_continuous) height:{{$barcode_details->paper_height}}in !important; @else height:{{$barcode_details->height}}in !important; @endif width:{{$barcode_details->paper_width}}in !important; line-height: 16px !important;" class=" @if(!$barcode_details->is_continuous) label-border-outer page-break @endif">

                            {{-- Paper Internal --}}
                            <div style="@if(!$barcode_details->is_continuous)margin-top:{{$barcode_details->top_margin}}in !important; margin-bottom:{{$barcode_details->top_margin}}in !important; margin-left:{{$barcode_details->left_margin}}in !important;margin-right:{{$barcode_details->left_margin}}in !important;@endif" class="label-border-internal">
                        @endif


                        <div class="label-print" style="
                                height:{{$barcode_details->height}}in !important; width:{{$barcode_details->width}}in !important;
                                 margin-left:{{$barcode_details->col_distance}}in !important;
                                 margin-top:{{$barcode_details->row_distance}}in !important;
                                " class="text-center" >
                            <div class="items" >

                                @if ($request->show_product_name)
                                    <div class="item-barcode" style="display: flex; justify-content: center;">
                                        {{ $product->title}}
                                    </div>
                                @endif

                                @if ($request->show_sku)
                                    <div class="item-barcode" >
                                        <span style="font-weight: bold;">SkU</span> : {{$product->sku}}
                                    </div>
                                @endif

                                @if ($request->show_price)
                                    <div class="item-barcode">
                                        <span style="font-weight: bold;">Price</span> : {{$product->price}} KWT
                                    </div>
                                @endif

                                @if ($request->show_supplier)
                                    <div class="item-barcode">
                                            {{$product->vendor->title }}
                                    </div>
                                @endif
                                <div class="barcode-image item-barcode">
                                    <img class=""
                                     style="max-width:90%; !important;height: {{$barcode_details->height*0.24}}in !important;"
                                     src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, $request->barcode_type ?? "C39+", 3, 30,array(39, 48, 54), true)}}" >
                                </div>
                            </div>


                        </div>





                        @if(is_paper_end($barcode_details, $loop_count))
                            {{-- Actual Paper --}}
                            </div>

                            {{-- Paper Internal --}}
                            </div>
                            {{-- <div class="page-break"></div> --}}
                        @endif
                    @endforeach

                @endif


                {{-- variation --}}
                @if(isset($productRequest["variants"]) && is_array($productRequest["variants"]) )
                    @foreach ($productRequest["variants"] as $variationRequest )
                        @if($variationRequest["num"] >= 1)
                            @php(
                                $varaition = $product->variants->where("id", $variationRequest["id"] )->first()
                            )

                            @if($varaition)
                                    @foreach (range(1,$variationRequest["num"] ) as $counterVariation)
                                        @php($loop_count++)
                                        @if (is_new_paper($barcode_details, $loop_count))
                                        {{-- Actual Paper --}}
                                            <div style="@if(!$barcode_details->is_continuous) height:{{$barcode_details->paper_height}}in !important; @else height:{{$barcode_details->height}}in !important; @endif width:{{$barcode_details->paper_width}}in !important; line-height: 16px !important;" class=" @if(!$barcode_details->is_continuous) label-border-outer page-break @endif">

                                            {{-- Paper Internal --}}
                                            <div style="@if(!$barcode_details->is_continuous)margin-top:{{$barcode_details->top_margin}}in !important; margin-bottom:{{$barcode_details->top_margin}}in !important; margin-left:{{$barcode_details->left_margin}}in !important;margin-right:{{$barcode_details->left_margin}}in !important;@endif" class="label-border-internal">
                                        @endif
                                        <div class="label-print" style="
                                                height:{{$barcode_details->height}}in !important; width:{{$barcode_details->width}}in !important;
                                                margin-left:{{$barcode_details->col_distance}}in !important;
                                                margin-top:{{$barcode_details->row_distance}}in !important;
                                                " class="text-center" >
                                            <div class="items" >
                                                @if ($request->show_product_name)
                                                    <div class="item-barcode" style="display: flex; justify-content: center;">
                                                        {{-- {{ $varaition->getTitle()}} --}}
                                                        {{ $product->title}}
                                                    </div>
                                                @endif

                                                @if ($request->show_sku)
                                                    <div class="item-barcode" >
                                                        <span style="font-weight: bold;">SkU</span> : {{$varaition->sku}}
                                                    </div>
                                                @endif

                                                @if ($request->show_price)
                                                    <div class="item-barcode">
                                                        <span style="font-weight: bold;">Price</span> : {{$varaition->price}} KWT
                                                    </div>
                                                @endif

                                                @if ($request->show_variation)
                                                    {{-- @foreach ($varaition->productValues as $value)
                                                        <div class="item-barcode">
                                                            <span style="font-weight: bold;">{{ $value->productOption->option->title }}</span> : {{$value->optionValue->title}}
                                                        </div>
                                                    @endforeach --}}

                                                    {{-- variation --}}
                                                    <div class="item-barcode">
                                                        {{ str_replace("-", " |", $varaition->getTitle()) }}
                                                    </div>

                                                @endif


                                                @if ($request->show_supplier)
                                                    <div class="item-barcode">
                                                            {{$product->vendor->title }}
                                                    </div>
                                                @endif

                                                <div class="barcode-image item-barcode">
                                                    <img class=""
                                                     style="max-width:90%; !important;height: {{$barcode_details->height*0.24}}in !important;"
                                                     src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, $request->barcode_type ?? "C39+", 3, 30,array(39, 48, 54), true)}}" >
                                                </div>

                                            </div>

                                        </div>

                                        @if(is_paper_end($barcode_details, $loop_count))
                                            {{-- Actual Paper --}}
                                            </div>

                                            {{-- Paper Internal --}}
                                            </div>
                                            {{-- <div class="page-break"></div> --}}
                                        @endif

                                    @endforeach
                            @endif

                        @endif
                    @endforeach
                @endif





            @endif


        @endforeach

        @if(!is_paper_end($barcode_details, $loop_count))
            {{-- Actual Paper --}}
            </div>

            {{-- Paper Internal --}}
            </div>

        @endif

    </div>
</div>

<style>
    @media print{
	    .content-wrapper{
	      border-left: none !important; /*fix border issue on invoice*/
	    }
	    .label-border-outer{
	        border: none !important;
	    }
	    .label-border-internal{
	        border: none !important;
	    }
	    .sticker-border{
	        border: none !important;
	    }
	    #preview_box{
	        padding-left: 0px !important;
	    }
	    #toast-container{
	        display: none !important;
	    }
	    .tooltip{
	        display: none !important;
	    }
	    .btn{
	    	display: none !important;
	    }
	}
    @page {
		size: {{$barcode_details->paper_width}}in @if(!$barcode_details->is_continuous && $barcode_details->paper_height != 0){{$barcode_details->paper_height}}in @endif;
		/*width: {{$barcode_details->paper_width}}in !important;*/
		/*height:@if($barcode_details->paper_height != 0){{$barcode_details->paper_height}}in !important @else auto @endif;*/
		margin-top: 0in;
		margin-bottom: 0in;
		margin-left: 0in;
		margin-right: 0in;

		@if($barcode_details->is_continuous)
			/*page-break-inside : avoid !important;*/
		@endif
	}
</style>