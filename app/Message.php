<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Message extends Model
{
    protected $guarded = [];

    public function checkReaction($profile, $type)
    {

        $count = count(
            $this->reactions()->where(
                [['profile_id', '=', $profile], ['type', '=', $type]]
            )->get()
        );


        return $count;
    }

    public function setReactions($value)
    {
        $this->type = array_merge($this->type, [$value]);
    }

    public function reactions()
    {
        return $this->belongsToMany(Profile::class)->withPivot('type');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
