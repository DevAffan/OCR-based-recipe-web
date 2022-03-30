<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [] ;


    public function getImageAttribute(){
        return Storage::url($this->attributes['image']);
    }
}
