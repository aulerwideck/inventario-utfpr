<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    protected $table = 'responsibles';

    protected $primaryKey = 'id';

    protected $attributes = [
        'value' => ''
    ];

    protected $fillable = [
        'id',
        'value',
    ];

    protected $hidden = [
        'patrimony_id',
        'inventory_id',
    ];

    public function patrimonies()
    {
        return $this->hasMany('App\Patrimony');
    }

    public function collects()
    {
        return $this->hasMany('App\Collect');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }

}
