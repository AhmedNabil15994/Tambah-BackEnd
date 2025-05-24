

{!! field()->text('classes[container]', 'Container Class', isset($model->classes['container']) ? $model->classes['container'] : null, ['data-name' => 'classes.container']) !!}
{!! field()->text('classes[title]', 'Title Class', isset($model->classes['title']) ? $model->classes['title'] : null, ['data-name' => 'classes.title']) !!}
{!! field()->text('classes[cards]', 'Cards Class', isset($model->classes['cards']) ? $model->classes['cards'] : null, ['data-name' => 'classes.cards']) !!}