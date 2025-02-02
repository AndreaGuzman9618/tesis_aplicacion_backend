<?php

namespace App\Models;

use CodeIgniter\Model;

class CitasModel extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_cita';
    protected $allowedFields = ['id_usuario', 'id_centro', 'id_especialidad', 'fecha_cita', 'hora_cita', 'motivo', 'id_estado', 'fecha_creacion'];
}
