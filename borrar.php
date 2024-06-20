<?php
// Verificar si se recibió el parámetro ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Incluir el archivo de conexión a la base de datos
    include_once './Modelo/Database.php';

    try {
        // Establecer la conexión a la base de datos
        $database = new Database();
        $conn = $database->getConnection();

        // Preparar la consulta SQL para eliminar el ítem por su ID
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_GET['id']);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir de vuelta a la página principal (index.php) después de eliminar el ítem
            header("Location: principal.php");
            exit();
        } else {
            echo "Error al intentar eliminar el ítem.";
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
