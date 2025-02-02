<?php

namespace App\Models;

use CodeIgniter\Model;

class CentrosSaludModel extends Model
{
    protected $table = 'centros_salud';
    protected $primaryKey = 'id_centro';
    protected $allowedFields = ['nombre', 'direccion', 'latitud', 'longitud', 'telefono', 'fecha_creacion'];
}
