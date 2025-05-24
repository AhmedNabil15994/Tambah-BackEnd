@if ($product->inputAttributes()->count() > 0 ||
    (!is_null($variantPrd) && $variantPrd->product->inputAttributes()->count() > 0))

    @php
        $cartProductId = !is_null($variantPrd) ? 'var-' . $variantPrd->id : $product->id;
    @endphp

    @foreach ($product->inputAttributes() as $key => $attribute)
        <div class="form-group">
            <label>
                {{ $attribute->translate('name', locale()) }}
                <span id="attributePriceLabel-{{ $attribute->id }}">

                    @if (isset(getCartItemById($cartProductId)->attributes['productAttributes']['prices']) &&
                        !is_null(getCartItemById($cartProductId)->attributes['productAttributes']['prices'][$attribute->id] ?? null))
                        {{-- <b> / {{ __('catalog::frontend.products.attribute_price') }}: </b> --}}
                        /
                        {{ priceWithCurrenciesCode(getCartItemById($cartProductId)->attributes['productAttributes']['prices'][$attribute->id]) }}
                    @elseif (!is_null($attribute->price))
                        {{-- <b> / {{ __('catalog::frontend.products.attribute_price') }}: </b> --}}
                        / {{ priceWithCurrenciesCode($attribute->price) }}
                    @endif
                </span>
            </label>
            @if ($attribute->type == 'text')
                <input type="{{ $attribute->type }}" class="form-control productInputsAttributes"
                    data-id="{{ $attribute->id }}" {{ $attribute->validation['required'] == 1 ? 'required' : '' }}
                    {{ isset($attribute->validation['validate_max']) && $attribute->validation['validate_max'] == 1 ? 'maxlength=' . $attribute->validation['max'] ?? '' . '"' : '' }}
                    {{ isset($attribute->validation['validate_min']) && $attribute->validation['validate_min'] == 1 ? 'maxlength=' . $attribute->validation['min'] ?? '' . '"' : '' }}
                    name="productAttributes[{{ $attribute->id }}]" autocomplete="off"
                    value="{{ key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? []) ? getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id] : '' }}" />
            @elseif($attribute->type == 'drop_down')
                <select class="form-control productInputsAttributes"
                    {{ $attribute->validation['required'] == 1 ? 'required' : '' }} data-id="{{ $attribute->id }}"
                    onchange="onAttributeOptionChange(this, '{{ $attribute->id }}', 'select')"
                    name="productAttributes[{{ $attribute->id }}]">
                    @foreach ($attribute->options as $option)
                        <option value="{{ $option->id }}" data-price="{{ $option->price }}"
                            id="productAttributesOption-{{ $attribute->id }}-{{ $option->id }}"
                            {{ key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? []) && $option->id == getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id] ? 'selected' : '' }}>
                            {{ $option->value }}</option>
                    @endforeach
                </select>
            @elseif($attribute->type == 'radio')
                <div class="row">
                    @foreach ($attribute->options as $option)
                        <div class="col-md-4">
                            <label for="radi_{{ $option->id }}">{{ $option->value }}</label>
                            <input type="radio" class="productInputsAttributes"
                                name="productAttributes[{{ $attribute->id }}]" data-id="{{ $attribute->id }}"
                                data-price="{{ $option->price }}" id="radi_{{ $option->id }}"
                                onchange="onAttributeOptionChange(this, '{{ $attribute->id }}', 'radio')"
                                {{ $attribute->validation['required'] == 1 ? 'required' : '' }}
                                {{ key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? []) && $option->id == getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id] ? 'checked' : '' }}
                                value="{{ $option->id }}">
                        </div>
                    @endforeach
                </div>
            @elseif($attribute->type == 'boolean')
                <input type="checkbox" class="productInputsAttributes" name="productAttributes[{{ $attribute->id }}]"
                    data-id="{{ $attribute->id }}"
                    {{ key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? []) && getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id] == 'on' ? 'checked' : '' }}
                    {{ $attribute->validation['required'] == 1 ? 'required' : '' }} />
            @elseif($attribute->type == 'file')
                <input type="{{ $attribute->type }}" class="form-control productInputsAttributes"
                    name="productAttributes[{{ $attribute->id }}]" data-id="{{ $attribute->id }}"
                    onchange="readURL(this, 'imgUploadPreview-{{ $attribute->id }}', 'single');"
                    @if (!key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? [])) {{ $attribute->validation['required'] == 1 ? 'required' : '' }} @endif
                    value="" />
                @if (key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? []) &&
                    !is_null(getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id]))
                    <img src="{{ url(getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id]) }}"
                        id="imgUploadPreview-{{ $attribute->id }}" class="img-thumbnail img-responsive img-preview"
                        style="height: 150px; width: 250px;" alt="attribute image">
                @else
                    <img src="#" id="imgUploadPreview-{{ $attribute->id }}"
                        class="img-thumbnail img-responsive img-preview"
                        style="height: 150px; width: 250px; display: none;" alt="attribute image">
                @endif
            @else
                <input type="{{ $attribute->type }}" class="form-control productInputsAttributes"
                    name="productAttributes[{{ $attribute->id }}]" data-id="{{ $attribute->id }}" autocomplete="off"
                    {{ $attribute->validation['required'] == 1 ? 'required' : '' }}
                    {{ isset($attribute->validation['validate_max']) && $attribute->validation['validate_max'] == 1 ? 'maxlength=' . $attribute->validation['max'] ?? '' . '"' : '' }}
                    {{ isset($attribute->validation['validate_min']) && $attribute->validation['validate_min'] == 1 ? 'maxlength=' . $attribute->validation['min'] ?? '' . '"' : '' }}
                    value="{{ key_exists($attribute->id, getCartItemById($cartProductId)->attributes['productAttributes'] ?? []) ? getCartItemById($cartProductId)->attributes['productAttributes'][$attribute->id] : '' }}" />
            @endif
        </div>
    @endforeach
@endif
