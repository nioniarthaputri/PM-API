<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryCard extends Model
{
    protected $fillable = ['id', 'card_id', 'from_list', 'to_list', 'since', 'until', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function biodata()
    {
    	return $this->belongsTo(Biodata::class,'created_by','user_id');
    }

    public function from_list()
    {
    	return $this->belongsTo(TableLists::class,'from_list','id');
    }

    public function to_list()
    {
    	return $this->belongsTo(TableLists::class,'to_list','id');
    }
}
