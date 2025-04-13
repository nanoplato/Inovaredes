<?php
session_start();
include 'db/connect.php';

$usuario_id = $_SESSION['usuario_id'];
$conteudo = $_POST['conteudo'];
$imagem_nome = "";

if (!empty($_FILES['imagem']['name'])) {
  $imagem_nome = uniqid() . "_" . $_FILES['imagem']['name'];
  move_uploaded_file($_FILES['imagem']['tmp_name'], "uploads/" . $imagem_nome);
}

$sql = "INSERT INTO posts (usuario_id, conteudo, imagem) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $usuario_id, $conteudo, $imagem_nome);
$stmt->execute();

header("Location: home.php");
?>