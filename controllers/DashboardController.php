<?php
namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {
    public static function index(Router $router){
       
        session_start();
        $nombre = $_SESSION['nombre'] ?? '';

        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietario_id',$id);

       $router->render('dashboard/index',[
           'nombre' => $nombre,
           'titulo' => 'proyectos',
           'proyectos' => $proyectos
       ]);
    }

//--------------------------------
    public static function crear_proyecto(Router $router)
    {
        isAuth();
        $nombre = $_SESSION['nombre'] ?? '';
        $alertas = [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
           
            if(empty($alertas)){
                //guardar Proyecto
                $proyecto->url = md5( uniqid());
                $proyecto->propietario_id =  $_SESSION['id'];
               $proyecto->guardar();
                header('location: /proyecto?id='.$proyecto->url);

            }
        }


        $router->render('dashboard/crear-proyecto',[
            'nombre' => $nombre,
            'titulo' => 'crear proyecto',
            'alertas' => $alertas
        ]);
    }
///####################################################################


    public static function proyecto(Router $router)
    {
        isauth();
        $nombre = $_SESSION['nombre'] ?? '';
        $alertas = [];

        $token = s($_GET['id']) ?? null;
        if(!$token) header('location: /dashboard');

        $proyecto = Proyecto::where('url',$token);

        if(!$proyecto){
            Proyecto::setAlerta('error','proyecto no encontrado');
        }else{
            if($proyecto->propietario_id !== $_SESSION['id']){
                header('location: /dashboard');
            }else{

            }
        }

        $alertas = Proyecto::getAlertas();

        $router->render('dashboard/proyecto',[
            'nombre' => $nombre,
            'titulo' => $proyecto->proyecto,
            'alertas' => $alertas
        ]);
    }
//-----------------------------------
    public static function perfil(Router $router)
    {
        isAuth();
        $nombre = $_SESSION['nombre'] ?? '';
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
           
           $alertas = $usuario->validarPerfil();

           if(empty($alertas)){
               $existeUsuario = Usuario::where('email', $usuario->email);

               if($existeUsuario && $existeUsuario->id !== $usuario->id){

                    Usuario::setAlerta('error','El email ya esta registrado use otro');

               }else{
                   $usuario->guardar();
                    Usuario::setAlerta('exito','guardado con exito');
    
                   $_SESSION['nombre'] = $usuario->nombre;
               }
           }
        }
        $alertas = Usuario::getAlertas();

        $router->render('dashboard/perfil',[
            'nombre' => $nombre,
            'titulo' => 'perfil',
            'usuario'=> $usuario,
            'alertas' => $alertas
        ]);
    }


    //**   ------------------------------ */

    public static function cambiar_password(Router $router){

        isAuth();
        $nombre = $_SESSION['nombre'] ?? '';
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();
           
            if(empty($alertas)){
                $resultado =  $usuario->comprobar_password();
                
                if($resultado){
                    $usuario->password = $usuario->password_nuevo;

                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();
                    $resultado =   $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito','Password Cambiada con exito');
                    }else{
                        Usuario::setAlerta('error','Al Cambiar el Password');
                    }
                }else{
                    Usuario::setAlerta('error','Error al Verificar El password Actual');
                }
            }
        }

        $alertas = Usuario::getAlertas();


        $router->render('dashboard/cambiar-password',[
            'nombre' => $nombre,
            'titulo' => 'perfil',
            'alertas' => $alertas
        ]);
    }
}
