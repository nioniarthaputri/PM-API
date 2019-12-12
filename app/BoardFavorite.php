<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardFavorite extends Model
{
    protected $fillable = ['id', 'user_id', 'board_id', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
