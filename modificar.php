<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar un nuevo departamento</title>
</head>
<body>
    <?php
    require './auxiliar.php';

    $dept_no = recoger_post('dept_no');
    $dnombre = recoger_post('dnombre');
    $loc = recoger_post('loc');
    $id = recoger_get('id');

    $pdo = conectar();

    if (isset($dept_no, $dnombre, $loc, $id)) {
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
                    if (existe_dept_no_otra_fila($dept_no, $id, $pdo)) {
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
                $sent = $pdo->prepare('UPDATE depart
                                          SET dept_no = :dept_no
                                            , dnombre = :dnombre
                                            , loc = :loc
                                        WHERE id = :id');
                $sent->execute([
                    'dept_no' => $dept_no,
                    'dnombre' => $dnombre,
                    'loc' => $loc,
                    'id' => $id,
                ]);
                $_SESSION['flash'] = 'La fila se ha modificado correctamente.';
                volver();
            } catch (PDOException $e) {
                error('No se ha podido modificar la fila.');
            }
        } else {
            mostrar_errores($error);
        }
    } else {
        if (isset($id)) {
            $sent = $pdo->prepare('SELECT * FROM depart WHERE id = :id');
            $sent->execute(['id' => $id]);
            $fila = $sent->fetch();
            if ($fila === false) {
                volver();
            } else {
                $dept_no = $fila['dept_no'];
                $dnombre = $fila['dnombre'];
                $loc = $fila['loc'];
            }
        } else {
            volver();
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
        <button type="submit">Modificar</button>
        <?php cancelar() ?>
    </form>
</body>
</html>