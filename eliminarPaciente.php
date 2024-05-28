<?php

include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dni_paciente = $_POST['dni_paciente'] ?? '';
$eliminar_consultas = $_POST['eliminar_consultas'] ?? 'no';

if (empty($dni_paciente)) {
    echo json_encode(array("error" => "El DNI del paciente es obligatorio."));
    exit;
}

try {

    $checkConsultasStmt = $conn->prepare("SELECT COUNT(*) as consulta_count FROM consultas WHERE id_paciente = ?");
    $checkConsultasStmt->bind_param("s", $dni_paciente);
    $checkConsultasStmt->execute();
    $result = $checkConsultasStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['consulta_count'] > 0 && $eliminar_consultas !== 'si') {
        echo json_encode(array("error" => "El paciente tiene consultas. ¿Desea eliminarlas también?", "consultas" => true));
        exit;
    }

    if ($eliminar_consultas === 'si') {
        $deleteConsultasStmt = $conn->prepare("DELETE FROM consultas WHERE id_paciente = ?");
        $deleteConsultasStmt->bind_param("s", $dni_paciente);
        $deleteConsultasStmt->execute();
        $deleteConsultasStmt->close();
    }

    $stmt = $conn->prepare("DELETE FROM pacientes WHERE dni_paciente = ?");
    $stmt->bind_param("s", $dni_paciente);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => "Paciente eliminado correctamente."));
    } else {
        echo json_encode(array("error" => "No se encontró paciente para el DNI proporcionado."));
    }
} catch (mysqli_sql_exception $e) {
    echo json_encode(array("error" => "Error al eliminar el paciente: " . $e->getMessage()));
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    if (isset($checkConsultasStmt)) {
        $checkConsultasStmt->close();
    }
}

