<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends BaseModel
{
    protected $fillable = ['name'];

    public function articles() {
        return $this->belongsToMany(Article::class,'article_tags','tag_id','article_id');
    }

    protected $appends = ['articles_count'];


    public function getArticlesCountAttribute()
    {
        return $this->articles()->count();
    }

}
