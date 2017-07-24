<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'holiday';
}
