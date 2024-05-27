<?php

include 'db.php';


$dni_paciente = $_POST['dni_paciente'] ?? '';

if (empty($dni_paciente)) {
    echo "Error: El nombre de usuario es obligatorio.";
    exit;
}

try {

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
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
