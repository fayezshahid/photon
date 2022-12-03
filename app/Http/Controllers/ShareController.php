<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\User;
use App\Models\Image;

class ShareController extends Controller
{
    public function share($userId, $imageId)
    {
        $share = new Share();
        $share->image_id = $imageId;
        $share->owner_id = auth()->user()->id;
        $share->viewer_id = $userId;
        $share->save();

        return User::where('id', $userId)->pluck('email')->first();
    }

    public function unshare($userId, $imageId)
    {
        Share::where('image_id', $imageId)->where('owner_id', auth()->user()->id)->where('viewer_id', $userId)->delete();

        return User::where('id', $userId)->pluck('email')->first();
    }

    public function arrangeSharedImages($arrangeBy, $order)
    {
        $imageIds = Share::where('viewer_id', auth()->user()->id)->pluck('image_id')->toArray();
        if($arrangeBy == 'Date')
        {
            if(!$order)
                return Image::whereIn('id', $imageIds)->orderby('created_at')->get();
            else
                return Image::whereIn('id', $imageIds)->orderby('created_at', 'desc')->get();
        }
        else if($arrangeBy == 'A-Z')
        {
            if(!$order)
                return Image::whereIn('id', $imageIds)->orderby('name')->get();
            else
                return Image::whereIn('id', $imageIds)->orderby('name', 'desc')->get();
        }
        else if($arrangeBy == 'Size')
        {
            if(!$order)
                return Image::whereIn('id', $imageIds)->orderby('size')->get();
            else
                return Image::whereIn('id', $imageIds)->get();
        }
    }

    public function removeSharedImage($userId, $imageId)
    {
        Share::where('image_id', $imageId)->where('viewer_id', auth()->user()->id)->where('owner_id', $userId)->delete();
    }

    public function ifImageShared($userId, $imageId)
    {
        return Share::where('image_id', $imageId)->where('owner_id', auth()->user()->id)->where('viewer_id', $userId)->pluck('id')->first();
    }

}
