<?php
if (isset($_POST['id'])) {
    $id = trim($_POST['id']);
    $pdo = new PDO('pgsql:host=localhost;dbname=prueba', 'prueba', 'prueba');
    $sent = $pdo->prepare('DELETE FROM depart WHERE id = :id');
    $sent->execute([':id' => $id]);
}
setcookie('borrar', '1');
header('Location: index.php');
