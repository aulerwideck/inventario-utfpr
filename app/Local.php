<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $table = 'locals';

    protected $primaryKey = 'id';

    protected $attributes = [
        'value' => ''
    ];

    protected $fillable = [
        'id',
        'value'
    ];

    protected $hidden = [
        'inventory_id'
    ];

    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }

    public function patrimonies()
    {
        return $this->hasMany('App\Patrimony');
    }

    public function collects()
    {
        return $this->hasMany('App\Collect', 'local_id');
    }
}
