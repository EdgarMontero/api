<?php
include 'db.php';

$dni_medico = $_POST['dni_medico'];
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

$query = "SELECT id_consulta, id_medico, id_paciente, tipo_consulta, descripcion_consulta, fecha_consulta, estado_consulta FROM consultas WHERE id_medico = ?";

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $query .= " AND fecha_consulta BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $dni_medico, $fecha_inicio, $fecha_fin);
} elseif (!empty($fecha_inicio)) {
    $query .= " AND fecha_consulta >= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $dni_medico, $fecha_inicio);
} elseif (!empty($fecha_fin)) {
    $query .= " AND fecha_consulta <= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $dni_medico, $fecha_fin);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $dni_medico);
}

$stmt->execute();
$result = $stmt->get_result();
$consultas = [];

while ($row = $result->fetch_assoc()) {
    $consultas[] = $row;
}

echo json_encode($consultas);
$stmt->close();
$conn->close();

