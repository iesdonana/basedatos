<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar un nuevo departamento</title>
</head>
<body>
    <?php
    require '../comunes/auxiliar.php';

    encabezado();

    $dept_no = isset($_POST['dept_no']) ? trim($_POST['dept_no']) : null;
    $dnombre = isset($_POST['dnombre']) ? trim($_POST['dnombre']) : null;
    $loc = isset($_POST['loc']) ? trim($_POST['loc']) : null;

    $pdo = conectar();
    
    if (isset($dept_no, $dnombre, $loc)) {
        // Validación y saneado de la entrada:
        $error_vacio = [
            'dept_no' => [],
            'dnombre' => [],
            'loc' => [],
        ];
        $error = $error_vacio;
        if ($dept_no == '') {
            $error['dept_no'][] = 'El número es obligatorio.';
        } else {
            if (!ctype_digit($dept_no)) {
                $error['dept_no'][] = 'El número no tiene un formato correcto.';
            } else {
                if ($dept_no > 99) {
                    $error['dept_no'][] = 'El número no puede ser mayor de 99.';
                } else {
                    if (existe_dept_no($dept_no, $pdo)) {
                        $error['dept_no'][] = 'Ese departamento ya existe.';
                    }
                }
            }
        }
        if ($dnombre == '') {
            $error['dnombre'][] = 'El nombre es obligatorio.';
        } else {
            if (mb_strlen($dnombre) > 256) {
                $error['dnombre'][] = 'El nombre es demasiado largo.';
            }
        }
        if ($loc == '') {
            $loc = null;
        } else {
            if (mb_strlen($loc) > 256) {
                $error['loc'][] = 'La localidad es demasiado larga.';
            }
        }
        // Insertar la fila:
        if ($error === $error_vacio) {
            try {
                $sent = $pdo->prepare('INSERT INTO depart (dept_no, dnombre, loc)
                                       VALUES (:dept_no, :dnombre, :loc)');
                $sent->execute([
                    'dept_no' => $dept_no,
                    'dnombre' => $dnombre,
                    'loc' => $loc,
                ]);
                $_SESSION['flash'] = 'La fila se ha insertado correctamente.';
                volver();
            } catch (PDOException $e) {
                error('No se ha podido insertar la fila.');
            }
        } else {
            mostrar_errores($error);
        }
    }
    ?>
    <form action="" method="post">
        <p>
            <label for="dept_no">Número:</label>
            <input type="text" name="dept_no" id="dept_no"
                   value="<?= $dept_no ?>">
        </p>
        <p>
            <label for="dnombre">Nombre:</label>
            <input type="text" name="dnombre" id="dnombre"
                   value="<?= $dnombre ?>">
        </p>
        <p>
            <label for="loc">Localidad:</label>
            <input type="text" name="loc" id="loc"
                   value="<?= $loc ?>">
        </p>
        <button type="submit">Insertar</button>
        <?php cancelar() ?>
    </form>
</body>
</html>