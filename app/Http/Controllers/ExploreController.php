<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ExploreController extends Controller
{
    public function getImageByName($name)
    {
        return Image::where('user_id', auth()->user()->id)->where('isArchived', 0)->where('inTrash', 0)->where(function($query) use ($name){
            $query->where('name', 'like', '%'.$name)->orWhere('name', 'like', '%'.$name.'%')->orWhere('name', 'like', $name.'%');
        })->orderby('created_at', 'desc')->get();
    }

    public function getImageByDate($date1, $date2)
    {
        return Image::where('user_id', auth()->user()->id)->where('isArchived', 0)->where('inTrash', 0)
        ->whereBetween('created_at', [$date1, $date2])->orderby('created_at', 'desc')->get();
    }

}
