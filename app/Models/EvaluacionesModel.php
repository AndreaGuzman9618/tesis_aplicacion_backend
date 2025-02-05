<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionesModel extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id_evaluacion';
    protected $allowedFields = ['id_usuario', 'calificacion', 'comentario', 'fecha_creacion'];
}
