<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$error = '';

// Solo ejecutar la autenticación si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once './Modelo/Database.php';

    $database = new Database();
    $conn = $database->getConnection();

    // Verificar si se recibieron los campos de username y password
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Consulta SQL para obtener el usuario por nombre de usuario
        $sql = "SELECT * FROM usuario WHERE username='$username' AND password=MD5('$password')";
        $result = $conn->query($sql);

        // Verificar si la consulta fue exitosa y hay resultados
        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verificar la contraseña
            if ($user['password'] === md5($password)) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirigir según el rol del usuario
                if ($user['role'] == 'admin') {
                    // Redirigir a principal.php
                    header("Location: principal.php");
                    exit();
                } else {
                    // Redirigir a home.php
                    header("Location: home.php");
                    exit();
                }
            } else {
                $error = 'Nombre de usuario o contraseña incorrectos';
            }
        } else {
            $error = 'Nombre de usuario o contraseña incorrectos';
        }
    } else {
        $error = 'Por favor, complete todos los campos.';
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online - Inicio de Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Inicio de Sesión</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="index.php" method="post">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            <a href="register.php" class="btn btn-secondary btn-block">Registrarse</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Mostrar mensaje en consola si el usuario es válido
        <?php if (isset($_SESSION['username'])): ?>
            console.log('Usuario válido: <?php echo $_SESSION['username']; ?>');
        <?php endif; ?>
    </script>
</body>
</html>

