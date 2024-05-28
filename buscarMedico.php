<?php

include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dni_medico = $_POST['dni_medico'] ?? '';

if (empty($dni_medico)) {
    echo "Error: El nombre de usuario es obligatorio.";
    exit;
}

try {
    $stmt = $conn->prepare("SELECT dni_medico, nombre, especialidad FROM medicos WHERE dni_medico = ?");
    $stmt->bind_param("s", $dni_medico);
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
