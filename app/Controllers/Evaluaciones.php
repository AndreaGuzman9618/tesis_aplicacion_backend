<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EvaluacionesModel;

class Evaluaciones extends ResourceController
{
    protected $modelName = 'App\Models\EvaluacionesModel';
    protected $format    = 'json';

    public function guardar()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['id_usuario']) || !isset($data['calificacion'])) {
            return $this->fail("Los datos enviados son inválidos", 400);
        }

        $nuevaEvaluacion = [
            'id_usuario'  => $data['id_usuario'],
            'calificacion' => $data['calificacion'],
            'comentario'  => isset($data['comentario']) ? $data['comentario'] : null,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        $this->model->insert($nuevaEvaluacion);

        return $this->respond([
            'status' => 200,
            'message' => 'Evaluación guardada con éxito'
        ]);
    }

    public function listar()
    {
        $evaluaciones = $this->model->findAll();

        return $this->respond([
            'status' => 200,
            'data' => $evaluaciones
        ]);
    }
}

