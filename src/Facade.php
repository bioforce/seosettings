<?php namespace Bioforce\SeoSettings;

/**
 *
 * @see \Bioforce\SeoSettings\SeoMeta
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return SeoMeta::class;
    }
}
