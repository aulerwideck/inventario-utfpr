<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patrimony extends Model
{
    protected $table = 'patrimonies';

    protected $primaryKey = 'id';

    protected $attributes = [
        'tombo' => 0,
        'tombo_old' => 0,
        'description' => '',
        'collected' => false,
    ];

    protected $fillable = [
        'tombo',
        'tombo_old',
        'description',
        'collected',
        'inventory_id',
        'local_id',
    ];

    protected $hidden = [
        'id',
        'state_id',
        'responsible_id',
    ];

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public function local()
    {
        return $this->belongsTo('App\Local');
    }

    public function responsible()
    {
        return $this->belongsTo('App\Responsible');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }

    public function collects()
    {
        return $this->hasMany('App\Collect','patrimony_id');
    }



}
