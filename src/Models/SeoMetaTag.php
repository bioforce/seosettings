<?php

namespace Bioforce\SeoSettings\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMetaTag extends Model
{
    //

    protected $fillable = [
        'name',
        'template',
        'group_id',
        'editable',
        'default_value',
        'sort',
    ];

    protected $appends = ['group_name'];

    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo('Bioforce\SeoSettings\Models\SeoTagGroup', 'group_id');
    }

    public function settings()
    {
        return $this->belongsToMany('Bioforce\SeoSettings\Models\SeoSetting', 'seo_meta_tag_values')->withPivot('value');
    }

    public function getGroupNameAttribute()
    {
        return $this->group->name;
    }
}
