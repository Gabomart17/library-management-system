<?php

class Usuario
{
    private $id;
    private $nombre;
    private $email;
    private $telefono;

    public function __construct($nombre = null, $email = null, $telefono = null)
    {
        $this->setNombre($nombre);
        $this->setEmail($email);
        $this->setTelefono($telefono);
    }

    // Getters y Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
}
