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
    require '../comunes/auxiliar.php';
    banner();
    
    if (isset($_SESSION['flash'])) {
        echo "<h3>{$_SESSION['flash']}</h3>";
        unset($_SESSION['flash']);
    }

    if (isset($_SESSION['favoritos'])) {   
        var_dump($_SESSION['favoritos']);
    }

    $emp_no = isset($_GET['emp_no']) ? trim($_GET['emp_no']) : '';
    ?>
    <form action="" method="get">
        <label for="emp_no">Número:</label>
        <input type="text" name="emp_no" id="emp_no" value="<?= $emp_no ?>">
        <button type="submit">Buscar</button>
    </form>
    <?php
    $pdo = conectar();
    $query = 'SELECT e.*,
                     dept_no, dnombre,
                     j.emp_no AS j_emp_no, j.apellidos AS j_apellidos
                FROM emple e
                JOIN depart d
                  ON e.depart_id = d.id
           LEFT JOIN emple j
                  ON e.jefe_id = j.id';
    // Validación del $emp_no:
    if ($emp_no == '') {
        $sent = $pdo->query('SELECT COUNT(*) FROM emple');
        $count = $sent->fetchColumn();
        $sent = $pdo->query($query);
    } else {
        $sent = $pdo->prepare("SELECT COUNT(*)
                                 FROM emple
                                WHERE emp_no = :emp_no");
        $sent->execute([':emp_no' => $emp_no]);
        $count = $sent->fetchColumn();
        $sent = $pdo->prepare("$query WHERE e.emp_no = :emp_no");
        $sent->execute([':emp_no' => $emp_no]);
    }
    ?>
    <h3>La tabla tiene <?= $count ?> filas.</h3>
    <table border="1">
        <thead>
            <th>Núm. Emple.</th>
            <th>Apellidos</th>
            <th>Salario</th>
            <th>Comisión</th>
            <th>Fecha alta</th>
            <th>Oficio</th>
            <th>Jefe</th>
            <th>Departamento</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            <?php foreach ($sent as $fila):
                extract($fila);
                // Formateado de datos:
                if ($fecha_alt != '') {
                    $fecha_alt_fmt = new DateTime($fecha_alt);
                    $fecha_alt_fmt->setTimezone(new DateTimeZone('Europe/Madrid'));
                    $fecha_alt_fmt = $fecha_alt_fmt->format('d-m-Y');
                } else {
                    $fecha_alt_fmt = null;
                }
                $fmt = new NumberFormatter('es_ES', NumberFormatter::CURRENCY);
                $salario_fmt = $fmt->formatCurrency($salario, 'EUR');
                if ($comision != '') {
                    $comision_fmt = $fmt->formatCurrency($comision, 'EUR');
                } else {
                    $comision_fmt = null;
                }
                $jefe_fmt = isset($j_emp_no) ? "($j_emp_no) $j_apellidos" : '';
                $depart_fmt = "($dept_no) $dnombre";
                ?>
                <tr>
                    <td><?= $emp_no ?></td>
                    <td><?= $apellidos ?></td>
                    <td><?= $salario_fmt ?></td>
                    <td><?= $comision_fmt ?></td>
                    <td><?= $fecha_alt_fmt ?></td>
                    <td><?= $oficio ?></td>
                    <td><?= $jefe_fmt ?></td>
                    <td><?= $depart_fmt ?></td>
                    <td>
                        <form action="/emple/borrar.php" method="post" class="borrar">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit">Borrar</button>
                        </form>
                        <a href="/emple/modificar.php?id=<?= $id ?>">Modificar</a>
                        <a href="agregar_favoritos.php?id=<?= $id ?>">
                            Añadir a favoritos
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <a href="insertar.php">Insertar un nuevo empleado</a>
</body>
</html>