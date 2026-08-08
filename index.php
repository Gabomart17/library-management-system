<?php
require_once 'classes/Biblioteca.php';

$biblioteca = new Biblioteca();

$accion  = $_GET['action'] ?? 'libros';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Libros ---
    if (isset($_POST['guardar_libro'])) {
        if (!empty($_POST['id'])) {
            $biblioteca->editarLibro($_POST['id'], $_POST);
            $mensaje = 'Libro actualizado correctamente.';
        } else {
            $libro = new Libro($_POST['titulo'], $_POST['autor'], $_POST['isbn'], $_POST['cantidad']);
            $biblioteca->agregarLibro($libro);
            $mensaje = 'Libro agregado correctamente.';
        }
        $accion = 'libros';
    }

    if (isset($_POST['eliminar_libro'])) {
        $biblioteca->eliminarLibro($_POST['id']);
        $mensaje = 'Libro eliminado.';
        $accion = 'libros';
    }

    // --- Usuarios ---
    if (isset($_POST['guardar_usuario'])) {
        if (!empty($_POST['id'])) {
            $biblioteca->editarUsuario($_POST['id'], $_POST);
            $mensaje = 'Usuario actualizado correctamente.';
        } else {
            $usuario = new Usuario($_POST['nombre'], $_POST['email'], $_POST['telefono']);
            $biblioteca->agregarUsuario($usuario);
            $mensaje = 'Usuario agregado correctamente.';
        }
        $accion = 'usuarios';
    }

    if (isset($_POST['eliminar_usuario'])) {
        $biblioteca->eliminarUsuario($_POST['id']);
        $mensaje = 'Usuario eliminado.';
        $accion = 'usuarios';
    }

    // --- Préstamos ---
    if (isset($_POST['prestar_libro'])) {
        $ok = $biblioteca->prestarLibro($_POST['libro_id'], $_POST['usuario_id']);
        $mensaje = $ok ? 'Préstamo registrado correctamente.' : 'No se pudo registrar el préstamo (sin stock disponible).';
        $accion = 'prestamos';
    }

    if (isset($_POST['devolver_libro'])) {
        $biblioteca->devolverLibro($_POST['prestamo_id']);
        $mensaje = 'Devolución registrada correctamente.';
        $accion = 'prestamos';
    }
}

