<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Usuarios</title>
</head>
<body>
    <?php
    require '../comunes/auxiliar.php';

    head();
    comprobar_admin();

    $pdo = conectar();
    $sent = $pdo->query('SELECT * FROM usuarios ORDER BY login');
    ?>
    <table border="1">
        <thead>
            <th>Nombre de usuario</th>
            <th colspan="2">Acciones</th>
        </thead>
        <tbody>
            <?php foreach ($sent as $fila): ?>
                <tr>
                    <td><?= hh($fila['login']) ?></td>
                    <td>
                        <form action="borrar.php" method="post">
                            <input type="hidden" name="id"
                                   value="<?= hh($fila['id']) ?>">
                            <button type="submit">Borrar</button>
                        </form>
                    </td>
                    <td>
                        <form action="modificar.php" method="get">
                            <input type="hidden" name="id"
                                   value="<?= hh($fila['id']) ?>">
                            <button type="submit">Modificar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <a href="insertar.php">Insertar un nuevo usuario</a>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>