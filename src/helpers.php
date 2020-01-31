<?php


if (!function_exists('metatags')) {
    /**
     *
     * @return \Bioforce\SeoSettings\SeoMeta
     */
    function metatags()
    {
        return app(\Bioforce\SeoSettings\SeoMeta::class);
    }
}

if (!function_exists('meta_tags_array')) {
    /**
     * Return prepare array of routes
     * @return Array
     */
    function meta_tags_array()
    {
        return metatags()->getMetaTagsArray();
    }
}

if (!function_exists('seo_routes_prepare')) {
    /**
     * Return prepare array of routes
     * @return Array
     */
    function seo_routes_prepare()
    {
        return metatags()->getRoutes();
    }
}

if (!function_exists('get_all_models')) {
    /**
     * Return models with colums
     * @return Array
     */
    function get_all_models(){
        return metatags()->getModels();
    }
}
