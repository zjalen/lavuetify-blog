<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends BaseModel
{
    protected $fillable = ['name','description', 'order', 'url'];
}
