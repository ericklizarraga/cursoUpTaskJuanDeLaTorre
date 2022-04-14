
<div class="contenedor crear">
  
<?php include_once __DIR__.'/../templates/nombre.sitio.php'; ?>
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea Tu Cuenta En UpTask Sesión</p>
        
        <form action="/crear" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">nombre</label>
                <input  type="text"
                        name="nombre" 
                        id="nombre"
                        value="<?php echo $usuario->nombre; ?>"
                        placeholder="tu nombre">
            </div>
           
            <div class="campo">
                <label for="email">Email</label>
                <input  type="email"
                        name="email" 
                        id="email"
                        value="<?php echo $usuario->email; ?>"
                        placeholder="tu Email">
            </div>

            
            <div class="campo">
                <label for="password">Password</label>
                <input  type="password"
                        name="password" 
                        id="password"
                        placeholder="tu password">
            </div>
            
            <div class="campo">
                <label for="password">Repetir Password</label>
                <input  type="password"
                        name="repetirPassword" 
                        id="repetirPassword"
                        placeholder="Repite tu password">
            </div>

            <input class="boton" type="submit" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">si ya tiene una cuenta inicia sesion Perro</a>
            <a href="/olvide">Recuperar password</a>
        </div>
    </div>
</div>