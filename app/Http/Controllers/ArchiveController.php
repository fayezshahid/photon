<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ArchiveController extends Controller
{
    public function index()
    {
        return view('archive');
    }

    public function addToArchive($id)
    {
        $image = Image::find($id);
        $image->isArchived = 1;
        $image->save();
    }

    public function removeFromArchive($id)
    {
        $image = Image::find($id);
        $image->isArchived = 0;
        $image->save();
    }

    public function arrangeArchivedImages($arrangeBy, $order)
    {
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return Image::where('isArchived', 1)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('created_at')->get();
            else
                return Image::where('isArchived', 1)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return Image::where('isArchived', 1)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('name')->get();
            else
                return Image::where('isArchived', 1)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('name', 'desc')->get();
        }
        else if($arrangeBy == 'Size')
        {
            if(!$order)
                return Image::where('isArchived', 1)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('size')->get();
            else
                return Image::where('isArchived', 1)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('size', 'desc')->get();
        }
    }

}
