<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_consulta = isset($_POST['id_consulta']) ? $_POST['id_consulta'] : '';
    $tipo_consulta = isset($_POST['tipo_consulta']) ? $_POST['tipo_consulta'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

    if (empty($id_consulta) || empty($tipo_consulta) || empty($descripcion) || empty($fecha)) {
        echo "Todos los campos son obligatorios.";
    } else {
        $stmt = $conn->prepare("UPDATE consultas SET tipo_consulta = ?, descripcion_consulta = ?, fecha_consulta = ? WHERE id_consulta = ?");
        $stmt->bind_param("ssss", $tipo_consulta, $descripcion, $fecha, $id_consulta);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Consulta actualizada con éxito"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar la consulta: " . $stmt->error]);
        }

        $stmt->close();
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Método de solicitud no válido"]);
}
