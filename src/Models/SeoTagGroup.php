<?php

namespace Bioforce\SeoSettings\Models;

use Illuminate\Database\Eloquent\Model;

class SeoTagGroup extends Model
{
    //

    protected $fillable = [
        'name',
        'sort'
    ];

    public $timestamps = false;

    public function tags()
    {
        return $this->hasMany('Bioforce\SeoSettings\Models\SeoMetaTag', 'group_id');
    }
}
