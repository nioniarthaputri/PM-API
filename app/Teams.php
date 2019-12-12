<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $fillable = ['id', 'team_name', 'team_description', 'created_by'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function boards()
    {
        return $this->hasMany(Boards::class);
    }
    public function members()
    {
        return $this->hasMany(Members::class);
    }
}
