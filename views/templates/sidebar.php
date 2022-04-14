<aside class="sidebar">

    <div class="contenedor-sidebar">
        <h2>UPTask</h2>

        <div class="cerrar-menu">
            <span></span>
            <span></span>
        </div>
    </div>

    <nav class="sidebae-nav">
        <a class="<?php echo ($titulo === 'proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'crear proyecto') ? 'activo' : ''; ?>" href="/crear-proyecto">Crear Proyectos</a>
        <a class="<?php echo ($titulo === 'perfil') ? 'activo' : ''; ?>" href="/perfil">Perfil</a>
    </nav>

    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesion</a>
    </div>
</aside>