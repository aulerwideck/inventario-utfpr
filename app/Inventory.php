<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $primaryKey = 'id';

    protected $attributes = [
        'year' => 0,
        'filename' => '',
        'finished' => false,
        'final_prevision' => '01-01-0001',
        'description' => '',
        'observation' => '',
        'final_filename' => '',
    ];

    protected $fillable = [
        'id',
        'year',
        'filename',
        'finished',
        'final_prevision',
        'description',
        'observation',
        'final_filename',
    ];

    protected $hidden = [
    ];

    public function locals()
    {
        return $this->hasMany('App\Local');
    }

    public function patrimonies()
    {
        return $this->hasMany('App\Patrimony');
    }

    public function collects()
    {
        return $this->hasMany('App\Collect');
    }

    public function responsibles()
    {
        return $this->hasMany('App\Responsible');
    }

}
