<?php
namespace App\Models;
use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table = 'configuracion';
    protected $primaryKey = 'id';
    protected $allowedFields = ['clave', 'valor'];
}
