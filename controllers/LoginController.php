<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    
    public static function login( Router $router){
      
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();
            
            if(empty($alertas)){
                //verificar que el usuarioo exista
                $usuario = Usuario::where('email',$usuario->email);
               
                if(!$usuario || $usuario->confirmado == 0){
                    Usuario::setAlerta('error','el usuario no existe o no esta confimado');
                }else{
                    //usuario existe
                    if( password_verify($_POST['password'], $usuario->password) ){
                        
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('location: /dashboard');
                    }else{
                        Usuario::setAlerta('error','la contraceña o el usuario no es valido');
                    }
                }
            }
       
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }
    
    
    //_---------------------------------------

    public static function logout( Router $router){
        if(empty($_SESSION)){
           session_start(); 
        }
        $_SESSION = [];
        header('location: /');
    }


    //--------------------------------------


    public static function crear( Router $router){
       
        $usuario = new Usuario();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();
            if(empty($alertas)){

                $existeUsaurio = Usuario::where('email', $usuario->email);
                if($existeUsaurio){
                    Usuario::setAlerta('error','El usuario ya esta registrado');
                }else{
                    //crear un nuevo usuario
                    $usuario->hashPassword();

                    //eliminar password 2 este metodo elimna propiedades de un objecto
                    unset($usuario->repetirPassword);

                    //generar token 
                    $usuario->crearToken();
                    $resultado = $usuario->guardar();

                    //enviar $email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfimacion();

                    if($resultado){
                        header('location: /mensaje');
                    }
                }
            }
    
        }
        $alertas = Usuario::getAlertas();
        
        $router->render('auth/crear',[
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    


    //-----------------------------------
    public static function olvide( Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$usuario->email);
               //debuguear($usuario);
                if($usuario && $usuario->confirmado === "1"){
                    //encontre el user

                   //generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->repetirPassword);
                   //actualizar el usuario
                    $usuario->guardar();
                   //enviar email
                    // $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                   
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstruciones();
                   
                   //imprimir la alerta
                   Usuario::setAlerta('exito','revisa tu email');
                }else{
                    Usuario::setAlerta('error','el usuario no existe o no esta confimado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide',[
            'titulo' => 'Recuperar Cuenta',
            'alertas' => $alertas
        ]);
    }



    //-----------------------------------
    public static function reestablecer( Router $router){
      
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('location: /');

        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','token no valido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
           
            $usuario->sincronizar($_POST);
            unset($usuario->repetirPassword);

            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                //hashear password
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                if($resultado){
                    header('location: /');
                }else{
                    Usuario::setAlerta('error','problemas al intentar reestablecer la contraseña intenta de nuevo');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer',[
            'titulo' => 'restablecer password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }



    //-----------------------------------
    public static function mensaje( Router $router){
        $router->render('auth/mensaje',[
            'titulo' => 'confirma tu cuenta'
        ]);
    
    }



    //-----------------------------------
    public static function confirmar( Router $router){


        $token = s($_GET['token']);
    
        if(!$token) header('location: /');

        //emcomntrar el usuario
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            //no se encontrpo usuario con ese token
            Usuario::setAlerta('error','Token no valido');
        }else{
            //confimar usuARIO
            $usuario->confirmado = 1;
            $usuario->tokem = null;
            unset($usuario->repetirPassword);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'cuenta confimada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar',[
            'titulo' => 'confirma tu cuenta',
            'alertas' => $alertas
        ]);
    
    }
}