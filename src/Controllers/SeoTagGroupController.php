<?php

namespace Bioforce\SeoSettings\Controllers;

use App\Http\Controllers\Controller;
use Bioforce\SeoSettings\Models\SeoTagGroup;
use Illuminate\Http\Request;

class SeoTagGroupController extends Controller
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
            if ($request->has('all'))
            {
                return response()->json(SeoTagGroup::all());
            }
            if ($request->has('get'))
            {
                return response()->json(SeoTagGroup::with('tags')->get());
            }
            return response()->json(SeoTagGroup::paginate(4));
        }
        return view('seo_settings::pages.group');
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
            'name' => ['required', 'unique:seo_tag_groups'],
            'sort' => ['required', 'numeric'],
        ]);
        return response()->json(SeoTagGroup::create($request->except(['_token'])));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SeoTagGroup  $seoTagGroup
     * @return \Illuminate\Http\Response
     */
    public function show(SeoTagGroup $seoTagGroup)
    {
        //
        return response()->json($seoTagGroup);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SeoTagGroup  $seoTagGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(SeoTagGroup $seoTagGroup)
    {
        //
        return response()->json($seoTagGroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SeoTagGroup  $seoTagGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SeoTagGroup $seoTagGroup)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:seo_tag_groups'],
            'sort' => ['required', 'numeric'],
        ]);
        return response()->json($seoTagGroup->update($request->except(['_token'])));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SeoTagGroup  $seoTagGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeoTagGroup $seoTagGroup)
    {
        //
        return response()->json($seoTagGroup->delete());
    }
}
