<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    protected $primaryKey = 'id';

    protected $attributes = [
        'value' => ''
    ];

    protected $fillable = [
        'id',
        'value',
    ];

    protected $hidden = [
    ];

    public function patrimonies()
    {
        return $this->hasMany('App\Patrimony');
    }

    public function collects()
    {
        return $this->hasMany('App\Collect');
    }
}
