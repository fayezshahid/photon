<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    public function index()
    {
        return view('trash');
    }
    
    public function restore($id)
    {
        $image = Image::find($id);
        $image->inTrash = 0;
        $image->save();
    }

    public function delete($id)
    {
        $image = Image::find($id);
        $image->delete();
        Storage::disk('image')->delete($image->image);
    }

    public function arrangeTrashImages($arrangeBy, $order)
    {
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return Image::where('inTrash', 1)->where('user_id', auth()->user()->id)->orderby('created_at')->get();
            else
                return Image::where('inTrash', 1)->where('user_id', auth()->user()->id)->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return Image::where('inTrash', 1)->where('user_id', auth()->user()->id)->orderby('name')->get();
            else
                return Image::where('inTrash', 1)->where('user_id', auth()->user()->id)->orderby('name', 'desc')->get();
        }
        else if($arrangeBy == 'Size')
        {
            if(!$order)
                return Image::where('inTrash', 1)->where('user_id', auth()->user()->id)->orderby('size')->get();
            else
                return Image::where('inTrash', 1)->where('user_id', auth()->user()->id)->orderby('size', 'desc')->get();
        }
    }

    public function clear()
    {
        Image::where('inTrash', 1)->delete();
    }

}
