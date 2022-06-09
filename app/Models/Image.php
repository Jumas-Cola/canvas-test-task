<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ["path"];

    protected static function booted()
    {
        static::addGlobalScope("order", function (Builder $builder) {
            $builder->orderBy("id", "desc");
        });
    }
}
