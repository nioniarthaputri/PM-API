<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boards extends Model
{
    protected $fillable = ['id', 'team_id', 'board_name', 'board_background', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function teams(){ 
        return $this->belongsTo(Teams::class); 
    }

    public function boardsTableLists()
    {
        return $this->hasManyThrough(
            'App\Cards',
            'App\TableLists',
            'boards_id', // Foreign key on users table...
            'table_lists_id', // Foreign key on history table...
            'id', // Local key on suppliers table...
            'id' // Local key on users table...
        );
    }

    public function tableListsCard()
    {
        return $this->hasManyThrough('App\Cards', 'App\TableLists');
    }
    public function tablelist(){ 
        return $this->hasMany(TableLists::class,'board_id','id'); 
    }
    
}
