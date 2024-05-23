<?php

include 'db.php';

$dni = $_POST['dni'];
$tipo_dni = $_POST['tipo_dni']; // 'paciente' o 'medico'
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
$estado_consulta = isset($_POST['estado_consulta']) ? $_POST['estado_consulta'] : '';

if ($tipo_dni === 'paciente') {
    $query = "SELECT id_consulta, id_medico, id_paciente, tipo_consulta, descripcion_consulta, fecha_consulta, estado_consulta FROM consultas WHERE id_paciente = ?";
} elseif ($tipo_dni === 'medico') {
    $query = "SELECT id_consulta, id_medico, id_paciente, tipo_consulta, descripcion_consulta, fecha_consulta, estado_consulta FROM consultas WHERE id_medico = ?";
} else {
    echo json_encode(['error' => 'Tipo de DNI no válido']);
    exit;
}

$params = [$dni];
$types = 's';

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $query .= " AND fecha_consulta BETWEEN ? AND ?";
    array_push($params, $fecha_inicio, $fecha_fin);
    $types .= 'ss';
} elseif (!empty($fecha_inicio)) {
    $query .= " AND fecha_consulta >= ?";
    array_push($params, $fecha_inicio);
    $types .= 's';
} elseif (!empty($fecha_fin)) {
    $query .= " AND fecha_consulta <= ?";
    array_push($params, $fecha_fin);
    $types .= 's';
}

if (!empty($estado_consulta) && $estado_consulta !== 'Todos') {
    $query .= " AND estado_consulta = ?";
    array_push($params, $estado_consulta);
    $types .= 's';
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$consultas = [];

while ($row = $result->fetch_assoc()) {
    $consultas[] = $row;
}

echo json_encode($consultas);
$stmt->close();
$conn->close();
