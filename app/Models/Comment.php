<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends BaseModel
{
    protected $fillable = ['user_id', 'article_id', 'belong','parent_id','level','content','is_checked'];

    public function article() {
        return $this->belongsTo(Article::class,'article_id','id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
