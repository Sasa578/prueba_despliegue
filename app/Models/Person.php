<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
    ];

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_person')
            ->withPivot('monto_aportado')
            ->withTimestamps();
    }
}
