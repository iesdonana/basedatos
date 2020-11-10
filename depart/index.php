<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departamentos</title>
    <style>
        .borrar {
            display: inline;
        }
    </style>
</head>
<body>
    <?php
    require '/comunes/auxiliar.php';
    
    head();
    comprobar_logueado();
    
    if (isset($_SESSION['flash'])) {
        echo "<h3>{$_SESSION['flash']}</h3>";
        unset($_SESSION['flash']);
    }

    if (isset($_SESSION['favoritos'])) {   
        var_dump($_SESSION['favoritos']);
    }

    $dept_no = isset($_GET['dept_no']) ? trim($_GET['dept_no']) : '';
    ?>
    <form action="" method="get">
        <label for="dept_no">Número:</label>
        <input type="text" name="dept_no" id="dept_no" value="<?= $dept_no ?>">
        <button type="submit">Buscar</button>
    </form>
    <?php
    $pdo = conectar();
    // Validación del $dept_no
    if ($dept_no == '') {
        $sent = $pdo->query('SELECT COUNT(*) FROM depart');
        $count = $sent->fetchColumn();
        $sent = $pdo->query('SELECT * FROM depart');
    } else {
        $sent = $pdo->prepare("SELECT COUNT(*)
                                 FROM depart
                                WHERE dept_no = :dept_no");
        $sent->execute([':dept_no' => $dept_no]);
        $count = $sent->fetchColumn();
        $sent = $pdo->prepare("SELECT *
                                 FROM depart
                                WHERE dept_no = :dept_no");
        $sent->execute([':dept_no' => $dept_no]);
    }
    ?>
    <h3>La tabla tiene <?= $count ?> filas.</h3>
    <table border="1">
        <thead>
            <th>Núm. Dept.</th>
            <th>Nombre</th>
            <th>Localidad</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            <?php foreach ($sent as $fila):
                $id = $fila['id'] ?>
                <tr>
                    <td><?= $fila['dept_no'] ?></td>
                    <td><?= $fila['dnombre'] ?></td>
                    <td><?= $fila['loc'] ?></td>
                    <td>
                        <form action="borrar.php" method="post" class="borrar">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit">Borrar</button>
                        </form>
                        <a href="modificar.php?id=<?= $id ?>">Modificar</a>
                        <a href="agregar_favoritos.php?id=<?= $id ?>">
                            Añadir a favoritos
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <a href="insertar.php">Insertar un nuevo departamento</a>
</body>
</html>