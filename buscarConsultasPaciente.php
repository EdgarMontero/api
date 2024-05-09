<?php
include 'db.php';

    $dni_paciente = $_POST['dni_paciente'];

    $stmt = $conn->prepare("SELECT id_consulta, id_medico, id_paciente, tipo_consulta, descripcion_consulta, fecha_consulta FROM consultas WHERE id_paciente = ?");
    $stmt->bind_param("s", $dni_paciente);
    $stmt->execute();
    $result = $stmt->get_result();
    $consultas = [];

    while ($row = $result->fetch_assoc()) {
        $consultas[] = $row;
    }

    echo json_encode($consultas);
    $stmt->close();
    $conn->close();

