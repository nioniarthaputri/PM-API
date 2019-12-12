<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardComments extends Model
{
    protected $fillable = ['id', 'card_id', 'attachment', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
        public function cards()
    {
        return $this->belongsTo(Cards::class);
    }
            public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
