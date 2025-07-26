<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaDisponible extends Model
{
    use HasFactory;

    protected $table = 'citas_disponibles';
    protected $fillable = ['especialidad', 'hora', 'lugar', 'fecha']; // Campos asignables
}
