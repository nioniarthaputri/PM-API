<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardMembers extends Model
{
    protected $fillable = ['id', 'card_id', 'user_id', 'adder_id', 'remover_id'];

    /*use SoftDeletes;
    protected $dates = ['deleted_at'];*/

    public function cards()
    {
        return $this->belongsTo(Cards::class);
    }
        public function user()
    {
        return $this->hasMany(User::class, 'id','user_id' );
    }
}
