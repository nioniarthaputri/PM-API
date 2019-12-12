<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    //use softDelete;

    protected $fillable = ['id', 'user_id', 'name', 'username' ,'initial', 'email','bio','photo', 'phone', 'address'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}

