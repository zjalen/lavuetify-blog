<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends BaseModel
{
    protected $fillable = ['title', 'parent_id', 'order','level','icon','url'];

    public function children() {
        return $this->hasMany(Menu::class,'parent_id','id');
    }
}
