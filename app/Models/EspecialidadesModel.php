<?php

namespace App\Models;

use CodeIgniter\Model;

class EspecialidadesModel extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'id_especialidad';
    protected $allowedFields = ['nombre'];
}
