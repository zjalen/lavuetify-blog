<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends BaseModel
{
    protected $fillable = ['title','name','url','content_md','content_html'];

    protected $table = 'pages';
}
