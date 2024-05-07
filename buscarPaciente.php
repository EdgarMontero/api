<?php
// Habilitar reporte de errores de mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Recibir el nombre de usuario desde una petición GET o POST (ajustar según necesidad)
$dni_paciente = $_POST['dni_paciente'] ?? '';

if (empty($dni_paciente)) {
    echo "Error: El nombre de usuario es obligatorio.";
    exit;
}

try {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'proyecto');

    // Buscar información del paciente usando el ID de usuario
    $stmt = $conn->prepare("SELECT dni_paciente, nombre, user_id, fecha_nacimiento, direccion, telefono FROM pacientes WHERE dni_paciente = ?");
    $stmt->bind_param("s", $dni_paciente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    $paciente = $result->fetch_assoc();
    echo json_encode($paciente);
} else {
    echo json_encode(array("error" => "No se encontró paciente para el DNI proporcionado."));
}
} catch (mysqli_sql_exception $e) {
    echo "Error al buscar el paciente: " . $e->getMessage();
} finally {
    // Cerrar statement y conexión
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>