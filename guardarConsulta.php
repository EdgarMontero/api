<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni_medico = isset($_POST['dni_medico']) ? $_POST['dni_medico'] : '';
    $dni_paciente = isset($_POST['dni_paciente']) ? $_POST['dni_paciente'] : '';
    $tipo_consulta = isset($_POST['tipo_consulta']) ? $_POST['tipo_consulta'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $estado_consulta = isset($_POST['estado_consulta']) ? $_POST['estado_consulta'] : '';

    if (empty($dni_medico) || empty($dni_paciente) || empty($tipo_consulta) || empty($descripcion) || empty($fecha) || empty($estado_consulta)) {
        echo "Todos los campos son obligatorios.";
    } else {
        $stmt = $conn->prepare("INSERT INTO consultas (id_medico, id_paciente, tipo_consulta, descripcion_consulta, fecha_consulta, estado_consulta) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $dni_medico, $dni_paciente, $tipo_consulta, $descripcion, $fecha, $estado_consulta);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error al crear la consulta: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
