<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar un nuevo empleado</title>
</head>
<body>
    <?php
    require '../comunes/auxiliar.php';

    const PAR = [
        'emp_no'=> [
            'tipo' => TIPO_DIGITOS,
            'obligatorio' => true,
            'etiqueta' => 'Número',
            'max' => 4,
        ],
        'apellidos' => [
            'tipo' => TIPO_TEXTO,
            'obligatorio' => true,
            'etiqueta' => 'Apellidos',
            'max' => 255,
        ],
        'salario' => [
            'tipo' => TIPO_NUMERO,
            'obligatorio' => true,
            'etiqueta' => 'Salario',
            'max' => 999999.99,
        ],
        'comision' => [
            'tipo' => TIPO_NUMERO,
            'obligatorio' => false,
            'etiqueta' => 'Comisión',
            'max' => 999999.99,
        ],
        'fecha_alt' => [
            'tipo' => TIPO_FECHA,
            'obligatorio' => false,
            'etiqueta' => 'Fecha de alta',
        ],
        'oficio' => [
            'tipo' => TIPO_TEXTO,
            'obligatorio' => false,
            'etiqueta' => 'Oficio',
            'max' => 255,
        ],
        'jefe_id' => [
            'tipo' => TIPO_DIGITOS,
            'obligatorio' => false,
            'etiqueta' => 'Jefe',
            'relacion' => [
                'tabla' => 'emple',
                'columna' => 'id',
                'visualizar' => 'apellidos',
            ],
        ],
        'depart_id' => [
            'tipo' => TIPO_DIGITOS,
            'obligatorio' => true,
            'etiqueta' => 'Departamento',
            'relacion' => [
                'tabla' => 'depart',
                'columna' => 'id',
                'visualizar' => 'dnombre',
            ],
        ],   
    ];

    foreach (PAR as $k => $v) {
        $$k = recoger_post($k);
        $tmp = $k . '_fmt';
        $$tmp = $$k;
    }

    unset($tmp);

    $pdo = conectar();
    
    $existen = true;

    foreach (PAR as $k => $v) {
        if (!isset($$k)) {
            $existen = false;
            break;
        }
    }

    if ($existen) {
        // Validación y saneado de la entrada:
        $error_vacio = [];
        foreach (PAR as $k => $v) {
            $error_vacio[$k] = [];
        }
        $error = $error_vacio;
        if ($emp_no == '') {
            $error['emp_no'][] = 'El número es obligatorio.';
        } else {
            if (!ctype_digit($emp_no)) {
                $error['emp_no'][] = 'El número debe contener sólo dígitos.';
            } else {
                if ($emp_no > 9999) {
                    $error['emp_no'][] = 'El número debe tener máximo 4 dígitos';
                } else {
                    if (existe_emp_no($emp_no, $pdo)) {
                        $error['emp_no'][] = 'Ese número ya existe';
                    }
                }
            }
        }
        if ($apellidos == '') {
            $error['apellidos'][] = 'Los apellidos son obligatorios';
        } else {
            if (mb_strlen($apellidos) > 255) {
                $error['apellidos'][] = 'Los apellidos exceden la longitud máxima';
            }
        }
        if ($salario == '') {
            $error['salario'][] = 'El salario es obligatorio';
        } else {
            if (!is_numeric($salario)) {
                $error['salario'][] = 'El salario debe ser un número';
            } else {
                $salario = round($salario, 2);
                if (abs($salario) > 999999.99) {
                    $error['salario'][] = 'El salario es demasiado grande o pequeño';
                }
            }
        }
        if ($comision == '') {
            $comision = null;
        } else {
            if (!is_numeric($comision)) {
                $error['comision'][] = 'La comision debe ser un número';
            } else {
                $comision = round($comision, 2);
                if (abs($comision) > 999999.99) {
                    $error['comision'][] = 'La comision es demasiado grande o pequeño';
                }
            }
        }
        if ($fecha_alt != '') {
            $matches = [];
            if (!preg_match(
                '/^(\d\d)-(\d\d)-(\d{4})$/',
                $fecha_alt, $matches
            )) {
                $error['fecha_alt'][] = 'El formato de la fecha no es válido';
            } else {
                $dia = $matches[1];
                $mes = $matches[2];
                $anyo = $matches[3];
                if (!checkdate($mes, $dia, $anyo)) {
                    $error['fecha_alt'][] = 'La fecha es incorrecta';
                } else {
                    $fecha_alt = "$anyo-$mes-$dia";
                }
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
        <?php foreach (PAR as $k => $v):
            $tmp = $k . '_fmt'; ?>
            <p>
                <label for="<?= $k ?>"><?= $v ?>: </label>
                <input type="text" name="<?= $k ?>" id="<?= $k ?>"
                   value="<?= $$tmp ?>">
            </p>
        <?php endforeach ?>
        <button type="submit">Insertar</button>
        <?php cancelar() ?>
    </form>
</body>
</html>