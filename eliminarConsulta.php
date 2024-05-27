<?php

include 'db.php';

if (isset($_POST['id_consulta'])) {
    $id_consulta = $_POST['id_consulta'];

    $stmt = $conn->prepare("DELETE FROM consultas WHERE id_consulta = ?");
    $stmt->bind_param("i", $id_consulta);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Consulta eliminada"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo eliminar la consulta"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID de consulta no proporcionado"]);
}

$conn->close();
