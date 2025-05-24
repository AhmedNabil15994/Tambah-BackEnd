<!DOCTYPE html>
<html lang="{{ locale() }}" dir="{{ is_rtl() }}" 
      data-currency="KWD"  
      data-total="{{auth()->check() ? auth()->user()->getTotoalCahsierOrder() : 0}}"
      data-user="{{auth()->check() ? auth()->user() : null}}"
      >

    @if (is_rtl() == 'rtl')
      @include('pos::cashier.layouts._head_rtl')
    @else
      @include('pos::cashier.layouts._head_ltr')
    @endif

    <body id="displayfullscree">
      <div id="appVue">
         @include('pos::cashier.layouts._header')


          @yield('content')
          {{-- <exemple :test="{{ json_encode(['id'=>1]) }}" ></exemple> --}}
      </div>

        @include('pos::cashier.layouts._jquery')
        @include('pos::cashier.layouts._js')
       
    </body>
</html>
