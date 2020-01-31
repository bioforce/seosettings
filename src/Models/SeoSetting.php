<?php

namespace Bioforce\SeoSettings\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    //

    protected $fillable = [
        'uri',
        'model'
    ];

    protected $appends = ['tags'];

    public $timestamps = false;

    public function tags()
    {
        return $this->belongsToMany('Bioforce\SeoSettings\Models\SeoMetaTag', 'seo_meta_tag_values')->withPivot('value');
    }

    public function getTagsAttribute()
    {
        return $this->tags()->get();
    }
}