$libroEditar = null;
if ($accion === 'libros' && isset($_GET['editar'])) {
    $libroEditar = $biblioteca->buscarLibro($_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
            color: #222;
        }

        nav {
            margin-bottom: 20px;
            background: #2c3e50;
            padding: 12px;
            border-radius: 6px;
        }

        nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            color: #2c3e50;
        }

        .mensaje {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        form.card {
            background: #fff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        form.card label {
            display: block;
            margin-top: 8px;
            font-size: 14px;
        }

        form.card input,
        form.card select {
            width: 100%;
            padding: 6px;
            margin-top: 3px;
            box-sizing: border-box;
        }

        form.card button {
            margin-top: 12px;
            padding: 8px 16px;
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #2c3e50;
            color: #fff;
        }

        .acciones a,
        .acciones button {
            margin-right: 8px;
            font-size: 13px;
        }

        .acciones button {
            background: none;
            border: none;
            color: #c0392b;
            cursor: pointer;
            padding: 0;
            text-decoration: underline;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            color: #fff;
        }

        .badge.activo {
            background: #e67e22;
        }

        .badge.devuelto {
            background: #27ae60;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>📚 Biblioteca Mini-App</h1>

        <nav>
            <a href="index.php?action=libros">Libros</a>
            <a href="index.php?action=usuarios">Usuarios</a>
            <a href="index.php?action=prestamos">Préstamos</a>
        </nav>

        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <div id="content">

            <?php if ($accion === 'libros'): ?>

                <h2><?php echo $libroEditar ? 'Editar libro' : 'Agregar libro'; ?></h2>
                <form class="card" method="POST" action="index.php">
                    <input type="hidden" name="id" value="<?php echo $libroEditar['id'] ?? ''; ?>">
                    <label>Título
                        <input type="text" name="titulo" required value="<?php echo htmlspecialchars($libroEditar['titulo'] ?? ''); ?>">
                    </label>
                    <label>Autor
                        <input type="text" name="autor" required value="<?php echo htmlspecialchars($libroEditar['autor'] ?? ''); ?>">
                    </label>
                    <label>ISBN
                        <input type="text" name="isbn" value="<?php echo htmlspecialchars($libroEditar['isbn'] ?? ''); ?>">
                    </label>
                    <label>Cantidad
                        <input type="number" name="cantidad" min="0" required value="<?php echo htmlspecialchars($libroEditar['cantidad'] ?? 1); ?>">
                    </label>
                    <button type="submit" name="guardar_libro">Guardar</button>
                </form>

                <h2>Listado de libros</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($biblioteca->obtenerLibros() as $libro): ?>
                            <tr>
                                <td><?php echo $libro['id']; ?></td>
                                <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                                <td><?php echo htmlspecialchars($libro['isbn']); ?></td>
                                <td><?php echo $libro['cantidad']; ?></td>
                                <td class="acciones">
                                    <a href="index.php?action=libros&editar=<?php echo $libro['id']; ?>">Editar</a>
                                    <form method="POST" action="index.php" style="display:inline">
                                        <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                                        <button type="submit" name="eliminar_libro" onclick="return confirm('¿Eliminar este libro?');">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php elseif ($accion === 'usuarios'): ?>

                <h2>Agregar usuario</h2>
                <form class="card" method="POST" action="index.php">
                    <input type="hidden" name="id">
                    <label>Nombre
                        <input type="text" name="nombre" required>
                    </label>
                    <label>Email
                        <input type="email" name="email" required>
                    </label>
                    <label>Teléfono
                        <input type="text" name="telefono">
                    </label>
                    <button type="submit" name="guardar_usuario">Guardar</button>
                </form>

                <h2>Listado de usuarios</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($biblioteca->obtenerUsuarios() as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                <td class="acciones">
                                    <form method="POST" action="index.php" style="display:inline">
                                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                        <button type="submit" name="eliminar_usuario" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php elseif ($accion === 'prestamos'): ?>

                <h2>Registrar préstamo</h2>
                <form class="card" method="POST" action="index.php">
                    <label>Libro
                        <select name="libro_id" required>
                            <?php foreach ($biblioteca->obtenerLibros() as $libro): ?>
                                <option value="<?php echo $libro['id']; ?>">
                                    <?php echo htmlspecialchars($libro['titulo']); ?> (disponibles: <?php echo $libro['cantidad']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Usuario
                        <select name="usuario_id" required>
                            <?php foreach ($biblioteca->obtenerUsuarios() as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>">
                                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button type="submit" name="prestar_libro">Prestar</button>
                </form>

                <h2>Préstamos activos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Usuario</th>
                            <th>Fecha préstamo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($biblioteca->obtenerPrestamosActivos() as $prestamo): ?>
                            <tr>
                                <td><?php echo $prestamo['id']; ?></td>
                                <td><?php echo htmlspecialchars($prestamo['libro_titulo']); ?></td>
                                <td><?php echo htmlspecialchars($prestamo['usuario_nombre']); ?></td>
                                <td><?php echo $prestamo['fecha_prestamo']; ?></td>
                                <td><span class="badge activo"><?php echo $prestamo['estado']; ?></span></td>
                                <td class="acciones">
                                    <form method="POST" action="index.php" style="display:inline">
                                        <input type="hidden" name="prestamo_id" value="<?php echo $prestamo['id']; ?>">
                                        <button type="submit" name="devolver_libro">Registrar devolución</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>

        </div>
    </div>
</body>

</html>