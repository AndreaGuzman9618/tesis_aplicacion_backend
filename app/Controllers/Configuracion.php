<?php
namespace App\Controllers;
use App\Models\ConfiguracionModel;
use CodeIgniter\RESTful\ResourceController;

class Configuracion extends ResourceController
{
    public function obtenerClaveAPI()
    {
        $model = new ConfiguracionModel();
        $clave = $model->where('clave', 'google_maps_api_key')->first();

        if (!$clave) {
            return $this->failNotFound("Clave de API no encontrada");
        }

        return $this->respond([
            'status' => 200,
            'api_key' => $clave['valor']
        ]);
    }
}
