<div>
    <button class="btn btn-block btn-info btn-lg printButton " >Print <i class="fa fa-print"></i></button>
    <div class="printer-container">

        @php($loop_count = 0)

        @foreach ($products as $product)
            @php($productRequest = $proudctsRequest->firstWhere("id", $product->id))




                {{-- render itme product --}}
                @if($productRequest)



                    {{-- product --}}
                    @if($productRequest["num"] >= 1)

                        @foreach (range(1,$productRequest["num"] ) as $counterVariation)

                            {{-- handle somme variable --}}
                            <?php
                                $loop_count += 1;
                                $is_new_row = ($barcode_details->stickers_in_one_row == 1 || ($loop_count % $barcode_details->stickers_in_one_row) == 1) ? true : false;

                                $is_new_paper = ($barcode_details->is_continuous && $is_new_row) || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 1));

                                $is_paper_end = (($barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_row == 0)) || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 0)));

                            ?>

                            {{-- =========== --}}

                            {{-- page handler --}}
                            @if($is_new_paper)
                                {{-- Actual Paper --}}
                                <div style="@if(!$barcode_details->is_continuous) height:{{$barcode_details->paper_height}}in !important; @else height:{{$barcode_details->height}}in !important; @endif width:{{$barcode_details->paper_width}}in !important; line-height: 16px !important;" class="@if(!$barcode_details->is_continuous) label-border-outer @endif">

                                {{-- Paper Internal --}}
                                <div style="@if(!$barcode_details->is_continuous)margin-top:{{$barcode_details->top_margin}}in !important; margin-bottom:{{$barcode_details->top_margin}}in !important; margin-left:{{$barcode_details->left_margin}}in !important;margin-right:{{$barcode_details->left_margin}}in !important;@endif" class="label-border-internal">
                            @endif
                            {{-- ================= --}}
                            <?php
                                if((!$barcode_details->is_continuous) && ($loop_count % $barcode_details->stickers_in_one_sheet) <= $barcode_details->stickers_in_one_row)
                                     $first_row = true;
                                elseif($barcode_details->is_continuous && ($loop_count <= $barcode_details->stickers_in_one_row) )
                                     $first_row = true;
                                else $first_row = false;

                            ?>

                            {{-- some condtion --}}

                            <div class=""
                                 style="height:{{$barcode_details->height}}in !important; line-height: {{$barcode_details->height}}in; width:{{$barcode_details->width}}in !important; display: inline-block; @if(!$is_new_row) margin-left:{{$barcode_details->col_distance}}in !important; @endif @if(!$first_row)margin-top:{{$barcode_details->row_distance}}in !important; @endif" class="sticker-border text-center"
                             >
                                <div class="" style="display:inline-block;vertical-align:middle;line-height:16px !important;">
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

                                    <div class="item-barcode text-center">
                                        <img  style="max-width:80%; !important;height: {{$barcode_details->height*0.24}}in !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($product->sku,  $request->barcode_type ?? "C39+" , 3,30,array(39, 48, 54), true)}}">

                                        {{-- <img class="img-responsive " src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, $request->barcode_type ?? "C39+")}}" > --}}
                                    </div>

                                </div>

                            </div>

                            {{-- handle if end --}}

                            @if($is_paper_end)
                                {{-- Actual Paper --}}
                                </div>

                                {{-- Paper Internal --}}
                                </div>
                            @endif



                        @endforeach

                    @endif



                    @if(isset($productRequest["variants"]) && is_array($productRequest["variants"]) )
                        @foreach ($productRequest["variants"] as $variationRequest )
                            @if($variationRequest["num"] >= 1)
                                @php(
                                    $varaition = $product->variants->where("id", $variationRequest["id"] )->first()
                                )

                                @if($varaition)
                                        @foreach (range(1,$variationRequest["num"] ) as $counterVariation)

                                         {{-- handle somme variable --}}
                                                <?php
                                                $loop_count += 1;
                                                $is_new_row = ($barcode_details->stickers_in_one_row == 1 || ($loop_count % $barcode_details->stickers_in_one_row) == 1) ? true : false;

                                                $is_new_paper = ($barcode_details->is_continuous && $is_new_row) || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 1));

                                                $is_paper_end = (($barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_row == 0)) || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 0)));

                                            ?>

                                            {{-- =========== --}}

                                            {{-- page handler --}}
                                            @if($is_new_paper)
                                                {{-- Actual Paper --}}
                                                <div style="@if(!$barcode_details->is_continuous) height:{{$barcode_details->paper_height}}in !important; @else height:{{$barcode_details->height}}in !important; @endif width:{{$barcode_details->paper_width}}in !important; line-height: 16px !important;" class="@if(!$barcode_details->is_continuous) label-border-outer @endif">

                                                {{-- Paper Internal --}}
                                                <div style="@if(!$barcode_details->is_continuous)margin-top:{{$barcode_details->top_margin}}in !important; margin-bottom:{{$barcode_details->top_margin}}in !important; margin-left:{{$barcode_details->left_margin}}in !important;margin-right:{{$barcode_details->left_margin}}in !important;@endif" class="label-border-internal">
                                            @endif
                                            {{-- ================= --}}
                                            <?php
                                                if((!$barcode_details->is_continuous) && ($loop_count % $barcode_details->stickers_in_one_sheet) <= $barcode_details->stickers_in_one_row)
                                                    $first_row = true;
                                                elseif($barcode_details->is_continuous && ($loop_count <= $barcode_details->stickers_in_one_row) )
                                                    $first_row = true;
                                                else $first_row = false;

                                            ?>

                                            {{-- some condtion --}}

                                            <div class=""
                                                    style="height:{{$barcode_details->height}}in !important; line-height: {{$barcode_details->height}}in; width:{{$barcode_details->width}}in !important; display: inline-block; @if(!$is_new_row) margin-left:{{$barcode_details->col_distance}}in !important; @endif @if(!$first_row)margin-top:{{$barcode_details->row_distance}}in !important; @endif" class="sticker-border text-center"
                                             >
                                                <div class=" text-center" style="display:inline-block;vertical-align:middle;line-height:16px !important;">
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

                                                </div>
                                                <div class="barcode-image item-barcode">
                                                    <img class="center-block" style="max-width:90%; !important;height: {{$barcode_details->height*0.24}}in !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($product->sku,  $request->barcode_type ?? "C39+" , 3,30,array(39, 48, 54), true)}}">
                                                    {{-- <img class="img-responsive" style="max-width: 80%" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, $request->barcode_type ?? "C39+")}}" > --}}
                                                </div>
                                            </div>

                                             {{-- handle if end --}}

                                            @if($is_paper_end)
                                                {{-- Actual Paper --}}
                                                </div>

                                                {{-- Paper Internal --}}
                                                </div>
                                            @endif
                                        @endforeach
                                @endif

                            @endif
                        @endforeach
                    @endif


                @endif

        @endforeach


        @if(!$is_paper_end)
            {{-- Actual Paper --}}
            </div>

            {{-- Paper Internal --}}
            </div>
        @endif

    </div>
</div>


<style type="text/css">

	.text-center{
		text-align: center;
	}

	.text-uppercase{
		text-transform: uppercase;
	}

	/*Css related to printing of barcode*/
	.label-border-outer{
	    border: 0.1px solid grey !important;
	}
	.label-border-internal{
	    /*border: 0.1px dotted grey !important;*/
	}
	.sticker-border{
	    border: 0.1px dotted grey !important;
	    overflow: hidden;
	    box-sizing: border-box;
	}
	#preview_box{
	    padding-left: 30px !important;
	}
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

	@media print{
		#preview_body{
			display: block !important;
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