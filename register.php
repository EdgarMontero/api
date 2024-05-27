<?php

include 'db.php';

header('Content-Type: application/json');  

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(['message' => 'Todos los campos son requeridos.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['message' => 'El nombre de usuario ya está en uso.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['message' => 'El correo electrónico ya está en uso.']);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("INSERT INTO users (name, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $passwordHash, $email);
    if ($stmt->execute()) {
        $user_id = $conn->insert_id;  
        echo json_encode(['message' => 'Usuario registrado exitosamente.', 'user_id' => $user_id]);
    } else {
        echo json_encode(['message' => 'Error al registrar el usuario: ' . $stmt->error]);
    }
} catch (Exception $e) {
    echo json_encode(['message' => "Error al registrar el usuario: " . $e->getMessage()]);
}
