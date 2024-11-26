<?php include '../Modelo/conexion/conexion.php';?>

<?php

    session_start();

    $Usuario = $_POST['usuario'];
    $password = $_POST['contraseña'];

    if (strpos($Usuario, '@') !== false) {
        // Inicia sesión con correo
        $sql = "SELECT * FROM usuarios WHERE correo = '$Usuario' AND contrasena = '$password'";
        $execute = mysqli_query($conexion, $sql);
        $row = mysqli_fetch_assoc($execute);

        if(($row['correo']==$Usuario)&&($row['contrasena']==$password)){
            $Usuario = $row['usuario'];
            $Rol = $row['rol'];
            $_SESSION['login_success'] = true;
            if(($Rol == '1')){
                $_SESSION['usuario']=$Usuario;
                    header("Location: ../Vista/IndexAdmin.php");
            }
            else if(($Rol == '2')){
                $_SESSION['usuario']=$Usuario;
                    header("Location: ../Vista/IndexGerente.php");
            }
            else{
                $_SESSION['usuario']=$Usuario;
                    header("Location: ../Vista/Index.php");
            }
        }else{
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "Usuario y/0 contraseña incorrectos";
            header("Location: ../Vista/Iniciosesion.php");
        }
    
    } else {
        // Inicia sesión con nombre de usuario
        $sql = "SELECT * FROM usuarios WHERE usuario = '$Usuario' AND contrasena = '$password'";
        $execute = mysqli_query($conexion, $sql);
        $row = mysqli_fetch_assoc($execute);

        if(($row['usuario']==$Usuario)&&($row['contrasena']==$password)){
            $Rol = $row['rol'];
            $_SESSION['login_success'] = true;
            if(($Rol == '1')){
                $_SESSION['usuario']=$Usuario;
                    header("Location: ../Vista/IndexAdmin.php");
            }
            else if(($Rol == '2')){
                $_SESSION['usuario']=$Usuario;
                    header("Location: ../Vista/IndexGerente.php");
            }
            else{
                $_SESSION['usuario']=$Usuario;
                    header("Location: ../Vista/Index.php");
            }
        }else{
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "Usuario y/0 contraseña incorrectos";
            header("Location: ../Vista/Iniciosesion.php");
        }
    
    }

?>
