<div class="note well">
    <div class="company-address">
        @foreach($attrValues as $value)
            <span class="bold"> {{$value->name}} : </span> {{$value->value}} <br>
        @endforeach
    </div>
</div>
