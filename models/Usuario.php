<?php
namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];


    public $id;
    public $nombre;
    public $email;
    public $password;
    public $repetirPassword;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id           =   $args['id'] ?? null;
        $this->nombre       =   $args['nombre'] ?? '';
        $this->email        =   $args['email'] ?? '';
        $this->password     =   $args['password'] ?? '';
        $this->password_actual     =   $args['password_actual'] ?? '';
        $this->password_nuevo     =   $args['password_nuevo'] ?? '';
        $this->repetirPassword     =   $args['repetirPassword'] ?? null;
        $this->token        =   $args['token'] ?? '';
        $this->confirmado   =   $args['confirmado'] ?? 0;
    }




    public function validarCuentaNueva() : array
    {
        if(!$this->nombre){
            self::$alertas['error'][] = 'ingrese el nombre';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'ingrese el email';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'ingrese el password';
        }
        if( strlen( $this->password ) < 6  ){
            self::$alertas['error'][] = 'ingrese un password mayor o igual 6 caracteres';
        }
        if( $this->password  != $this->repetirPassword  ){
            self::$alertas['error'][] = 'las contraceñas no son iguales';
        }

        return self::$alertas;
    }




    public function validarEmail()
    {
        if(!$this->email){
            self::$alertas['error'][] = 'el email obligatorio';
        }
        if( !filter_var( $this->email, FILTER_VALIDATE_EMAIL ) ){
            self::$alertas['error'][] = 'el email no es valido';
        }

        return self::$alertas;
    }




    public function validarPassword()
    {
        if(!$this->password){
            self::$alertas['error'][] = 'ingrese el password';
        }
        if( strlen( $this->password ) < 6  ){
            self::$alertas['error'][] = 'ingrese un password mayor o igual 6 caracteres';
        }

        return self::$alertas;
    }



    public function validarLogin()
    {
        if(!$this->email){
            self::$alertas['error'][] = 'el email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'ingrese el password';
        }
        if( !filter_var( $this->email, FILTER_VALIDATE_EMAIL ) ){
            self::$alertas['error'][] = 'el email no es valido';
        }

        return self::$alertas;
    }


    public function validarPerfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'el nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'el email es obligatorio';
        }

        return self::$alertas;
    }

    public function nuevoPassword(){
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'ingrese el password Nuevo';
        }
        if(!$this->password_actual){
            self::$alertas['error'][] = 'ingrese el password Actual';
        }
        if( strlen( $this->password_nuevo ) < 6  ){
            self::$alertas['error'][] = 'ingrese un password nuevo mayor o igual 6 caracteres';
        }
        return self::$alertas;
    }


    public function comprobar_password() :bool{
        return password_verify($this->password_actual, $this->password);
    }


    public function hashPassword() : void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }




    public function crearToken() : void
    {
        $this->token = uniqid();
    }
}