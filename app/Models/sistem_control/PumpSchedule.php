<?php

namespace App\Models\sistem_control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PumpSchedule extends Model
{
  use Notifiable;

  protected $fillable = [
    'pump_name',
    'start_time',
    'duration_minutes',
    'days',
    'status',
  ];
  protected $casts = ['days' => 'array'];

}
