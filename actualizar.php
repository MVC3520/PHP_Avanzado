<?php
// Iniciar la sesión y habilitar la visualización de errores
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se recibió el formulario con datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Incluir el archivo de conexión a la base de datos
    include_once './Modelo/Database.php';

    // Obtener los datos del formulario
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $modified = date('Y-m-d H:i:s'); // Fecha y hora actual

    try {
        // Establecer la conexión a la base de datos
        $database = new Database();
        $conn = $database->getConnection();

        // Preparar la consulta SQL para actualizar el ítem
        $sql = "UPDATE items SET name = ?, description = ?, price = ?, category_id = ?, modified = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsis", $name, $description, $price, $category_id, $modified, $id);

        // Ejecutar la consulta y verificar si se actualizó el ítem
        if ($stmt->execute()) {
            // Redirigir a la página principal con un mensaje de éxito
            $_SESSION['success_message'] = "El ítem se ha actualizado correctamente.";
            header("Location: principal.php");
            exit();
        } else {
            echo "Error al actualizar el ítem: " . $stmt->error;
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
