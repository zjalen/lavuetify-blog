<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends BaseModel
{
    protected $fillable = ['user_id', 'article_id', 'belong','parent_id','level','content','is_checked'];

    protected $with = ['user', 'article', 'belong_comment:content', 'parent:content'];

    protected $appends = ['avatar'];

    public function getAvatarAttribute()
    {
        return $this->user ? $this->user->avatar : '';
    }


    public function article() {
        return $this->belongsTo(Article::class,'article_id','id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function belong_comment() {
        return $this->belongsTo(Comment::class,'belong','id');
    }

    public function parent() {
        return $this->belongsTo(Comment::class,'parent_id','id');
    }
}
