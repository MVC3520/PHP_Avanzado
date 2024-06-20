<?php
// Iniciar la sesión y habilitar la visualización de errores
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Incluir el archivo de conexión a la base de datos
    include_once './Modelo/Database.php';

    // Obtener los datos del formulario
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $created = date('Y-m-d H:i:s'); // Fecha y hora actual para el campo created

    try {
        // Establecer la conexión a la base de datos
        $database = new Database();
        $conn = $database->getConnection();

        // Preparar la consulta SQL para verificar si ya existe un ítem con el mismo nombre y categoría
        $sql = "SELECT * FROM items WHERE name = ? AND category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si ya existe un ítem con el mismo nombre y categoría
        if ($result->num_rows > 0) {
            echo "Error: Ya existe un ítem con el mismo nombre y categoría.";
        } else {
            // Preparar la consulta SQL para insertar el nuevo ítem
            $sql = "INSERT INTO items (name, description, price, category_id, created) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Verificar si la preparación fue exitosa
            if ($stmt) {
                // Vincular los parámetros con la consulta
                $stmt->bind_param("ssdss", $name, $description, $price, $category_id, $created);

                // Ejecutar la consulta y verificar si se insertó el ítem correctamente
                if ($stmt->execute()) {
                    // Redirigir a la página principal con un mensaje de éxito
                    $_SESSION['success_message'] = "El ítem se ha creado correctamente.";
                    header("Location: principal.php");
                    exit();
                } else {
                    echo "Error al crear el ítem: " . $stmt->error;
                }

                // Cerrar la conexión
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta: " . $conn->error;
            }
        }

        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
