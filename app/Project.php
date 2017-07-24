<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  public $incrementing = false;
  protected $primaryKey = 'prj_no';
}
