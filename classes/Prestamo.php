<?php

class Prestamo {
    private $id;
    private $libro_id;
    private $usuario_id;
    private $fecha_prestamo;
    private $fecha_devolucion;
    private $estado;

    public function __construct($libro_id = null, $usuario_id = null) {
        // TODO: Inicializar atributos, establecer fecha_prestamo a hoy
    }

    // Getters y Setters
    public function getId() {
        // TODO
    }

    public function getLibroId() {
        // TODO
    }

    public function getUsuarioId() {
        // TODO
    }

    public function getFechaPrestamo() {
        // TODO
    }

    public function getFechaDevolucion() {
        // TODO
    }

    public function setFechaDevolucion($fecha) {
        // TODO
    }

    public function getEstado() {
        // TODO
    }

    public function setEstado($estado) {
        // TODO
    }
}
