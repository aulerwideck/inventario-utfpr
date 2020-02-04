<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collect extends Model
{
    protected $table = 'collects';

    protected $primaryKey = 'id';

    protected $attributes = [
        'tombo' => 0,
        'tombo_old' => 0,
        'description' => '',
        'observation' => ''
    ];

    protected $fillable = [
        'id',
        'tombo',
        'tombo_old',
        'description',
        'observation',
        'local_id',
        'state_id',
        'user_id',
    ];

    protected $hidden = [
        'responsible_id',
        'inventory_id',
        'patrimony_id',
    ];

    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }

    public function patrimony()
    {
        return $this->belongsTo('App\Patrimony');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function responsible()
    {
        return $this->belongsTo('App\Responsible');
    }

    public function local()
    {
        return $this->belongsTo('App\Local', 'local_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }
}
