<?php

function cookies()
{
    if (isset($_COOKIE['borrar'])) {
        setcookie('borrar', '', 1); ?>
        <h3>La fila se ha borrado correctamente.</h3><?php
    }
}

function banner()
{
    if (!isset($_COOKIE['acepta_cookies'])): ?>
        <h2>
            Este sitio usa cookies.
            <a href="cookies.php">Aceptar</a>
        </h2><?php
    endif;
}

function conectar()
{
    $pdo = new PDO('pgsql:host=localhost;dbname=prueba', 'prueba', 'prueba');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function volver()
{
    header('Location: index.php');
}

function error($mensaje)
{ ?>
    <h3><?= $mensaje ?></h3><?php
    return true;
}

function existe_dept_no($dept_no, $pdo)
{
    $sent = $pdo->prepare('SELECT COUNT(*)
                             FROM depart
                            WHERE dept_no = :dept_no');
    $sent->execute(['dept_no' => $dept_no]);
    return $sent->fetchColumn() != 0;
}

function existe_emp_no($emp_no, $pdo)
{
    $sent = $pdo->prepare('SELECT COUNT(*)
                             FROM emple
                            WHERE emp_no = :emp_no');
    $sent->execute(['emp_no' => $emp_no]);
    return $sent->fetchColumn() != 0;
}

function existe_dept_no_otra_fila($dept_no, $id, $pdo)
{
    $sent = $pdo->prepare('SELECT COUNT(*)
                             FROM depart
                            WHERE dept_no = :dept_no
                              AND id != :id');
    $sent->execute(['dept_no' => $dept_no, 'id' => $id]);
    return $sent->fetchColumn() != 0;
}

function mostrar_errores($error)
{
    foreach ($error as $k => $v) {
        foreach ($v as $mensaje) {
            echo "<h3>$mensaje</h3>";
        }
    }
}

function cancelar()
{ ?>
    <a href="index.php">Volver</a><?php
}

function recoger($tipo, $nombre)
{
    return filter_input($tipo, $nombre, FILTER_CALLBACK, [
        'options' => 'trim'
    ]);
}

function recoger_get($nombre)
{
    return recoger(INPUT_GET, $nombre);
}

function recoger_post($nombre)
{
    return isset($_POST[$nombre]) ? trim($_POST[$nombre]) : null;
    return recoger(INPUT_POST, $nombre);
}
