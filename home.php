<?php
session_start();
include 'db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $usuario = $result->fetch_assoc();

  if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
  } else {
    echo "Login inválido";
    exit;
  }
}

if (!isset($_SESSION['usuario_id'])) {
  header("Location: index.html");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>InovaRede - Feed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .navbar {
      background-color: #6610f2;
    }
    .navbar-brand, .nav-link, .text-white {
      color: #fff !important;
    }
    .card {
      border-radius: 15px;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark px-4">
  <a class="navbar-brand" href="#">InovaRede</a>
  <div class="ms-auto">
    <span class="text-white me-3">Olá, <?php echo $_SESSION['nome']; ?></span>
    <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
  </div>
</nav>

<div class="container mt-4">
  <form action="post.php" method="post" enctype="multipart/form-data" class="mb-4">
    <div class="card p-3">
      <textarea name="conteudo" class="form-control mb-2" placeholder="Compartilhe algo..." required></textarea>
      <input type="file" name="imagem" class="form-control mb-2">
      <button type="submit" class="btn btn-primary">Postar</button>
    </div>
  </form>

  <h4>Últimas postagens</h4>
  <?php
  $sql = "SELECT p.*, u.nome FROM posts p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.data_postagem DESC";
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<h5>{$row['nome']}</h5>";
    echo "<p>{$row['conteudo']}</p>";
    if ($row['imagem']) {
      echo "<img src='uploads/{$row['imagem']}' class='img-fluid rounded mb-2'>";
    }
    echo "<small class='text-muted'>{$row['data_postagem']}</small>";
    echo "</div></div>";
  }
  ?>
</div>
</body>
</html>