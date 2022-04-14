<div class="contenedor reestablecer">
  
    <?php include_once __DIR__.'/../templates/nombre.sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">coloca tu nuevo password*</p>

        <?php include_once __DIR__.'/../templates/alertas.php'; ?>
        <?php if($mostrar) : ?>
                    <form  class="formulario" method="POST">
                        
                        <div class="campo">
                            <label for="password">Password</label>
                            <input  type="password"
                                    name="password" 
                                    id="password"
                                    placeholder="tu password">
                        </div>

                        <input class="boton" type="submit" value="guardar password">
                    </form>
        <?php  endif; ?>

        <div class="acciones">
            <a href="/">Login</a>
            <a href="/crear">si no tiene una cuenta crea un Perro</a>
        </div>
    </div>
</div>