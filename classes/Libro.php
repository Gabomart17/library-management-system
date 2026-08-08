<?php

class Libro
{
    private $id;
    private $titulo;
    private $autor;
    private $isbn;
    private $cantidad;

    public function __construct($titulo = null, $autor = null, $isbn = null, $cantidad = 1)
    {
        $this->setTitulo($titulo);
        $this->setAutor($autor);
        $this->setIsbn($isbn);
        $this->setCantidad($cantidad);
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

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor($autor)
    {
        $this->autor = $autor;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = (int) $cantidad;
    }
}
