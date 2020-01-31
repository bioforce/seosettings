<?php

namespace Bioforce\SeoSettings\Controllers;

use App\Http\Controllers\Controller;
use Bioforce\SeoSettings\Models\SeoMetaTag;
use Bioforce\SeoSettings\Models\SeoTagGroup;
use Illuminate\Http\Request;

class SeoMetaTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()){
            return response()->json(SeoMetaTag::with('group')->paginate(20));
        }
        return view('seo_settings::pages.tag');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required'],
            'template' => ['required'],
            'sort' => ['required', 'numeric'],
            'editable' => ['boolean'],
            'group_id' => ['required', 'exists:seo_tag_groups,id'],
        ]);
        return response()->json(SeoMetaTag::create($request->except(['_token'])));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SeoMetaTag  $seoMetaTag
     * @return \Illuminate\Http\Response
     */
    public function show(SeoMetaTag $seoMetaTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SeoMetaTag  $seoMetaTag
     * @return \Illuminate\Http\Response
     */
    public function edit(SeoMetaTag $seoMetaTag)
    {
        //
        return response()->json($seoMetaTag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SeoMetaTag  $seoMetaTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SeoMetaTag $seoMetaTag)
    {
        //
        $request->validate([
            'name' => ['required'],
            'template' => ['required'],
            'sort' => ['required', 'numeric'],
            'editable' => ['boolean'],
            'group_id' => ['required', 'exists:seo_tag_groups,id'],
        ]);

        return response()->json($seoMetaTag->update($request->except(['_token', 'id', 'group_name', 'group'])));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SeoMetaTag  $seoMetaTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeoMetaTag $seoMetaTag)
    {
        //
    }
}
