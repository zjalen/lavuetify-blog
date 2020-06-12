<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    protected $fillable = ['name', 'parent_id', 'order','level','show_as_menu'];

    protected $with = ['children'];

    public function children() {
        return $this->hasMany(Category::class,'parent_id','id');
    }

    public function articles() {
        return $this->hasMany(Article::class,'category_id','id');
    }
}
