
@permission('delete_all')
<div style="margin-top: 25px;">
    {!! field('delete_switch')->checkBox('show_all_deleted', __('apps::dashboard.datatable.show_all_deleted'),null, ['id' => 'showAllDeleted']) !!}
</div>
@endpermission