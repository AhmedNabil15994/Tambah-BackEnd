<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
        <link rel="icon" href="/poss/images/favicon.png">
        <link rel="stylesheet" href="/poss/css/bootstrap.min.css">
        <link rel="stylesheet" href="/poss/css/themify-icons.css">
        <link rel="stylesheet" href="/poss/css/invoice.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.0.55/print.min.css" integrity="sha512-10r4jTZDvxGIGpQUK1Odu6gknooxC7LvWCRID8fYj4UNwMr31K/EnUp+78WLgPqc+2vq03SCXYTZW7/dYUDskg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.0.55/print.min.js" integrity="sha512-b7HB4vwMMFmHHJdx4RtgHNoCUO453pOBpxJ+uWSvoxeV7drH56OqEIsGVS25Wm0tmE7zyGZkKlIiGk4Ouc3hOQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body>
        <a href="javascript:window.print()" class="print-button"><i class="ti-printer"></i> Print this invoice</a>
        <button type="button" onclick="printJS(
            { printable: 'invoice', type: 'html', css:[
                '/poss/css/bootstrap.min.css',
               '/poss/css/themify-icons.css',
               '/poss/css/invoice.css'

            ],
            header: 'PrintJS - Form Element Selection'

             }
        )">
            Print Form
         </button>
        <div id="invoice">
            <div id="logo">
                <!--<img src="images/logo2.png" alt="">-->
                <h3>Tocaan POS</h3>
            </div>
            <!-- Client & Supplier -->
            <div class="info">
                <p class="d-flex">
                    <strong class="flex-1">Order ID</strong>
                    <span> #{{$order->id}}</span>
                </p>
                <p class="d-flex">
                    <strong class="flex-1">Customer</strong>
                    <span> {{$order->user->name}}</span>
                </p>
                <p class="d-flex">
                    <strong class="flex-1">Seller</strong>
                    <span> {{$order->cashier->name}}</span>
                </p>
                <p class="d-flex">
                    <strong class="flex-1">Date</strong>
                    <span> {{$order->created_at->format("d-m-Y h:i a")}}</span>
                </p>
            </div>


            <!-- Invoice -->
            <div class="row">
                <div class="col-md-12">
                    <table class="margin-top-20">
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        @forelse ($order->allProducts as $item)
                            <tr>
                                <td>{{$item->title}}</td>
                                <td>{{$item->qty}}</td>
                                <td> {{$item->selling_price}}</td>
                                <td> {{$item->total}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <p class="lead">Empty May be all refund</p>
                                </td>
                            </tr>
                        @endforelse


                    </table>
                </div>

                <div id="totals">
                    <table>
                        {{-- <tr>
                            <td>Shipping</td>
                            <td><span> 10.00</span></td>
                        </tr> --}}
                        {{-- <tr>
                            <td>Taxes</td>
                            <td><span> 12.00</span></td>
                        </tr> --}}
                        <tr class="total-price">
                            <td>Total</td>
                            <td><span> {{$order->total}}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- delivery Address -->


            @if($order->orderAddress != null)
                <div class="info">
                    @if(!is_null($order->orderAddress->state))

                        <p class="d-flex">
                            <strong class="flex-1">
                                {{ $order->orderAddress->state->city->title }}
                                /
                                {{ $order->orderAddress->state->title }}
                            </strong>
                        </p>
                    @endif
                    <br/>

                    @if($order->orderAddress->governorate)

                        <p class="d-flex">
                            <strong class="flex-1">Governorate </strong>
                            <span>{{ $order->orderAddress->governorate }}</span>
                        </p>
                    @endif

                    @if($order->orderAddress->block)

                        <p class="d-flex">
                            <strong class="flex-1">Block </strong>
                            <span>{{ $order->orderAddress->block }}</span>
                        </p>
                    @endif

                    @if($order->orderAddress->district)

                        <p class="d-flex">
                            <strong class="flex-1">District </strong>
                            <span>{{ $order->orderAddress->district }}</span>
                        </p>
                    @endif

                    @if($order->orderAddress->street)

                        <p class="d-flex">
                            <strong class="flex-1">Street </strong>
                            <span>{{ $order->orderAddress->street }}</span>
                        </p>
                    @endif

                    @if($order->orderAddress->building)

                        <p class="d-flex">
                            <strong class="flex-1">Building </strong>
                            <span>{{ $order->orderAddress->building }}</span>
                        </p>
                    @endif

                    @if($order->orderAddress->floor)

                        <p class="d-flex">
                            <strong class="flex-1">Floor</strong>
                            <span>{{ $order->orderAddress->floor }}</span>
                        </p>
                    @endif

                    @if($order->orderAddress->flat)

                        <p class="d-flex">
                            <strong class="flex-1">Flat </strong>
                            <span>{{ $order->orderAddress->flat }}</span>
                        </p>
                    @endif

                    <p class="d-flex">
                        <strong class="flex-1">Details </strong>
                        <span>{{ optional($order->orderAddress)->address ?? '---' }}</span>
                    </p>
                </div>
            @endif

            <!-- Footer -->
            <div id="footer">
                <p>Thanks You for Choosing Us.</p>
                <ul>
                    <li>{{config("app.url")}}</li>
                    <li>(123) 123-456</li>
                </ul>
            </div>
        </div>
    </body>
    <script>

    </script>
</html>

