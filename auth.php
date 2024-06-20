<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Aquí puedes agregar lógica adicional para manejar acciones específicas de autenticación
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Autenticación</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <p>Su rol es: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
