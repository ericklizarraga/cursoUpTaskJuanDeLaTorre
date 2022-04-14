<div class="contenedor login">
  
    <?php include_once __DIR__.'/../templates/nombre.sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php include_once __DIR__.'/../templates/alertas.php'; ?>
        
        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input  type="email"
                        name="email" 
                        id="email"
                        placeholder="tu Email">
            </div>

            
            <div class="campo">
                <label for="password">Password</label>
                <input  type="password"
                        name="password" 
                        id="password"
                        placeholder="tu password">
            </div>

            <input class="boton" type="submit" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">si no tiene una cuenta crea un Perro</a>
            <a href="/olvide">Recuperar password</a>
        </div>
    </div>
</div>