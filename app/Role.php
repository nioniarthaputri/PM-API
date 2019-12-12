<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $fillable = ['id', 'nama'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
