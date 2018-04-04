<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $table = 'reset_password';

    public function user () {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
