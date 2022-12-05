<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Album_Image;

class AlbumController extends Controller
{
    public function index()
    {
        return view('album');
    }

    public function create(Request $request)
    {
        $album = new Album();
        $album->name = $request->name;
        $album->user_id = auth()->user()->id;
        $album->save();
    }

    public function update(Request $request, $id)
    {
        $album = Album::find($id);
        $album->update(['name' => $request->name]); 
    }

    public function delete($id)
    {
        $album = Album::find($id);
        $album->delete();
    }

    public function arrangeAlbums($arrangeBy, $order)
    {
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return auth()->user()->albums()->orderby('created_at')->get();
            else
                return auth()->user()->albums()->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return auth()->user()->albums()->orderby('name')->get();
            else
                return auth()->user()->albums()->orderby('name', 'desc')->get();
        }
        else if($arrangeBy == 'Size')
        {
            if(!$order)
                return auth()->user()->albums()->orderby('size')->get();
            else
                return auth()->user()->albums()->orderby('size', 'desc')->get();
        }
    }

    public function getAlbumImages($albumId, $arrangeBy, $order)
    {
        $album = Album::find($albumId);
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return $album->images()->where('isArchived', 0)->where('inTrash', 0)->orderby('created_at')->get();
            else
                return $album->images()->where('isArchived', 0)->where('inTrash', 0)->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return $album->images()->where('isArchived', 0)->where('inTrash', 0)->orderby('name')->get();
            else
                return $album->images()->where('isArchived', 0)->where('inTrash', 0)->orderby('name', 'desc')->get();
        }
        else if($arrangeBy == 'Size')
        {
            if(!$order)
                return $album->images()->where('isArchived', 0)->where('inTrash', 0)->orderby('size')->get();
            else
                return $album->images()->where('isArchived', 0)->where('inTrash', 0)->orderby('size', 'desc')->get();
        }
    }

    public function getAlbums()
    {
        return auth()->user()->albums()->orderby('created_at', 'desc')->get();
    }

    public function addToAlbum($albumId, $imageId)
    {
        $album_image = new Album_Image();
        $album_image->album_id = $albumId;
        $album_image->image_id = $imageId;
        $album_image->save();
        $album = Album::find($albumId);
        $album->update([
            'size' => $album->images()->sum('size'),
        ]);
        return $album->name;
    }

    public function removeFromAlbum($albumId, $imageId)
    {
        Album_Image::where('album_id', $albumId)->where('image_id', $imageId)->delete();
        $album = Album::find($albumId);
        $album->update([
            'size' => $album->images()->sum('size'),
        ]);
        return $album->name;
    }

    public function getAlbumName($name)
    {
        return Album::where('user_id', auth()->user()->id)->where(function($query) use ($name){
            $query->where('name', 'like', '%'.$name)->orWhere('name', 'like', '%'.$name.'%')->orWhere('name', 'like', $name.'%');
        })->get();
    }

    public function ifInAlbum($albumId, $imageId)
    {
        return Album_Image::where('album_id', $albumId)->where('image_id', $imageId)->pluck('id')->first();
    }

}
