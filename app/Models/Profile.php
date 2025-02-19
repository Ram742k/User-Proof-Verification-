<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'id_proof', 'address_proof', 'status'];
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
