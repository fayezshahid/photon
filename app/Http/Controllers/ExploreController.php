<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ExploreController extends Controller
{
    public function getImageByName($name)
    {
        return Image::where('user_id', auth()->user()->id)->where('name', 'like', '%'.$name)
        ->orWhere('name', 'like', '%'.$name.'%')->where('user_id', auth()->user()->id)
        ->orWhere('name', 'like', $name.'%')->get()->where('user_id', auth()->user()->id);
    }

    public function getImageByDate($date1, $date2)
    {
        return Image::where('user_id', auth()->user()->id)->whereBetween('created_at', [$date1, $date2])->get();
    }

}
