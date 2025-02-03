<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $allowedFields = [
        'nombre',
        'cedula',
        'email',
        'password',
        'telefono',
        'direccion',
        'id_rol',
        'coordenadas_lat',
        'coordenadas_lon'
    ];
    protected $useTimestamps = true; // Manejo de created_at y updated_at
}
