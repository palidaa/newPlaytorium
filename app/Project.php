<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  protected $primaryKey = 'prj_no';
  public $incrementing = false;
  //public $timestamps = false;
}
