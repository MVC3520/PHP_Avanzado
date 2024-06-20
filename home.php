<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once './Modelo/Database.php';

// Crear una instancia de la conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Consulta SQL para obtener todos los productos
$sql = "SELECT id, name, description, price FROM items";
$result = $conn->query($sql);

// Variable de sesión para almacenar los productos agregados al carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Función para agregar un producto al carrito
function agregarAlCarrito($productId) {
    $_SESSION['carrito'][] = $productId;
}

// Función para obtener el detalle completo de un producto por su ID (simulado)
function obtenerProductoPorId($productId) {
    global $conn;
    $sql = "SELECT id, name, description, price FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    $stmt->close();
    return $producto;
}

// Verificar si se ha enviado el formulario para agregar productos al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregarCarrito'])) {
    $productId = $_POST['productId'];
    agregarAlCarrito($productId);
    echo "<script>alert('Producto agregado al carrito');</script>";
}

// Verificar si se ha realizado la compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar'])) {
    // Simular una compra realizada
    $_SESSION['carrito'] = array(); // Vaciar carrito
    echo "<script>alert('Compra realizada');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Tienda Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .card-text {
            color: #6c757d;
        }
        .btn-agregar {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-agregar:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .carrito {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 4px;
        }
        .comprar-btn {
            background-color: #28a745;
            border-color: #28a745;
        }
        .comprar-btn:hover {
            background-color: #218838;
            border-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Bienvenido, <?php echo $_SESSION['username']; ?></h1>
        <a href="logout.php" class="btn btn-secondary mb-3">Cerrar Sesión</a>
        
        <div class="row">
            <div class="col-md-8">
                <h2>Productos Disponibles</h2>
                <div class="row">
                    <?php while ($producto = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($producto['description']); ?></p>
                                    <p class="card-text"><strong>Precio: $<?php echo number_format($producto['price'], 2); ?></strong></p>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <input type="hidden" name="productId" value="<?php echo $producto['id']; ?>">
                                        <button type="submit" name="agregarCarrito" class="btn btn-primary btn-agregar btn-block">Agregar al Carrito</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="col-md-4">
                <h2>Carrito de Compras</h2>
                <div class="carrito">
                    <?php if (count($_SESSION['carrito']) > 0): ?>
                        <ul>
                            <?php foreach ($_SESSION['carrito'] as $productId): ?>
                                <?php $producto = obtenerProductoPorId($productId); ?>
                                <?php if ($producto): ?>
                                    <li><?php echo htmlspecialchars($producto['name']); ?> - $<?php echo number_format($producto['price'], 2); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <button type="submit" name="comprar" class="btn btn-success comprar-btn btn-block">Comprar</button>
                        </form>
                    <?php else: ?>
                        <p>No hay productos en el carrito.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos al finalizar
$conn->close();
?>
