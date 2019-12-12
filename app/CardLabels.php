<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardLabels extends Model
{
    protected $fillable = ['id', 'card_id', 'label', 'label_color', 'added_by', 'removed_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
        public function cards()
    {
        return $this->belongsTo(Cards::class);
    }
}
