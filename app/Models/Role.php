<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use Notifiable;
    protected $guarded = ['id'];

    public function users(): HasMany
    {
      return $this->hasMany(User::class);
    }
}
