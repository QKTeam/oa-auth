<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    public function role () {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function reset_password () {
        return \App\Models\Role::find($this->id)->lastest('created_at')->first();
    }
}
