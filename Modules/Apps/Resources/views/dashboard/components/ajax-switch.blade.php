{!! field('search')->checkBox('switch','',null,[
 'onchange' => 'toggleBoolean(this , "'.$url.'")',
 'checked' => ($model->$switch == $open ? 'checked' : null),
]) !!}