<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];
    public function profileImage()
    {
        return $this->picture ? '/storage/' . $this->picture : '/svg/user.svg';
    }

    public function reacts()
    {
        return $this->belongsToMany(Message::class)->withPivot('type');
    }


    public function followers()
    {
        return $this->belongsToMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
