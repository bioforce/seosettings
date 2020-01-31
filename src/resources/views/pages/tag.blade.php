@extends('seo_settings::layout')

@section('content')
@php
    $fillableArray = [
        ['code' => 'id', 'name' => 'ID', 'editable' => false],
        ['code' => 'name', 'name' => 'Name', 'editable' => true, 'type' => 'text'],
        ['code' => 'sort', 'name' => 'Sort', 'editable' => true, 'type' => 'text'],
        ['code' => 'template', 'name' => 'Template', 'editable' => true, 'type' => 'text'],
        ['code' => 'group_id', 'name' => 'Group', 'editable' => true, 'type' => 'model', 'modelListUrl' => route('seo.groups.index').'?all'],
        ['code' => 'editable', 'name' => 'Editable', 'editable' => true, 'type' => 'checkbox'],
        ['code' => 'default_value', 'name' => 'Default value', 'editable' => true, 'type' => 'text'],
    ];
    $tableColumnList = [
        ['code' => 'id', 'name' => 'ID'],
        ['code' => 'name', 'name' => 'Name'],
        ['code' => 'sort', 'name' => 'Sort'],
        ['code' => 'template', 'name' => 'Template'],
        ['code' => 'group_name', 'name' => 'Group'],
        ['code' => 'editable', 'name' => 'Editable'],
        ['code' => 'default_value', 'name' => 'Default value'],
    ];
@endphp
<items-page
    :model='@json("SeoTag")'
    :columns='@json($tableColumnList)'
    :fillable='@json($fillableArray)'
    :api-url='@json(route('seo.tags.index'))'
    :add-button-text='@json("Add tag")'
    :can-delete='@json(true)'>
</items-page>
@endsection
