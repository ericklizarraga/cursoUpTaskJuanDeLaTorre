(()=>{n();let e=[],t=[];document.querySelector("#agregar-tarea").addEventListener("click",e=>{i(!1)});const a=document.querySelectorAll('#filtros input[type="radio"]');function o(a){const o=a.target.value;if(""!==o)return t=e.filter(e=>e.estado===o),void c(t);t=[...e],c(t)}async function n(){try{const t="/api/tareas?id="+l(),a=await fetch(t),o=await a.json();e=o,c(o)}catch(e){console.log(e)}}function c(t=[]){const a=document.querySelector("#listado-tareas");if(function(e=Element){for(;e.firstChild;)e.removeChild(e.firstChild)}(a),0===t.length){const e=document.createElement("LI");return e.textContent="No Hay Tareas",e.classList.add("no-tareas"),void a.appendChild(e)}t.forEach(t=>{const o=document.createElement("LI");o.dataset.tareaId=t.id,o.classList.add("tarea");const n=document.createElement("P");n.textContent=t.nombre,n.ondblclick=function(e){i(!0,t)};const d=document.createElement("DIV");d.classList.add("opciones");const u=document.createElement("BUTTON");u.classList.add("estado-tarea",""+r(t.estado).toLowerCase()),u.textContent=r(t.estado),u.dataset.estadoTarea=t.estado,u.ondblclick=function(e){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,s(e)}({...t})};const m=document.createElement("BUTTON");m.classList.add("eliminar-tarea"),m.dataset.idTarea=t.id,m.textContent="Eliminar",m.onclick=function(a){!function(t){Swal.fire({title:"Eliminar Tarea?",showCancelButton:!0,confirmButtonText:"SI",cancelButtonText:"NO"}).then(a=>{a.isConfirmed&&async function(t){const{estado:a,id:o,nombre:n,proyecto_id:r}=t,i=new FormData;i.append("id",o),i.append("estado",a),i.append("nombre",n),i.append("proyecto_id",l()),console.log(t),console.log("--------------------"),console.log(e);try{const a=await fetch("/api/tareas/eliminar",{method:"POST",body:i}),o=await a.json();console.log(o),"exito"===o.tipo&&(e=e.filter(e=>e.id!=t.id),console.log("--------------------"),console.log(e),Swal.fire("Eliminado",o.mensaje,"success"),c(e))}catch(e){console.log(e)}}(t)})}({...t})},d.appendChild(u),d.appendChild(m),o.appendChild(n),o.appendChild(d),a.appendChild(o)})}function r(e){return"0"===e?"Pendiente":"Completado"}function i(e=!1,t={}){const a=document.createElement("DIV");a.classList.add("modal"),a.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend>${e?"Editar Tarea":"Añade una nueva tarea"}</legend>\n                <div class="campo">\n                    <label>Tarea</label>\n                    <input  type="text" \n                            name="tarea"\n                            placeholder=" ${t.nombre?"Edita la Tarea":"Añadir  tarea al proyecto Actual"} "\n                            id="tarea"\n                            value="${t.nombre?t.nombre:""}" />\n                </div>\n\n                <div class="opciones">\n                    <input  type="submit"\n                            class="submit-nueva-tarea"\n                            value="${t.nombre?"Guardar Cambios":"Añadir Tarea"}" />\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>\n            </form>\n       `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},400),a.addEventListener("click",a=>{!function(e,t=!1,a){e.preventDefault();const o=e.target,c=document.querySelector(".modal"),r=document.querySelector(".formulario");(o.classList.contains("cerrar-modal")||o.classList.contains("modal"))&&(r.classList.add("cerrar"),setTimeout(()=>{c.remove()},500));if(o.classList.contains("submit-nueva-tarea")){const e=document.querySelector("#tarea").value.trim();if(!e)return void d("EL nombre de la tarea es oligatorio","error",document.querySelector(".formulario legend"));t?(a.nombre=e,s(a)):async function(e){const t=new FormData;t.append("nombre",e),t.append("proyecto_id",l());try{const e="/api/tareas",a=await fetch(e,{method:"POST",body:t}),o=await a.json();if(console.log(o),d(o.mensaje,o.tipo,document.querySelector(".formulario legend")),"exito"===o.tipo){const e=document.querySelector(".modal");setTimeout(()=>{e.remove(),n()},3e3)}}catch(e){console.log(e)}}(e)}}(a,e,{...t})}),document.querySelector(".dashboard").appendChild(a)}function d(e,t,a=document){if(!document.querySelector(".alerta")){const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextSibling),setTimeout((function(){o.remove()}),3e3)}}async function s(e){console.log(e);const{estado:t,id:a,nombre:o,proyecto_id:c}=e,r=new FormData;r.append("id",a),r.append("estado",t),r.append("nombre",o),r.append("proyecto_id",l());try{const e="/api/tareas/actualizar",t=await fetch(e,{method:"POST",body:r}),a=await t.json();if(console.log(a),"exito"===a.tipo){Swal.fire(a.mensaje,"","success");const e=document.querySelector(".modal");e&&e.remove()}n()}catch(e){console.log(e)}}function l(){return new URLSearchParams(window.location.search).get("id")??null}a&&a.forEach(e=>{e.addEventListener("click",o)})})();