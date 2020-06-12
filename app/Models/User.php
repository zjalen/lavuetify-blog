<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends BaseModel
{
    // 允许模型批量赋值
    protected $fillable = ['type','nickname','avatar','openid','email','access_token','is_black','last_login_ip'];
    // 字段黑名单
    protected $guarded = [''];

    public function comments() {
        return $this->hasMany(Comment::class, 'user_id','id');
    }
}
