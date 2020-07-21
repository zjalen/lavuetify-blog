<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends BaseModel
{
    protected $fillable = ['user_id', 'article_id', 'belong','parent_id','level','content','is_checked'];

    protected $with = ['belong_comment:content', 'parent'];

    protected $appends = ['avatar', 'nickname'];

    public function getAvatarAttribute()
    {
        return $this->user ? $this->user->avatar : '';
    }

    public function getNicknameAttribute()
    {
        return $this->user ? $this->user->nickname : '';
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
