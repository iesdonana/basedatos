<?php
session_start();

require '../comunes/auxiliar.php';

comprobar_admin();

if (!isset($_POST['csrf_token'])) {
    volver();
    return;
} elseif ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
    volver();
    return;
}
borrar_fila('emple');
volver();
