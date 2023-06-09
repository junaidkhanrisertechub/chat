<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;
    public function members(){
        return $this->hasMany(ChatMember::Class, 'group_id','id');
    }
}
