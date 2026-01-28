<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca {
    private $db;
    private $conn;

    public function __construct() {
        // TODO: Inicializar conexión a base de datos
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro) {
        // TODO: Insertar libro en base de datos
    }

    public function editarLibro($id, $nuevosDatos) {
        // TODO: Actualizar libro en base de datos
    }

    public function eliminarLibro($id) {
        // TODO: Eliminar libro de base de datos
    }

    public function obtenerLibros() {
        // TODO: Retornar lista de libros disponibles
        return [];
    }

    public function buscarLibro($id) {
        // TODO: Retornar un libro específico
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario) {
        // TODO: Insertar usuario en base de datos
    }

    public function editarUsuario($id, $nuevosDatos) {
        // TODO: Actualizar usuario en base de datos
    }

    public function eliminarUsuario($id) {
        // TODO: Eliminar usuario de base de datos
    }

    public function obtenerUsuarios() {
        // TODO: Retornar lista de usuarios
        return [];
    }

    // Gestión de Préstamos
    public function prestarLibro($libro_id, $usuario_id) {
        // TODO: Crear registro de préstamo y actualizar stock de libros
    }

    public function devolverLibro($prestamo_id) {
        // TODO: Actualizar fecha de devolución y estado del préstamo, actualizar stock
    }

    public function obtenerPrestamosActivos() {
        // TODO: Retornar lista de préstamos activos
        return [];
    }
}
