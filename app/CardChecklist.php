<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardChecklist extends Model
{
    protected $fillable = ['id', 'card_id', 'title', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
         public function checklist_detail()
    {
        return $this->hasMany(CardChecklistDetail::class, 'checklist_id', 'id');
    }

        public function cards()
    {
        return $this->belongsTo(Cards::class, 'card_id', 'id');
    }
    
}
