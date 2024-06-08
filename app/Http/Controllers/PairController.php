<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pair;

class PairController extends Controller
{
    public function index()
    {
        return view('sharing');
    }

    public function getUsers()
    {
        $list1 = Pair::where('sender_id', auth()->user()->id)->where('isAccepted', 1)->pluck('receiver_id')->toArray();
        $list2a = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 1)->pluck('sender_id')->toArray();
        $list2b = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 0)->pluck('sender_id')->toArray();
        $list3 = array_merge($list1, $list2a, $list2b);
        array_push($list3, auth()->user()->id);
        return User::whereNotIn('id', $list3)->get();
    }

    public function getFriends()
    {
        $list1 = Pair::where('sender_id', auth()->user()->id)->where('isAccepted', 1)->pluck('receiver_id')->toArray();
        $list2a = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 1)->pluck('sender_id')->toArray();
        $list4 = array_merge($list1, $list2a);
        return User::whereIn('id', $list4)->get();
    }

    public function getRequests()
    {
        $list5 = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 0)->pluck('sender_id')->toArray();
        return User::whereIn('id', $list5)->get();
    }

    public function getRequestsSent()
    {
        return Pair::where('sender_id', auth()->user()->id)->where('isAccepted', 0)->pluck('receiver_id')->toArray();
    }

    public function getEmail($email, $mode)
    {
        $query = User::query()->where(function($query) use ($email) {
            $query->where('email', 'LIKE', '%'.$email)
                  ->orWhere('email', 'LIKE', '%'.$email.'%')
                  ->orWhere('email', 'LIKE', $email.'%');
        });
        
        if($mode == 3)
            return $query->get();  

        $list1 = Pair::where('sender_id', auth()->user()->id)->where('isAccepted', 1)->pluck('receiver_id')->toArray();
        $list2a = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 1)->pluck('sender_id')->toArray();
        $list2b = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 0)->pluck('sender_id')->toArray();
        $list3 = array_merge($list1, $list2a, $list2b);
        $list4 = array_merge($list1, $list2a);
        array_push($list3, auth()->user()->id);
        $list5 = Pair::where('receiver_id', auth()->user()->id)->where('isAccepted', 0)->pluck('sender_id')->toArray();

        if($mode == 0)
            $query->whereNotIn('id', $list3);
        if($mode == 1)
            $query->whereIn('id', $list4);    
        if($mode == 2)
            $query->whereIn('id', $list5);

        return $query->get();
    }

    public function sendRequest($id)
    {
        $pair = new Pair();
        $pair->sender_id = auth()->user()->id;
        $pair->receiver_id = $id;
        $pair->isAccepted = 0;
        $pair->save();
    }

    public function deleteRequest($id)
    {
        Pair::where('sender_id', auth()->user()->id)->where('receiver_id', $id)->delete();
    }

    public function acceptRequest($id)
    {
        $friend = Pair::where('receiver_id', auth()->user()->id)->where('sender_id', $id);
        $friend->update(['isAccepted' => 1]);
    }

    public function rejectRequest($id)
    {
        Pair::where('receiver_id', auth()->user()->id)->where('sender_id', $id)->delete();
    }

    public function removeFriend($id)
    {
        Pair::where('receiver_id', auth()->user()->id)->where('sender_id', $id)->orWhere('sender_id', auth()->user()->id)->where('receiver_id', $id)->delete();
    }
}
