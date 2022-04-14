<div class="contenedor olvide">
  
    <?php include_once __DIR__.'/../templates/nombre.sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera Tu Acceso UpTask</p>

        <?php include_once __DIR__.'/../templates/alertas.php'; ?>
        
        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input  type="email"
                        name="email" 
                        id="email"
                        placeholder="tu Email"
                        >
            </div>

            <input class="boton" type="submit" value="Eviar Instruciones">
        </form>

        <div class="acciones">
            <a href="/">Login</a>
            <a href="/crear">si no tiene una cuenta crea un Perro</a>
        </div>
    </div>
</div>