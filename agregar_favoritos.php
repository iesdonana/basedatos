<?php
session_start();

require './auxiliar.php';

if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    if (!isset($_SESSION['favoritos'])) {
        $_SESSION['favoritos'] = [];
    }
    if (!in_array($id, $_SESSION['favoritos'])) {
        $_SESSION['favoritos'][] = $id;
    }
}
volver();
