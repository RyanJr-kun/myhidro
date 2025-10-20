<?php

namespace App\Models\sistem_control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pump extends Model
{
  use Notifiable;
  protected $guarded = ['id'];
}
