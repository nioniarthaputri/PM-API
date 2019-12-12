<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryProgress extends Model
{
    protected $fillable = ['id', 'user_id', 'card_id', 'from_list', 'to_list', 'since', 'until', 'estimated_time'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
