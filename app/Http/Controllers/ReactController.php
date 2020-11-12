<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Message $message)
    {
        $profile = auth()->user()->profile->id;
        $type = request()->reaction;
        
        if($message->checkReaction($profile,$type)>0){ 
            return $message->reactions()->wherePivot('type','=',$type)->detach(
                $profile);
        }
        else{
            return $message->reactions()->attach(
                $profile, ['type' => $type]
            );
        } 
    }
}