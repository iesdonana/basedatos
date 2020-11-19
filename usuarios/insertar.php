<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar un nuevo usuario</title>
</head>
<body>
    <?php
    require '../comunes/auxiliar.php';

    head();
    comprobar_admin();

    $login = recoger_post('login');
    $password = recoger_post('password');
    $password_confirm = recoger_post('password_confirm');

    $pdo = conectar();

    if (isset($login, $password, $password_confirm)) {
        // Validación y saneado de la entrada:
        $error_vacio = [
            'login' => [],
            'password' => [],
            'password_confirm' => [],
        ];
        $error = $error_vacio;
        if ($login == '') {
            $error['login'][] = 'El nombre del usuario no puede ser vacío.';
        } else {
            if (mb_strlen($login) > 255) {
                $error['login'][] = 'El nombre del usuario es demasiado grande.';
            } else {
                $sent = $pdo->prepare('SELECT *
                                         FROM usuarios
                                        WHERE login = :login');
                $sent->execute(['login' => $login]);
                if ($sent->fetch() !== false) {
                    $error['login'][] = 'El usuario ya existe.';
                }
            }
        }
        if ($password == '' || $password_confirm == '') {
            $error['password'][] = 'Las contraseñas son obligatorias.';
        } else {
            if ($password != $password_confirm) {
                $error['password'][] = 'Las contraseñas no coinciden.';
            }
        }
        // Insertar la fila:
        if ($error === $error_vacio) {
            try {
                $sent = $pdo->prepare('INSERT INTO usuarios (login, password)
                                       VALUES (:login, :password)');
                $sent->execute([
                    'login' => $login,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
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
            <label for="login">Nombre de usuario:</label>
            <input type="text" name="login" id="login"
                   value="<?= $login ?>">
        </p>
        <p>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password">
        </p>
        <p>
            <label for="password_confirm">Confirmar contraseña:</label>
            <input type="password" name="password_confirm"
                   id="password_confirm">
        </p>
        <button type="submit">Insertar</button>
        <?php cancelar() ?>
    </form>
    
</body>
</html>