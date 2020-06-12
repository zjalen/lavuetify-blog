<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Article extends BaseModel
{
    use SoftDeletes;
    protected $fillable = ['title','description','cover','category_id','topic_id','author_id','is_top','is_draft','content_md','content_html','created_at'];

    protected $table = 'articles';

    protected $appends = ['cover_full_path'];

    protected $with = ['category', 'topic', 'tags:name'];

    public function getCoverFullPathAttribute()
    {
        $cover = explode('storage/', $this->cover);
        $path = count($cover) > 0 ? $cover[1] : $this->cover;
        return Storage::disk('public')->url($path);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class,'topic_id','id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'article_tags','article_id','tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id','id');
    }
}
