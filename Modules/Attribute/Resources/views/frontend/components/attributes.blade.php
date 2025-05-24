
@if(!$children->count())

    @if($single)

        @php $attribute = $attrs instanceof Modules\Attribute\Entities\Attribute ?$attrs : $attrs->attribute; @endphp
        @include('attribute::frontend.components.single-attr',compact('attribute','customData'))
    @else
        @foreach($attrs as $attribute)
            @php $attribute = $attribute instanceof Modules\Attribute\Entities\Attribute ? $attribute : $attribute->attribute; @endphp
            @include('attribute::frontend.components.single-attr',compact('attribute','customData'))
        @endforeach
    @endif
@else
        @php 
            $appendedChildrenIds = [];
            $appendedChildren = [];
             $rand = rand(1111111,99999999);
        @endphp
        <div id ="appVue{{$rand}}">

            @if($single)

                @php $attribute = $attrs instanceof Modules\Attribute\Entities\Attribute ?$attrs : $attrs->attribute; @endphp
                @include('attribute::frontend.components.single-attr',compact('attribute','customData'))
            @else
                @foreach($attrs as $attribute)
                    @php $attribute = $attribute instanceof Modules\Attribute\Entities\Attribute ? $attribute : $attribute->attribute; @endphp
                    @include('attribute::frontend.components.single-attr',compact('attribute','customData'))
                @endforeach
            @endif
            @if(count($children))
                @foreach($children as $child)
                    @if(!in_array($child->attribute_id,$appendedChildrenIds))
                        <div v-if="childshowing.find((attr) => attr.id == '{{optional($child->attribute)->id}}')">
                            <x-attributes-inputs type="" inputTheme="address_no_label" :attrs="$child" :data="$customData" single="true"/>
                        </div>
                        @php 
                            array_push($appendedChildrenIds , $child->attribute_id) ;
                            array_push($appendedChildren , $child->attribute) ;
                        @endphp
                    @endif
                @endforeach
            @endif
        </div>
@push('scripts')

    <script>
    let children = @json($children);
    let childrenAttributes = @json($appendedChildren);
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                children: children,
                childrenAttributes: childrenAttributes,
                childshowing:[],
            }
        },
        methods:{
            refreshChildrenAttributesShowing(){
                
                this.childrenAttributes.map((child) => {
                    let id = child.id;
                    let response = this.checkShowingAttr(id);

                    if(response){
                        if(!this.childshowing.find((attr) => attr.id == id)){
                            this.childshowing.push({
                                id : id
                            });
                        }
                    }else{
                        this.removeelement(this.childshowing,id)
                    }
                })
            },
            checkShowingAttr(childID){
                let response = false;
                let childAttribute = this.childrenAttributes.find((attr) => attr.id == childID);

                if(childAttribute){
                    let successAction = childAttribute.json_data.hasOwnProperty('show') && childAttribute.json_data.show == 1 ? true : false;
                    let currentAction = !successAction;
                    let boolFlag = false;

                    response = currentAction;
                    
                    if(childAttribute.json_data.hasOwnProperty('case')){
                        
                        switch(childAttribute.json_data.case){
                            case 'any':
                                boolFlag = this.childAttrCondationing(childID,'or');
                                break;
                            case 'all':
                                boolFlag = this.childAttrCondationing(childID,'and');
                                break;
                        }

                        return boolFlag ? successAction : currentAction;
                    }
                }

                return response;
            },

            childAttrCondationing(childID,operitor){

                let boolFlag = null;

                this.children.filter((child) => {

                    return child.attribute.id == childID
                }).map((child) => {
                    
                    if(child.json_data.hasOwnProperty('action') && child.json_data.hasOwnProperty('option') ){
                        
                        let catalogVal = null;
                        switch(child.catalogable.type){
                            case "drop_down":
                                catalogVal = $(`select[name='attributes[${child.catalogable_id}]']`).val();
                                break;
                            case "radio":
                            case "checkbox":
                                catalogVal = $(`input[name='attributes[${child.catalogable_id}]']:checked`).val();
                                break;
                            default:
                                catalogVal = $(`input[name='attributes[${child.catalogable_id}]']`).val();
                                break;
                        }
                        
                        switch(child.json_data.action){
                            case 'is':
                                
                                if(catalogVal && catalogVal == child.json_data.option){
                                    if(operitor == 'and'){
                                        boolFlag = (boolFlag == null ? true : boolFlag) && true;
                                    }else{
                                        boolFlag = boolFlag || true;
                                    }
                                }else{
                                    if(operitor == 'and'){
                                        boolFlag = (boolFlag == null ? true : boolFlag) && false;
                                    }
                                }

                                break;
                            case 'is_not':
                                
                                if(catalogVal && catalogVal != child.json_data.option){
                                    if(operitor == 'and'){
                                        boolFlag = (boolFlag == null ? true : boolFlag) && true;
                                    }else{
                                        boolFlag = boolFlag || true;
                                    }
                                }else{
                                    if(operitor == 'and'){
                                        boolFlag = (boolFlag == null ? true : boolFlag) && false;
                                    }
                                }
                                break;
                        }
                    }
                });
                return boolFlag;
            },
            removeelement(array,id) {
                let removingElement = array.map((rule) => rule.id).indexOf(id);
                if (removingElement || removingElement == 0) {
                    array.splice(removingElement, 1);
                    return true;
                }

                return false;
            },
        },
        mounted(){
            this.refreshChildrenAttributesShowing()
        },
    }).mount('#appVue{{$rand}}')
    </script>
@endpush
@endif