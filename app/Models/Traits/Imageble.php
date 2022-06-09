<?php

namespace App\Models\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Imageble
{
    public function gallery()
    {
        return $this->morphMany(Image::class, "imageble");
    }
}
