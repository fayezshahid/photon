<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album_Image extends Model
{
    use HasFactory;

    protected $table = 'album_image';

    protected $fillable = [
        'image_id',
        'album_id'
    ];

}
