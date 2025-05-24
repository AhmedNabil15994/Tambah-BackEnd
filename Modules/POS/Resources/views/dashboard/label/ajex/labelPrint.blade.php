<div>
    <button class="btn btn-block btn-info btn-lg printButton " >Print <i class="fa fa-print"></i></button>
    <div class="row printer-container" style="padding: 10px; margin-top: 20px">

        @foreach ($products as $product)
            @php($productRequest = $proudctsRequest->firstWhere("id", $product->id))




            {{-- render itme product --}}
            @if($productRequest)

                {{-- product --}}
                @if($productRequest["num"] >= 1)
                    @foreach (range(1,$productRequest["num"] ) as $counterVariation)
                        <div class="col-md-3 col-sm-3 barcode" >
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

                            </div>
                            <div class="barcode-image item-barcode">
                                <img class="img-responsive " src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, $request->barcode_type ?? "C39+")}}" >
                            </div>
                        </div>
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
                                    <div class="col-md-3  col-sm-3 barcode">
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

                                        </div>
                                        <div class="barcode-image item-barcode">
                                            <img class="img-responsive" style="max-width: 80%" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, $request->barcode_type ?? "C39+")}}" >
                                        </div>
                                    </div>
                                    @endforeach
                            @endif

                        @endif
                    @endforeach
                @endif


            @endif

        @endforeach

    </div>
</div>