<?php
// detalhes_nota.php

// Conexão com o banco de dados (exemplo com MySQL)
$servername = "localhost";
$username = "oficina";
$password = "K4rr1nh0";
$dbname = "oficina";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica se o ID da nota foi enviado via GET
if (isset($_GET['id'])) {
    $idNota = $_GET['id'];

    // Query para selecionar detalhes da nota de serviço pelo ID
    $sql = "SELECT * FROM notas_servico WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNota);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Retorna os dados como JSON
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Nota de serviço não encontrada']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID da nota não foi fornecido']);
}

$conn->close();
?>
