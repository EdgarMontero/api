<?php

include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dni_medico = $_POST['dni_medico'];
$nombre = $_POST['nombre'];
$especialidad = $_POST['especialidad'];

try {

    $stmt = $conn->prepare("UPDATE medicos SET nombre = ?, especialidad = ? , updated_at = NOW() WHERE dni_medico = ?");
    $stmt->bind_param("sss", $nombre, $especialidad, $dni_medico);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => "Datos actualizados correctamente."));
    } else {
        echo json_encode(array("error" => "No se actualizó ningún dato."));
    }
} catch (mysqli_sql_exception $e) {
    echo "Error al actualizar el medico: " . $e->getMessage();
} finally {
    $stmt->close();
    $conn->close();
}
