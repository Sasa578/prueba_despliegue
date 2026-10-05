<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'fecha_pago',
        'monto_total',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto_total' => 'decimal:2',
    ];

    public const ESTADOS = [
        'pagado' => 'Pagado',
        'pendiente' => 'Pendiente',
        'vencido' => 'Vencido',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'payment_person')
            ->withPivot('monto_aportado')
            ->withTimestamps();
    }

    /**
     * Suma de los aportes registrados por los participantes.
     */
    public function totalAportado(): float
    {
        return (float) $this->people->sum('pivot.monto_aportado');
    }
}
