<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

// ========================================================================
//                           User ROUTES
// ========================================================================

$routes->post('/register', 'Users::register');
$routes->post('/login', 'Users::login');

$routes->get('perfil/(:num)', 'Users::getProfile/$1');   // Obtener perfil
$routes->put('perfil/(:num)', 'Users::updateProfile/$1'); // Actualizar perfil

// ========================================================================
//                           Citas ROUTES
// ========================================================================
$routes->group('citas', function ($routes) {
    $routes->get('especialidades', 'Citas::getEspecialidades'); // Obtener especialidades
    $routes->get('centros/(:num)', 'Citas::getCentrosPorEspecialidad/$1'); // Centros por especialidad
    $routes->get('fechas/(:num)/(:num)', 'Citas::getFechasDisponibles/$1/$2'); // Fechas disponibles
    $routes->get('horarios/(:num)/(:num)/(:any)', 'Citas::getHorariosDisponibles/$1/$2/$3'); // Horarios por fecha
    $routes->get('especialidades_reservadas/(:num)', 'Citas::getEspecialidadesReservadas/$1');
    $routes->post('reservar', 'Citas::reservarCita'); // Reservar cita
    $routes->get('programadas/(:num)', 'Citas::getCitasProgramadas/$1');
    $routes->post('cancelar/(:num)', 'Citas::cancelarCita/$1');
    $routes->post('reagendar', 'Citas::reagendarCita');    
});

// ========================================================================
//                           Notificaciones ROUTES
// ========================================================================
$routes->group('notificaciones', function ($routes) {
    $routes->get('usuario/(:num)', 'Notificaciones::getNotificaciones/$1'); // Obtener notificaciones
    $routes->post('crear', 'Notificaciones::crearNotificacion'); // Crear notificación
    $routes->delete('eliminar/(:num)', 'Notificaciones::eliminarNotificacion/$1'); // Eliminar notificación
});
