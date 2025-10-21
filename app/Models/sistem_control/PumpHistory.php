<?php

namespace App\Models\sistem_control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PumpHistory extends Model
{
  use Notifiable;

  const CREATED_AT = 'start_time';
  const UPDATED_AT = 'end_time';

  protected $guarded = ['id'];

  protected $fillable = [
    'pump_name',
    'triggered_by',
    'start_time',
    'end_time',
    'duration_in_seconds',
  ];
}
