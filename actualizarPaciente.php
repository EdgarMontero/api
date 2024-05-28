<?php

include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dni_paciente = $_POST['dni_paciente'];
$nombre = $_POST['nombre'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];

try {
    $stmt = $conn->prepare("UPDATE pacientes SET nombre = ?, fecha_nacimiento = ?, direccion = ?, telefono = ? WHERE dni_paciente = ?");
    $stmt->bind_param("sssss", $nombre, $fecha_nacimiento, $direccion, $telefono, $dni_paciente);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => "Datos actualizados correctamente."));
    } else {
        echo json_encode(array("error" => "No se actualizó ningún dato."));
    }
} catch (mysqli_sql_exception $e) {
    echo "Error al actualizar el paciente: " . $e->getMessage();
} finally {
    $stmt->close();
    $conn->close();
}
