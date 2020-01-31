<?php
Route::group([
        'middleware' => \Illuminate\Support\Arr::wrap(config('seo-settings.middleware')),
        'namespace' => '\Bioforce\SeoSettings\Controllers',
        'as' => 'seo.',
    ], function () {
    Route::group(['prefix' => config('seo-settings.path').'/seo'], function () {
        Route::get('/', 'SeoSettingController@index')->name('index');

        Route::resource('settings', 'SeoSettingController')->parameters([
            'settings' => 'seoSetting'
        ]);
        Route::resource('tags', 'SeoMetaTagController')->parameters([
            'tags' => 'seoMetaTag'
        ]);
        Route::resource('groups', 'SeoTagGroupController')->parameters([
            'groups' => 'seoTagGroup'
        ]);
    });
});
