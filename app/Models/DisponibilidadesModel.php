<?php

namespace App\Models;

use CodeIgniter\Model;

class DisponibilidadesModel extends Model
{
    protected $table = 'disponibilidades';
    protected $primaryKey = 'id_disponibilidad';
    protected $allowedFields = ['id_centro', 'id_especialidad', 'fecha', 'hora_inicio', 'hora_fin', 'ocupado'];
}

