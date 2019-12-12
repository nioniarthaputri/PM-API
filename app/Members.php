<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    protected $fillable = ['id', 'team_id', 'user_id', 'created_by'];

    //use SoftDeletes;
    //protected $dates = ['deleted_at'];

    public function teams()
    {
        return $this->belongsTo(Teams::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
