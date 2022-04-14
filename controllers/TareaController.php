<?php
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;
use MVC\Router;

class TareaController {
    public static function index(Router $router)
    {
        isAuth();
        $proyectoId = s($_GET['id']);

        if(!$proyectoId) header('location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);

        if(!$proyecto || $proyecto->propietario_id !== $_SESSION['id']) header('location: /404');
        
        $tarea = Tarea::belongsTo('proyecto_id', $proyecto->id);
        echo json_encode($tarea);
    }


    public static function crear()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $respuesta = $_SESSION;
            $proyectoId = s($_POST['proyecto_id']);

            $proyecto = Proyecto::where('url', $proyectoId);

            if(!$proyecto || $proyecto->propietario_id !== $_SESSION['id']){
                $respuesta = [
                    'tipo' =>'error',
                    'mensaje' => 'hubo un Error al agregar la tarea'
                ];
            }else{
                
                $tarea = new Tarea($_POST);
                $tarea->proyecto_id = $proyecto->id;
                $resultado = $tarea->guardar();
    
                if(!$resultado){
                    $respuesta = [
                        'tipo' =>'error',
                        'mensaje' => 'hubo un Error al agregar la tarea'
                    ];
                }else{
                    $respuesta = [
                    'tipo' =>'exito',
                    'mensaje' => 'Tarea Agregada Con Exito'
                    ];
                }
            }

            echo json_encode($respuesta);
        }
    }


    public static function actualizar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $proyectoId = s($_POST['proyecto_id']);
            $proyecto = Proyecto::where('url', $proyectoId);

            
            if(!$proyecto || $proyecto->propietario_id !== $_SESSION['id']){
                $respuesta = [
                    'tipo' =>'error',
                    'mensaje' => 'hubo un Error al agregar la tarea'
                ];
            }else{
                $tarea = new Tarea($_POST);
                $tarea->proyecto_id = $proyecto->id;
                $resultado =$tarea->guardar();

                if(!$resultado){
                    $respuesta = [
                        'tipo' =>'error',
                        'mensaje' => 'hubo un Error al agregar la tarea'
                    ];
                }else{
                    $respuesta = [
                    'tipo' =>'exito',
                    'tarea'=> $tarea,
                    'mensaje' => 'Tarea Actualizada Con Exito'
                    ];
                }
            }

            echo json_encode($respuesta);
        }
    }


    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $respuesta = [];


            $proyectoId = s($_POST['proyecto_id']);
            $proyecto = Proyecto::where('url', $proyectoId);

            
            if(!$proyecto || $proyecto->propietario_id !== $_SESSION['id']){
                $respuesta = [
                    'tipo' =>'error',
                    'mensaje' => 'hubo un Error al agregar la tarea'
                ];
            }else{
                $tarea = new Tarea($_POST);
                $resultado = $tarea->eliminar();

                if(!$resultado){
                    $respuesta = [
                        'tipo' =>'error',
                        'mensaje' => 'hubo un Error al agregar la tarea'
                    ];
                }else{
                    $respuesta = [
                    'tipo' =>'exito',
                    'tarea'=> $tarea,
                    'mensaje' => 'Tarea Eliminada Con Exito'
                    ];
                }
                
            }

            echo json_encode($respuesta);
        }
    }
}