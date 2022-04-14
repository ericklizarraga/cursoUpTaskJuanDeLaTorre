(()=>{

    obtenerTareas();
    let arregloTareas = [];
    let arreglofiltradas = [];

    const nuevaTareaBTN = document.querySelector('#agregar-tarea');
    nuevaTareaBTN.addEventListener('click',(e)=>{
        mostrarformulario( false);
    });


    //filtros de busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
   
    if(filtros){
        filtros.forEach(radio => {
            radio.addEventListener('click',filtrarTareas);
        });
    }


    function filtrarTareas(e){
        const filtro = e.target.value;

        if(filtro !== ''){
            arreglofiltradas = arregloTareas.filter(tarea => tarea.estado === filtro);
            mostrarTareas( arreglofiltradas );
            return;
        }

        arreglofiltradas = [...arregloTareas];
        mostrarTareas( arreglofiltradas );
    }

   async function obtenerTareas(){
        try {
            const url = `/api/tareas?id=${obtenerProyecto()}`;
            const respuesta = await fetch(url);
            const tareas = await respuesta.json(); 
            arregloTareas = tareas;
            mostrarTareas( tareas );
        } catch (error) {
            console.log(error);
        }
    }
    

    function mostrarTareas( tareas = [] ){
        //console.log(tareas);
       
        const contenedorTareas = document.querySelector('#listado-tareas');
        limpiarHTML( contenedorTareas );

        if(tareas.length === 0){
            const noTareas =  document.createElement('LI');
            noTareas.textContent = 'No Hay Tareas';
            noTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(noTareas);
            return;
        }

        //console.log(tareas);

        tareas.forEach( tarea=> {
            //console.log(tarea,34);
            const liTarea = document.createElement('LI');
            liTarea.dataset.tareaId = tarea.id;
            liTarea.classList.add('tarea');

            const nombretarea = document.createElement('P');
            nombretarea.textContent = tarea.nombre;
            nombretarea.ondblclick = function(e){
                mostrarformulario(true, tarea);
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea',`${ estadoToString(tarea.estado).toLowerCase()}`);
            btnEstadoTarea.textContent = estadoToString(tarea.estado);
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function(e){
               cambiarTareaActual( {...tarea} );
             
            }


            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.onclick = function(e){
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            liTarea.appendChild(nombretarea);
            liTarea.appendChild(opcionesDiv);

            contenedorTareas.appendChild(liTarea);
            // console.log(contenedorTareas);
        });

    }
    
    function estadoToString(estado){
        return  (estado === '0') ? 'Pendiente' : 'Completado';
    }

    function limpiarHTML(contenedor = Element){
        while(contenedor.firstChild){
            contenedor.removeChild(contenedor.firstChild);
        }
    }

    function mostrarformulario(editar = false,tarea = {}){
       // console.log(tarea);
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${ editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input  type="text" 
                            name="tarea"
                            placeholder=" ${ (tarea.nombre) ? 'Edita la Tarea' : 'Añadir  tarea al proyecto Actual' } "
                            id="tarea"
                            value="${ (tarea.nombre) ? tarea.nombre : '' }" />
                </div>

                <div class="opciones">
                    <input  type="submit"
                            class="submit-nueva-tarea"
                            value="${ (tarea.nombre) ? 'Guardar Cambios' : 'Añadir Tarea' }" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
       ` ;
       
        setTimeout(()=>{
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        },400);


        modal.addEventListener('click',(e)=>{
            cerrarModal(e, editar, {...tarea});
        });

        document.querySelector('.dashboard').appendChild(modal);
    }



    function cerrarModal(e, editar = false, tareaObjet)
    {
        e.preventDefault();

        const target = e.target;
        const modal = document.querySelector('.modal');
        const formulario = document.querySelector('.formulario');

        if(target.classList.contains('cerrar-modal') || target.classList.contains('modal')){

            formulario.classList.add('cerrar');

           setTimeout(()=>{
                modal.remove();
           },500);
        }

        
        if(target.classList.contains('submit-nueva-tarea')){
            // submitFormularioNuevaTarea();

            const tarea = document.querySelector('#tarea').value.trim();
            if(!tarea){
               mostrarAlerta('EL nombre de la tarea es oligatorio','error',document.querySelector('.formulario legend'));
                return;
            }

            if(editar){
                tareaObjet.nombre = tarea;
                actualizarTarea(tareaObjet);
            }else{
                agregarTarea( tarea );
            }
        }
    }


    // function submitFormularioNuevaTarea()
    // {
    //     agregarTarea(tarea);
    // }

    function mostrarAlerta(mensaje, tipo, referencia = document)
    {
        const existeAlerta = document.querySelector('.alerta');
        
        if(!existeAlerta){
            const alerta = document.createElement('DIV');
            alerta.classList.add('alerta', tipo);
            alerta.textContent = mensaje;
    
            referencia.parentElement.insertBefore(alerta, referencia.nextSibling);

            setTimeout(function(){
                alerta.remove();
            },3000);
           
        }
    }

    async function agregarTarea(tarea)
    {
        //console.log(tarea);
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyecto_id', obtenerProyecto() );

        try {
            const url = '/api/tareas';
            const respuesta = await fetch(url, {
                method  :   'POST',
                body    :   datos
            });
            
            const resultado  = await respuesta.json();
            console.log(resultado);
            mostrarAlerta(resultado.mensaje ,resultado.tipo ,document.querySelector('.formulario legend'));
            
            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(()=>{
                    modal.remove();
                    obtenerTareas();
                },3000);
            }
            
        } catch (error) {
            console.log(error);
        }
    }


    function cambiarTareaActual( tarea)
    {
        // console.log(tarea);
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea( tarea );
    }


    async function actualizarTarea(tarea){
        console.log(tarea);
       
       const {estado,id, nombre, proyecto_id} = tarea;

       const datos = new FormData();
       datos.append('id',id);
       datos.append('estado',estado);
       datos.append('nombre',nombre);
       datos.append('proyecto_id', obtenerProyecto());

       try {
           const url = '/api/tareas/actualizar';
           const respuesta = await fetch(url, {
               method:'POST',
               body:datos
           }); 

           const resultado = await respuesta.json();
           console.log(resultado);
           
           if(resultado.tipo === 'exito'){
                Swal.fire(
                     resultado.mensaje,
                     '',
                     'success'
                    );

                    const modal = document.querySelector('.modal');
                    if(modal){
                        modal.remove();
                    }
           }
          
           obtenerTareas();
       
       } catch (error) {
           console.log(error);
       }
    }

    function confirmarEliminarTarea(tarea){

        Swal.fire({
            title: 'Eliminar Tarea?',
            showCancelButton: true,
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
          })
    }


    async function eliminarTarea(tarea){

        const {estado,id, nombre, proyecto_id} = tarea;
        const datos = new FormData();

        datos.append('id',id);
        datos.append('estado',estado);
        datos.append('nombre',nombre);
        datos.append('proyecto_id', obtenerProyecto());
        console.log(tarea);
        console.log('--------------------');
        console.log(arregloTareas);

        const url = '/api/tareas/eliminar';
       

        try {

            const respuesta = await fetch(url,{
                method:'POST',
                body: datos
            });
         
            
           const resultado = await respuesta.json();
            console.log(resultado);
           if(resultado.tipo === 'exito'){
                arregloTareas = arregloTareas.filter(tareamemoria => tareamemoria.id != tarea.id);
                console.log('--------------------');
                console.log(arregloTareas);
                Swal.fire('Eliminado',resultado.mensaje,'success');
                mostrarTareas( arregloTareas );
           }

          
        } catch (error) {
            console.log(error);
        }
       
    }

    function obtenerProyecto()
    {
        const params = new URLSearchParams(window.location.search);
        const propietarioId = params.get('id') ?? null; 
        return propietarioId;
    }

})();