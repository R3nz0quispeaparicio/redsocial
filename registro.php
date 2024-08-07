<?php
session_start();
include 'lib/config.php';

ini_set('error_reporting', 0);

if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $repcontrasena = $_POST['repcontrasena'];

        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) AS num FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['num'] > 0) {
            ?>
            <br>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                El nombre de usuario está en uso, por favor escoja otro
            </div>
            <?php
        } else {
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT COUNT(*) AS num FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['num'] > 0) {
                ?>
                <br>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    El email ya está en uso por favor escoja otro o verifique si tiene una cuenta
                </div>
                <?php
            } else {
                // Verificar si las contraseñas coinciden
                if ($contrasena != $repcontrasena) {
                    ?>
                    <br>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Las contraseñas no coinciden
                    </div>
                    <?php
                } else {
                    // Insertar nuevo usuario
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, usuario, contrasena, fecha_reg) VALUES (:nombre, :email, :usuario, :contrasena, NOW())");
                    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                    $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
                    $stmt->execute();

                    ?>
                    <br>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Felicidades se ha registrado correctamente
                    </div>
                    <?php

                    header("Refresh: 2; url=https://app-c32fa0d2-8e26-4be6-8c30-fabe657b1315.cleverapps.io/login.php");
                    exit;
                }
            }
        }
    }
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registro - Red Social</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href=""><b>RED</b>SOCIAL</a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Regístrate en REDSOCIAL</p>

        <form action="" method="post">
            <div class="form-group has-feedback">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>" required>
                <span class="glyphicon glyphicon-star form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" name="usuario" class="form-control" placeholder="Usuario" value="<?php echo isset($_POST['usuario']) ? $_POST['usuario'] : ''; ?>" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="repcontrasena" class="form-control" placeholder="Repita la contraseña" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-10">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="check" required> Acepto los <a href="#">términos y condiciones</a>
                        </label>
                    </div>
                </div>
                <div class="col-xs-12">
                    <button type="submit" name="registrar" class="btn btn-primary btn-block btn-flat">Registrarme</button>
                </div>
            </div>
        </form>

        <br>
        <a href="https://app-c32fa0d2-8e26-4be6-8c30-fabe657b1315.cleverapps.io/login.php" class="text-center">Tengo actualmente una cuenta</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
