/*Mostrar ocultar menu principal*/
let btn_menu=document.getElementById('btn-menu');
btn_menu.addEventListener("click", function(e){
    e.preventDefault();

    let navLateral=document.getElementById('navLateral');
    let pageContent=document.getElementById('pageContent');

    if(navLateral.classList.contains('navLateral-change') && pageContent.classList.contains('pageContent-change')){
        navLateral.classList.remove('navLateral-change');
        pageContent.classList.remove('pageContent-change');
    }else{
        navLateral.classList.add('navLateral-change');
        pageContent.classList.add('pageContent-change');
    }
});

/*Mostrar y ocultar submenus*/
let btn_subMenu=document.querySelectorAll(".btn-subMenu");
btn_subMenu.forEach(subMenu => {
    subMenu.addEventListener("click", function(e){

        e.preventDefault();
        if(this.classList.contains('btn-subMenu-show')){
            this.classList.remove('btn-subMenu-show');
        }else{
            this.classList.add('btn-subMenu-show');
        }
    });
});

