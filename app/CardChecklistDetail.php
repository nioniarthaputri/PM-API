<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardChecklistDetail extends Model
{
    protected $fillable = ['id', 'checklist_id', 'list', 'checked', 'checked_by', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

        public function card_checklist()
    {
        return $this->belongsTo(CardChecklist::class);
    }

}
