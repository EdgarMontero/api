<?php

include 'db.php';

$dni_paciente = $_POST['dni_paciente'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$direccion = $_POST['direccion'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$user_id = $_POST['user_id'] ?? null;

if (empty($dni_paciente) || empty($nombre) || empty($fecha_nacimiento) || empty($direccion) || empty($telefono) || empty($user_id)) {
    echo "Error al guardar el paciente: Todos los campos son obligatorios.";
    exit;
}

try {

    $query = "SELECT COUNT(*) FROM pacientes WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if ($exists > 0) {
        echo "Error al guardar el paciente: Ya existe un registro con el mismo DNI y usuario.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO pacientes (dni_paciente, user_id, nombre, fecha_nacimiento, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $dni_paciente, $user_id, $nombre, $fecha_nacimiento, $direccion, $telefono);
    $stmt->execute();
    echo "success: Paciente guardado con éxito";
} catch (mysqli_sql_exception $e) {
    echo "Error al guardar el paciente: " . $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
