<?php

namespace App\Models\sistem_control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PumpHistory extends Model
{
  use Notifiable;
  protected $guarded = ['id'];
}
