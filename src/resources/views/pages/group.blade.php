@extends('seo_settings::layout')

@section('content')
@php
    $fillableArray = [
        ['code' => 'id', 'name' => 'ID', 'editable' => false],
        ['code' => 'name', 'name' => 'Name', 'editable' => true, 'type' => 'text'],
        ['code' => 'sort', 'name' => 'Sort', 'editable' => true, 'type' => 'text'],
    ];
    $tableColumnList = [
        ['code' => 'id', 'name' => 'ID'],
        ['code' => 'name', 'name' => 'Name'],
        ['code' => 'sort', 'name' => 'Sort'],
    ]
@endphp
<items-page
    :model='@json("SeoTagGroup")'
    :columns='@json($tableColumnList)'
    :fillable='@json($fillableArray)'
    :api-url='@json(route('seo.groups.index'))'
    :add-button-text='@json("Add group")'
    :can-delete='@json(true)'>
</items-page>
@endsection
