<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
</html>