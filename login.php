<?php
$username = $_POST['username'];
$password = $_POST['password'];

// Conectar a la base de datos
$conn = new mysqli('localhost', 'cfgs', 'ira491', 'proyecto');

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar consulta SQL para obtener la contraseña hasheada y el dni_medico
$stmt = $conn->prepare("SELECT users.password, medicos.dni_medico FROM users 
                        JOIN medicos ON users.id_user = medicos.user_id 
                        WHERE name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        echo "Login success," . $row['dni_medico'];
    } else {
        echo "Login failed";
    }
} else {
    echo "Login failed";
}

$stmt->close();
$conn->close();
?>