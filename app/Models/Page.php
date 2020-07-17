<?php

namespace App\Models;

class Page extends BaseModel
{
    protected $fillable = ['title','name','url','content_md','content_html'];

    protected $table = 'pages';
}
