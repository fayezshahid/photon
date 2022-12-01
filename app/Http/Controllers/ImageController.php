<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Archive;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max::255',
            'image' => 'required'
        ]);

        $image = Storage::disk('image')->putFile('', $request->image);
        $data['image'] = $image;
        $data['user_id'] = auth()->user()->id;

        Image::create($data);
    }

    public function update(Request $request, $imageId)
    {
        if($request->hiddenToken == 1)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255',
            ]);
        }
        else
        {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required',
            ]);

            $image = Image::find($imageId);
            Storage::disk('image')->delete($image->image);

            $image = Storage::disk('image')->putFile('', $request->image);
            $data['image'] = $image;
        }
        
        $image = Image::find($imageId);
        $image->update($data);
    }

    public function addToTrash($imageId)
    {
        $image = Image::find($imageId);
        $image->inTrash = 1;
        $image->save();
    }

    function arrangeImages($arrangeBy, $order)
    {
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return Image::where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('created_at')->get();
            else
                return Image::where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return Image::where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('name')->get();
            else
                return Image::where('isArchived', 0)->where('inTrash', 0)->where('user_id', auth()->user()->id)->orderby('name', 'desc')->get();
        }
    }

}
