<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableLists extends Model
{
    protected $fillable = ['id', 'board_id', 'list_name', 'created_by', 'position'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function boards(){ 
        return $this->belongsTo(Boards::class, 'board_id', 'id'); 
    }
    public function cards()
    {
        return $this->hasMany(Cards::class, 'list_id', 'id');
    }
}
