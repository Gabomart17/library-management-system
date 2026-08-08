<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro)
    {
        $sql = "INSERT INTO libros (titulo, autor, isbn, cantidad) 
                VALUES (:titulo, :autor, :isbn, :cantidad)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo'   => $libro->getTitulo(),
            ':autor'    => $libro->getAutor(),
            ':isbn'     => $libro->getIsbn(),
            ':cantidad' => $libro->getCantidad(),
        ]);
    }

    public function editarLibro($id, $nuevosDatos)
    {
        $sql = "UPDATE libros 
                SET titulo = :titulo, autor = :autor, isbn = :isbn, cantidad = :cantidad 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo'   => $nuevosDatos['titulo'],
            ':autor'    => $nuevosDatos['autor'],
            ':isbn'     => $nuevosDatos['isbn'],
            ':cantidad' => $nuevosDatos['cantidad'],
            ':id'       => $id,
        ]);
    }

    public function eliminarLibro($id)
    {
        $sql = "DELETE FROM libros WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerLibros()
    {
        $sql = "SELECT * FROM libros ORDER BY titulo ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function buscarLibro($id)
    {
        $sql = "SELECT * FROM libros WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario)
    {
        $sql = "INSERT INTO usuarios (nombre, email, telefono) 
                VALUES (:nombre, :email, :telefono)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $usuario->getNombre(),
            ':email'    => $usuario->getEmail(),
            ':telefono' => $usuario->getTelefono(),
        ]);
    }

    public function editarUsuario($id, $nuevosDatos)
    {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, email = :email, telefono = :telefono 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $nuevosDatos['nombre'],
            ':email'    => $nuevosDatos['email'],
            ':telefono' => $nuevosDatos['telefono'],
            ':id'       => $id,
        ]);
    }

    public function eliminarUsuario($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT * FROM usuarios ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Gestión de Préstamos
    public function prestarLibro($libro_id, $usuario_id)
    {
        try {
            $this->conn->beginTransaction();

            $libro = $this->buscarLibro($libro_id);
            if (!$libro || $libro['cantidad'] < 1) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, estado) 
                    VALUES (:libro_id, :usuario_id, :fecha_prestamo, 'activo')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':libro_id'       => $libro_id,
                ':usuario_id'     => $usuario_id,
                ':fecha_prestamo' => date('Y-m-d'),
            ]);

            $sqlUpdate = "UPDATE libros SET cantidad = cantidad - 1 WHERE id = :id";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute([':id' => $libro_id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function devolverLibro($prestamo_id)
    {
        // TODO: Actualizar fecha de devolución y estado del préstamo, actualizar stock
        try {
            $this->conn->beginTransaction();

            $sql = "SELECT libro_id FROM prestamos WHERE id = :id AND estado = 'activo'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $prestamo_id]);
            $prestamo = $stmt->fetch();

            if (!$prestamo) {
                $this->conn->rollBack();
                return false;
            }

            $sqlUpdate = "UPDATE prestamos 
                    SET estado = 'devuelto', fecha_devolucion = :fecha 
                    WHERE id = :id";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':fecha' => date('Y-m-d'),
                ':id'    => $prestamo_id,
            ]);

            $sqlStock = "UPDATE libros SET cantidad = cantidad + 1 WHERE id = :id";
            $stmtStock = $this->conn->prepare($sqlStock);
            $stmtStock->execute([':id' => $prestamo['libro_id']]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function obtenerPrestamosActivos()
    {
        $sql = "SELECT p.id, p.fecha_prestamo, p.fecha_devolucion, p.estado,
                    l.titulo AS libro_titulo, u.nombre AS usuario_nombre
                FROM prestamos p
                JOIN libros l ON p.libro_id = l.id
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.estado = 'activo'
                ORDER BY p.fecha_prestamo DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
