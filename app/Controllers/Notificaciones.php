<?php

namespace App\Controllers;
use App\Models\NotificacionesModel;
use CodeIgniter\RESTful\ResourceController;

class Notificaciones extends ResourceController
{
    protected $modelName = 'App\Models\NotificacionesModel';
    protected $format    = 'json';

    // Obtener notificaciones de un usuario
    public function obtenerNotificaciones($idUsuario)
    {
        $notificaciones = $this->model->obtenerNotificacionesUsuario($idUsuario);
        return $this->respond(['data' => $notificaciones], 200);
    }

    // Marcar una notificación como leída
    public function marcarLeida($idNotificacion)
    {
        if ($this->model->cambiarEstadoNotificacion($idNotificacion, 3)) {
            return $this->respond(['message' => 'Notificación marcada como leída'], 200);
        }
        return $this->failNotFound('Notificación no encontrada');
    }

    // Eliminar una notificación (se marca como eliminada, no se borra)
    public function eliminarNotificacion($idNotificacion)
    {
        if ($this->model->cambiarEstadoNotificacion($idNotificacion, 6)) {
            return $this->respond(['message' => 'Notificación eliminada'], 200);
        }
        return $this->failNotFound('Notificación no encontrada');
    }

    // Crear una nueva notificación
    public function crearNotificacion()
    {
        $data = $this->request->getJSON(true);

        if (!$this->validate([
            'id_usuario' => 'required|integer',
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'icono' => 'required|string'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $this->model->insert([
            'id_usuario' => $data['id_usuario'],
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'icono' => $data['icono'],
            'id_estado' => 1 // Estado "pendiente" por defecto
        ]);

        return $this->respond(['message' => 'Notificación creada con éxito'], 201);
    }
}
