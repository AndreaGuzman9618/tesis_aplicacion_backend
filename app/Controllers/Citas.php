<?php

namespace App\Controllers;

use App\Models\EspecialidadesModel;
use App\Models\CentrosSaludModel;
use App\Models\DisponibilidadesModel;
use App\Models\NotificacionesModel;
use App\Models\CitasModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Citas extends ResourceController
{
    protected $especialidadesModel;
    protected $centrosSaludModel;
    protected $disponibilidadesModel;
    protected $citasModel;

    public function __construct()
    {
        $this->especialidadesModel = new EspecialidadesModel();
        $this->centrosSaludModel = new CentrosSaludModel();
        $this->disponibilidadesModel = new DisponibilidadesModel();
        $this->notificacionesModel = new NotificacionesModel();
        $this->citasModel = new CitasModel();
    }

    // Obtener especialidades
    public function getEspecialidades()
    {
        $especialidades = $this->especialidadesModel->findAll();
        return $this->respond(['data' => $especialidades], ResponseInterface::HTTP_OK);
    }

    // Obtener centros de salud por especialidad
    public function getCentrosPorEspecialidad($idEspecialidad)
    {
        // Consultar los centros de salud con disponibilidad para la especialidad
        $centros = $this->disponibilidadesModel->select('centros_salud.id_centro, centros_salud.nombre, centros_salud.direccion')
            ->join('centros_salud', 'disponibilidades.id_centro = centros_salud.id_centro')
            ->where('disponibilidades.id_especialidad', $idEspecialidad)
            ->distinct()
            ->findAll();
    
        // Si no hay registros en la tabla de disponibilidades, devolver todos los centros de salud
        if (empty($centros)) {
            $centros = $this->centrosSaludModel->findAll();
        }
    
        return $this->respond(['data' => $centros], ResponseInterface::HTTP_OK);
    }
    

    // Obtener fechas disponibles por centro y especialidad
    public function getFechasDisponibles($idCentro, $idEspecialidad)
    {
        $model = new DisponibilidadesModel();
        $fechas = $model->where('id_centro', $idCentro)
                        ->where('id_especialidad', $idEspecialidad)
                        ->findAll();
    
        // ✅ Si la tabla está vacía, generar dinámicamente las fechas
        if (empty($fechas)) {
            $hoy = date('N'); // 1 = Lunes, 7 = Domingo
            $fechaInicio = date('Y-m-d');
    
            // 🔹 Si hoy es sábado (6) o domingo (7), empezar el próximo lunes
            if ($hoy == 6) {
                $fechaInicio = date('Y-m-d', strtotime('next Monday'));
            } elseif ($hoy == 7) {
                $fechaInicio = date('Y-m-d', strtotime('next Monday'));
            }
    
            // 🔹 Determinar la fecha de fin (viernes)
            $fechaFin = date('Y-m-d', strtotime('next Friday', strtotime($fechaInicio)));
    
            $horarios = $this->generarHorariosDisponibles(); // Obtiene los horarios
    
            $fechasGeneradas = [];
            for ($fecha = strtotime($fechaInicio); $fecha <= strtotime($fechaFin); $fecha += 86400) {
                $diaSemana = date('N', $fecha); // 1 = Lunes, ..., 5 = Viernes
                if ($diaSemana >= 1 && $diaSemana <= 5) { // Solo de lunes a viernes
                    $fechasGeneradas[] = [
                        'fecha' => date('Y-m-d', $fecha),
                        'horarios' => $horarios, // Intervalos de 45 minutos
                    ];
                }
            }
    
            return $this->respond([
                'status' => 200,
                'message' => 'Fechas generadas dinámicamente.',
                'data' => $fechasGeneradas
            ], 200);
        }
    
        return $this->respond([
            'status' => 200,
            'message' => 'Fechas obtenidas desde la base de datos.',
            'data' => $fechas
        ], 200);
    }
    
    // ✅ Generar horarios disponibles en intervalos de 45 minutos
    private function generarHorariosDisponibles()
    {
        $horaInicio = strtotime("08:00:00"); // Desde las 08:00 AM
        $horaFin = strtotime("17:00:00"); // Hasta las 05:00 PM
        $intervalo = 45 * 60; // 45 minutos en segundos
    
        $horarios = [];
        for ($hora = $horaInicio; $hora < $horaFin; $hora += $intervalo) {
            $horarios[] = date('H:i', $hora);
        }
    
        return $horarios;
    }
    
    public function getHorariosDisponibles($idCentro, $idEspecialidad, $fechaSeleccionada)
    {
        $horaInicio = new \DateTime('08:00');
        $horaFin = new \DateTime('17:00');
        $intervalo = new \DateInterval('PT45M'); // Intervalo de 45 minutos
    
        $horariosDisponibles = [];
        
        // Generar horarios automáticos de 45 minutos
        while ($horaInicio < $horaFin) {
            $horaStr = $horaInicio->format('H:i:s');
            $estadoValidos = [1, 4];
            //log_message('error', "Verificando horario con: Centro: {$idCentro}, Especialidad: {$idEspecialidad}, Fecha: {$fechaSeleccionada}, Hora: {$horaStr}");
    
            // Verificar si el horario está ocupado
            $ocupado = $this->citasModel
                ->where('id_centro', (int)$idCentro)
                ->where('id_especialidad', (int)$idEspecialidad)
                ->whereIn('id_estado', $estadoValidos)
                ->where('fecha_cita', date('Y-m-d', strtotime($fechaSeleccionada))) // Formato de fecha
                ->where('hora_cita', $horaStr) // Mantén el formato H:i:s directamente
                ->countAllResults();
    
            //log_message('error', "Resultado de ocupación para {$horaStr}: {$ocupado}");
    
            if ($ocupado == 0) {
                $horariosDisponibles[] = $horaStr;
            }
    
            $horaInicio->add($intervalo); // Avanza al siguiente intervalo
        }
    
        return $this->respond(['data' => $horariosDisponibles], ResponseInterface::HTTP_OK);
    }
    
    // Reservar una cita
    public function reservarCita()
    {
        $data = $this->request->getJSON(true);
    
        // Validar que los datos necesarios estén presentes
        if (!$this->validate([
            'id_usuario' => 'required|integer',
            'id_especialidad' => 'required|integer',
            'id_centro' => 'required|integer',
            'fecha_cita' => 'required|valid_date[Y-m-d]',
            'hora_cita' => 'required|valid_date[H:i:s]',
            'id_estado' => 'required|integer',
            'motivo' => 'permit_empty|string',
        ])) {
            return $this->respond([
                'status' => 400,
                'message' => 'Datos inválidos.',
                'errors' => $this->validator->getErrors(),
            ], 400);
        }

        $estadoValidos = [1, 4];

        // Verificar si el usuario ya tiene una cita en la misma especialidad
        $existeCita = $this->citasModel
            ->where('id_usuario', $data['id_usuario'])
            ->where('id_especialidad', $data['id_especialidad'])
            ->whereIn('id_estado', $estadoValidos)
            ->where('fecha_cita >=', date('Y-m-d')) // Solo futuras citas
            ->countAllResults();
    
        if ($existeCita > 0) {
            return $this->respond([
                'status' => 400,
                'message' => 'Ya tienes una cita programada para esta especialidad.',
            ], 400);
        }
    
        // Insertar la nueva cita
        $this->citasModel->insert([
            'id_usuario' => $data['id_usuario'],
            'id_especialidad' => $data['id_especialidad'],
            'id_centro' => $data['id_centro'],
            'fecha_cita' => $data['fecha_cita'],
            'hora_cita' => $data['hora_cita'],
            'id_estado' => $data['id_estado'],
            //'motivo' => $data['motivo'],
        ]);
    
        return $this->respond([
            'status' => 201,
            'message' => 'Cita reservada con éxito.',
        ], 201);
    }
    
    
    public function getEspecialidadesReservadas($idUsuario)
    {
        // Solo traer citas que estén en estados válidos (por ejemplo, pendientes o confirmadas)
        $estadoValidos = [1, 4]; // Pendiente, Confirmada (puedes ajustar según tu lógica)
    
        $especialidadesReservadas = $this->citasModel
            ->select('id_especialidad')
            ->where('id_usuario', $idUsuario)
            ->whereIn('id_estado', $estadoValidos)
            ->groupBy('id_especialidad')
            ->findAll();
    
        if (empty($especialidadesReservadas)) {
            return $this->respond(['data' => []], ResponseInterface::HTTP_OK);
        }
    
        // Extraer solo los IDs de especialidades
        $especialidadesIds = array_column($especialidadesReservadas, 'id_especialidad');
    
        return $this->respond(['data' => $especialidadesIds], ResponseInterface::HTTP_OK);
    }
    
    
    public function getCitasProgramadas($idUsuario)
    {
        $citas = $this->citasModel
            ->select('citas.*, especialidades.nombre AS especialidad, centros_salud.nombre AS centro')
            ->join('especialidades', 'citas.id_especialidad = especialidades.id_especialidad')
            ->join('centros_salud', 'citas.id_centro = centros_salud.id_centro')
            ->where('citas.id_usuario', $idUsuario)
            ->whereIn('citas.id_estado', [1, 4]) // Pendiente y Reagendada
            ->findAll();
    
        return $this->respond(['data' => $citas], ResponseInterface::HTTP_OK);
    }
    
    // Cancelar una cita
    public function cancelarCita($idCita)
    {

        log_message('error', "Intentando cancelar cita con ID: $idCita");

        // Verificar si la cita existe antes de actualizar
        $citaExistente = $this->citasModel->find($idCita);

        if (!$citaExistente) {
            return $this->failNotFound("Cita no encontrada");
        }

        // Intentar actualizar el estado de la cita a cancelado (3)
        $actualizado = $this->citasModel->update($idCita, ['id_estado' => 3]);

        if (!$actualizado) {
            return $this->fail("Error al cancelar la cita. Inténtalo de nuevo.", 500);
        }

        // 🔹 Crear notificación automática
        $this->crearNotificacionAutomatica(
            $citaExistente['id_usuario'],
            "Cita cancelada",
            "Tu cita programada para el {$citaExistente['fecha_cita']} a las {$citaExistente['hora_cita']} ha sido cancelada.",
            "cancel"
        );

        return $this->respond([
            'status' => 200,
            'message' => 'Cita cancelada con éxito'
        ], ResponseInterface::HTTP_OK);
    }

    // Reagendar una cita
    public function reagendarCita()
    {
        $data = $this->request->getJSON(true);

        if (!$this->validate([
            'id_cita' => 'required|integer',
            'nueva_fecha' => 'required|valid_date',
            'nueva_hora' => 'required'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $this->citasModel->update($data['id_cita'], [
            'fecha_cita' => $data['nueva_fecha'],
            'hora_cita' => $data['nueva_hora'],
            'id_estado' => 4
        ]);

        return $this->respond([
            'status' => 200,
            'message' => 'Cita reagendada con éxito'
        ], ResponseInterface::HTTP_OK);
    }    


    private function crearNotificacionAutomatica($idUsuario, $titulo, $descripcion, $icono)
    {
        $this->notificacionesModel->insert([
            'id_usuario'   => $idUsuario,
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'icono'        => $icono,
            'id_estado'    => 1 // Pendiente por defecto
        ]);
    }

}
