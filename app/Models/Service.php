<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'costo_mensual',
        'dia_vencimiento',
        'activo',
    ];

    protected $casts = [
        'costo_mensual' => 'decimal:2',
        'dia_vencimiento' => 'integer',
        'activo' => 'boolean',
    ];

    public const CATEGORIAS = [
        'servicios_basicos' => 'Servicios básicos',
        'internet_tv' => 'Internet y TV',
        'streaming' => 'Streaming',
        'otros' => 'Otros',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Etiqueta legible de la categoría (p. ej. "Servicios básicos").
     */
    public function getCategoriaLabelAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? 'Otros';
    }
}
