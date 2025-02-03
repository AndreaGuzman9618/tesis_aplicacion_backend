<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends ResourceController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        // Validación de datos
        $validation = \Config\Services::validation();
    
        $rules = [
            'nombre'           => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[usuarios.email]',
            'password'         => 'required|min_length[8]',
            'id_rol'           => 'required|integer|in_list[1,2,3]', // 1: paciente, 2: admin, 3: doctor
            'coordenadas_lat'  => 'permit_empty|decimal',
            'coordenadas_lon'  => 'permit_empty|decimal',
            'telefono'         => 'permit_empty|max_length[15]',
            'cedula'         => 'permit_empty|max_length[11]',
            'direccion'        => 'permit_empty|max_length[255]',
        ];
    
        if (!$this->validate($rules)) {
            return $this->fail($validation->getErrors(), 400);
        }
    
        // Encriptar contraseña
        $hashedPassword = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
    
        // Datos a guardar
        $data = [
            'nombre'           => $this->request->getVar('nombre'),
            'email'            => $this->request->getVar('email'),
            'password'         => $hashedPassword,
            'telefono'         => $this->request->getVar('telefono'),
            'cedula'         => $this->request->getVar('cedula'),
            'direccion'        => $this->request->getVar('direccion'),
            'id_rol'           => $this->request->getVar('id_rol'),
            'coordenadas_lat'  => $this->request->getVar('coordenadas_lat'),
            'coordenadas_lon'  => $this->request->getVar('coordenadas_lon'),
        ];
    
        // Guardar usuario en la base de datos
        if ($this->userModel->save($data)) {
            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Usuario registrado exitosamente.',
                'data'    => $data,
            ]);
        } else {
            return $this->failServerError('Error al registrar el usuario.');
        }
    }

    public function login()
    {
        $cedula = $this->request->getVar('cedula'); 
        $password = $this->request->getVar('password');

        // Validar datos obligatorios
        if (!$cedula || !$password) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_BAD_REQUEST,
                'message' => 'Por favor, ingrese una cédula y contraseña.',
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Consultar el usuario en la base de datos
        $userModel = new UserModel();
        $user = $userModel->where('cedula', $cedula)->first();

        // Verificar si el usuario existe
        if (!$user) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_UNAUTHORIZED,
                'message' => 'Usuario no encontrado o credenciales incorrectas.',
            ], ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Verificar la contraseña utilizando password_verify
        if (!password_verify($password, $user['password'])) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_UNAUTHORIZED,
                'message' => 'Usuario no encontrado o credenciales incorrectas.',
            ], ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Si las credenciales son correctas, generar una respuesta exitosa
        return $this->respond([
            'status' => ResponseInterface::HTTP_OK,
            'message' => 'Inicio de sesión exitoso.',
            'data' => [
                'id_usuario' => (int)$user['id_usuario'],
                'nombre' => $user['nombre'],
                'cedula' => $user['cedula'],
                'email' => $user['email'],
            ],
        ], ResponseInterface::HTTP_OK);
    }    

    // Obtener datos del perfil
    public function getProfile($id = null)
    {
        if (!$id) {
            return $this->respond([
                'status' => 400,
                'message' => 'ID del usuario no proporcionado.',
            ], 400);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->respond([
                'status' => 404,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Perfil obtenido con éxito.',
            'data' => [
                'id_usuario' => $user['id_usuario'],
                'nombre' => $user['nombre'],
                'email' => $user['email'],
                'telefono' => $user['telefono'],
                'direccion' => $user['direccion'],
                'coordenadas_lat' => $user['coordenadas_lat'],
                'coordenadas_lon' => $user['coordenadas_lon'],
            ],
        ], 200);
    }

    // Actualizar datos del perfil
    public function updateProfile($id = null)
    {
        if (!$id) {
            return $this->respond([
                'status' => 400,
                'message' => 'ID del usuario no proporcionado.',
            ], 400);
        }

        $data = $this->request->getJSON(true);

        if (!$this->validate([
            'email' => 'required|valid_email',
            'telefono' => 'required|min_length[10]',
        ])) {
            return $this->respond([
                'status' => 400,
                'message' => 'Datos inválidos.',
                'errors' => $this->validator->getErrors(),
            ], 400);
        }

        $updateData = [
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'coordenadas_lat' => $data['coordenadas_lat'],
            'coordenadas_lon' => $data['coordenadas_lon'],
        ];

        $this->userModel->update($id, $updateData);

        return $this->respond([
            'status' => 200,
            'message' => 'Perfil actualizado con éxito.',
        ], 200);
    }


}