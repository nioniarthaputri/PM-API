<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Cards extends Model
{
    protected $fillable = [
                            'id', 
                            'list_id', 
                            'card_name', 
                            'description', 
                            'estimated_hour',
                            'due_date',
                            'created_by',
                            'position',
                            'created_at', 
                            'updated_at'
    ];

    public function card_checklist(){
        return $this->hasMany(CardChecklist::class, 'card_id', 'id');
    }

    public function card_attachment(){
        return $this->hasMany(CardAttachments::class, 'card_id', 'id');
    }

    public function table_list(){
        return $this->belongsTo(TableLists::class, 'list_id', 'id');
    }

    public function card_label(){
        return $this->hasMany(CardLabels::class, 'card_id', 'id');
    }

    public function card_comment(){
        return $this->hasMany(CardComments::class, 'card_id', 'id')->orderBy('created_at', 'desc');
    }

    public function card_member(){
        return $this->hasMany(CardMembers::class, 'card_id', 'id');
    }

    //tambah relation card history
    public function card_history(){
        return $this->hasMany(HistoryCard::class, 'card_id', 'id');
    }

    
    protected $dates = ['deleted_at'];
}
