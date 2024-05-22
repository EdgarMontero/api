<?php
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$type = $_POST['type']; 

if ($type == 'medico') {
    $stmt = $conn->prepare("SELECT users.password, medicos.dni_medico FROM users 
                            JOIN medicos ON users.id_user = medicos.user_id 
                            WHERE name = ?");
} elseif ($type == 'paciente') {
    $stmt = $conn->prepare("SELECT users.password, pacientes.dni_paciente FROM users 
                            JOIN pacientes ON users.id_user = pacientes.user_id 
                            WHERE name = ?");
} else {
    echo "Invalid type";
    $conn->close();
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        if ($type == 'medico') {
            echo "Login success," . $row['dni_medico'];
        } elseif ($type == 'paciente') {
            echo "Login success," . $row['dni_paciente'];
        }
    } else {
        echo "Login failed";
    }
} else {
    echo "Login failed";
}

$stmt->close();
$conn->close();
?>
