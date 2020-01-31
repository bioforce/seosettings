<?php

namespace Bioforce\SeoSettings\Controllers;

use App\Http\Controllers\Controller;
use Bioforce\SeoSettings\Models\SeoSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        /*echo "<pre>";
        print_r(get_all_models());
        echo "</pre>";
*/
        $routes = \seo_routes_prepare();
        if($request->ajax()){
            if ($request->has('get'))
            {
                return response()->json($routes);
            }
            if ($request->has('models'))
            {
                return response()->json(get_all_models());
            }
            return response()->json($routes);
        }
        return view('seo_settings::seo_page', ['routes' => $routes]);
        return view('seo_settings::seo_page', ['routes' => \Route::getRoutes()]);
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
        $values = array_map(function($value) { return ['value' => $value]; }, $request->values);
        //return response()->json($values);
        $seoSetting = SeoSetting::create($request->except(['_token', 'values']));
        $seoSetting->tags()->sync($values);
        return response()->json($seoSetting);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SeoSetting  $seoSetting
     * @return \Illuminate\Http\Response
     */
    public function show(SeoSetting $seoSetting)
    {
        //
        return response()->json($seoSetting);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SeoSetting  $seoSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(SeoSetting $seoSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SeoSetting  $seoSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SeoSetting $seoSetting)
    {
        //
        $seoSetting->update($request->except(['_token', 'values']));
        $seoSetting->tags()->sync($this->prepareTags($request->values));
        return response()->json($seoSetting->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SeoSetting  $seoSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeoSetting $seoSetting)
    {
        //
        $seoSetting->tags()->detach();
        return response()->json($seoSetting->delete());
    }

    private function prepareTags($values)
    {
        return array_map(function($value) { return ['value' => $value]; }, $values);
    }
}
