<?php
namespace Model;

use Model\ActiveRecord;

class Tarea extends ActiveRecord{
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id','nombre' ,'estado' ,'proyecto_id'];

    public $id;
    public $nombre;
    public $estado;
    public $proyecto_id;

    public function __construct($args = [])
    {
        $this->id            =   $args['id'] ?? null;
        $this->nombre        =   $args['nombre'] ?? '';
        $this->estado        =   $args['estado'] ?? 0;
        $this->proyecto_id   =   $args['proyecto_id'] ?? '';
    }


    public function validarTarea(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'ingrese la tarea del proyecto';
        }
        return self::$alertas;
    }
}