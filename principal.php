<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Items</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Items</h1>
        <a href="alta.php" class="btn btn-primary mb-3">Agregar Item</a>
        <a href="logout.php" class="btn btn-danger mb-3">Cerrar Sesión</a>

        <?php
        // Incluir el archivo de conexión a la base de datos
        include_once './Modelo/Database.php';

        try {
            // Establecer la conexión a la base de datos
            $database = new Database();
            $conn = $database->getConnection();

            // Ejecutar consulta SQL para obtener los items
            $sql = "SELECT id, name, description, price, category_id FROM items";
            $result = $conn->query($sql);

            // Verificar si hay resultados y mostrarlos en una tabla
            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Nombre</th>";
                echo "<th>Descripción</th>";
                echo "<th>Precio</th>";
                echo "<th>ID de Categoría</th>";
                echo "<th>Acción</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['category_id']}</td>";
                    echo "<td>";
                    echo "<a href='modificar.php?id={$row['id']}' class='btn btn-sm btn-primary mr-1'>Editar</a>";
                    echo "<a href='borrar.php?id={$row['id']}' class='btn btn-sm btn-danger'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "No se encontraron items.";
            }

            // Cerrar la conexión
            $conn->close();
        } catch (Exception $e) {
            echo "Error en la consulta SQL: " . $e->getMessage();
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
