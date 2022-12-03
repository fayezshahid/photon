<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class FavouriteController extends Controller
{
    public function index()
    {
        return view('favourite');
    }

    public function addToFavourite($id)
    {
        $image = Image::find($id);
        $image->isFavourite = 1;
        $image->save();
    }

    public function removeFromFavourite($id)
    {
        $image = Image::find($id);
        $image->isFavourite = 0;
        $image->save();
    }

    public function arrangeFavouriteImages($arrangeBy, $order)
    {
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return Image::where('isFavourite', 1)->where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('created_at')->get();
            else
                return Image::where('isFavourite', 1)->where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return Image::where('isFavourite', 1)->where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('name')->get();
            else
                return Image::where('isFavourite', 1)->where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('name', 'desc')->get();
        }
        else if($arrangeBy == 'Size')
        {
            if(!$order)
                return Image::where('isFavourite', 1)->where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('size')->get();
            else
                return Image::where('isFavourite', 1)->where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('size', 'desc')->get();
        }
    }
}
