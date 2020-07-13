<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Topic extends BaseModel
{
    protected $fillable = ['name', 'cover'];
    protected $appends = ['cover_full_path'];

    public function getCoverFullPathAttribute()
    {
        $cover = explode('storage/', $this->cover);
        $path = count($cover) > 1 ? $cover[1] : $cover[0];
        return Storage::disk('public')->url($path);
    }
}
