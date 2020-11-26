<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
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
    
    comprobar_logueado();
    
    if (isset($_SESSION['flash'])) {
        echo "<h3>{$_SESSION['flash']}</h3>";
        unset($_SESSION['flash']);
    }
    
    if (isset($_SESSION['favoritos'])) {   
        var_dump($_SESSION['favoritos']);
    }
    
    $columna = recoger_get('columna') ?? 'dept_no';
    $criterio = recoger_get('criterio');
    $pag = recoger_get('pag') ?? 1;
    ?>
    <div class="container">
        <?php head() ?>
        <div class="row mt-4">
            <div class="col">
                <form action="" method="get">
                    <div class="form-group">
                        <select name="columna" id="columna" class="form-control">
                            <option value="dept_no"
                                    <?= selected($columna, 'dept_no') ?>>
                                Número
                            </option>
                            <option value="dnombre"
                                    <?= selected($columna, 'dnombre') ?>>
                                Nombre
                            </option>
                        </select>
                        <input type="text" class="form-control" name="criterio" id="criterio" value="<?= $criterio ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>
        <?php
        $pdo = conectar();
        $limit = FPP;
        $offset = FPP * ($pag - 1);

        if ($criterio == '') {
            $sent = $pdo->query('SELECT COUNT(*) FROM depart');
            $nfilas = $sent->fetchColumn();
            $npags = ceil($nfilas / FPP);
            $sent = $pdo->query("SELECT *
                                    FROM depart
                                ORDER BY dept_no
                                   LIMIT $limit
                                  OFFSET $offset");
        } else {
            if ($columna == 'dept_no') {
                $sent = $pdo->prepare('SELECT COUNT(*)
                                         FROM depart
                                        WHERE dept_no::text = :dept_no');
                $sent->execute(['dept_no' => $criterio]);
                $nfilas = $sent->fetchColumn();
                $npags = ceil($nfilas / FPP);
                $sent = $pdo->prepare("SELECT *
                                         FROM depart
                                        WHERE dept_no::text = :dept_no
                                     ORDER BY dept_no
                                        LIMIT $limit
                                       OFFSET $offset");
                $sent->execute(['dept_no' => $criterio]);
            } elseif ($columna == 'dnombre') {
                $sent = $pdo->prepare('SELECT COUNT(*)
                                       FROM depart
                                      WHERE upper(dnombre) LIKE upper(:dnombre)');
                $sent->execute(['dnombre' => "%$criterio%"]);
                $nfilas = $sent->fetchColumn();
                $npags = ceil($nfilas / FPP);
                $sent = $pdo->prepare("SELECT *
                                         FROM depart
                                        WHERE upper(dnombre) LIKE upper(:dnombre)
                                     ORDER BY dnombre
                                        LIMIT $limit
                                       OFFSET $offset");
                $sent->execute(['dnombre' => "%$criterio%"]);
            }
        }
        ?>
        <div class="row mt-3">
            <div class="col">
                La tabla tiene <?= $nfilas ?> filas.
                <table class="table table-striped table-bordered">
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
                                        <button type="submit" class="btn btn-danger btn-sm" >Borrar</button>
                                    </form>
                                    <a href="modificar.php?id=<?= $id ?>" class="btn btn-info btn-sm">Modificar</a>
                                    <a href="agregar_favoritos.php?id=<?= $id ?>">
                                        Añadir a favoritos
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php paginador($pag, $npags, "&columna=$columna&criterio=$criterio") ?>
        <div class="row">
            <div class="col">
                <a href="insertar.php">Insertar un nuevo departamento</a>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </div>
</body>
</html>