<?php

namespace App\Models;
use CodeIgniter\Model;

class NotificacionesModel extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';
    protected $allowedFields = [
        'id_usuario', 'titulo', 'descripcion', 'icono', 'id_estado', 'fecha_creacion'
    ];

    public function obtenerNotificacionesUsuario($idUsuario)
    {
        return $this->select('notificaciones.*, estado_notificaciones.nombre_estado AS estado')
            ->join('estado_notificaciones', 'notificaciones.id_estado = estado_notificaciones.id_estado')
            ->where('notificaciones.id_usuario', $idUsuario)
            ->orderBy('notificaciones.fecha_creacion', 'DESC')
            ->findAll();
    }

    public function cambiarEstadoNotificacion($idNotificacion, $nuevoEstado)
    {
        // Obtener el estado anterior
        $notificacion = $this->find($idNotificacion);
        if (!$notificacion) return false;

        $estadoAnterior = $notificacion['id_estado'];

        // Actualizar estado en la notificación
        $this->update($idNotificacion, ['id_estado' => $nuevoEstado]);

        // Registrar en el historial
        $db = db_connect();
        $db->table('historial_notificaciones')->insert([
            'id_notificacion' => $idNotificacion,
            'id_estado_anterior' => $estadoAnterior,
            'id_estado_nuevo' => $nuevoEstado
        ]);

        return true;
    }
}
