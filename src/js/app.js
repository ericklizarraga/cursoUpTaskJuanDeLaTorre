
const mobilMenuBtn  = document.querySelector('.menu'); 
const mobilCerrarMenuBtn  = document.querySelector('.cerrar-menu'); 
const sidebar  = document.querySelector('.sidebar'); 


console.log(mobilMenuBtn, sidebar);

if(mobilMenuBtn){
    mobilMenuBtn.addEventListener('click', function(e){
         sidebar.classList.toggle('mostrar');
    });
}


if(mobilCerrarMenuBtn){
    mobilCerrarMenuBtn.addEventListener('click',function(e){
        sidebar.classList.remove('mostrar');
    });
}