<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
  public $timestamps = false;

  protected $fillable = [
      'id', 'prj_no', 'date', 'task_name', 'description', 'time_in', 'time_out'
  ];
}
