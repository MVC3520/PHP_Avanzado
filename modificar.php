<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Item</title>
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
        <h1>Modificar Item</h1>
        <?php
        // Verificar si se recibió el parámetro ID
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            // Incluir el archivo de conexión a la base de datos
            include_once './Modelo/Database.php';

            try {
                // Establecer la conexión a la base de datos
                $database = new Database();
                $conn = $database->getConnection();

                // Preparar la consulta SQL para seleccionar el ítem por su ID
                $sql = "SELECT id, name, description, price, category_id, created, modified FROM items WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                // Verificar si se encontró el ítem
                if ($result->num_rows == 1) {
                    $item = $result->fetch_assoc(); // Obtener los datos del ítem

                    // Mostrar el formulario para modificar el ítem
                    ?>
                    <form action="actualizar.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="category_id">ID de Categoría</label>
                            <input type="number" class="form-control" id="category_id" name="category_id" value="<?php echo htmlspecialchars($item['category_id']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php
                } else {
                    echo "No se encontró el ítem.";
                }

                // Cerrar la conexión
                $stmt->close();
                $conn->close();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "ID del ítem no especificado.";
        }
        ?>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
