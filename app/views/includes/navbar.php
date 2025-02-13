<div class="full-width navBar">
    <div class="full-width navBar-options">
        <i class="fas fa-exchange-alt fa-fw" id="btn-menu"></i> 
        <nav class="navBar-options-list">
            <ul class="list-unstyle">
                <li class="text-condensedLight noLink" >
                    <div id="dolarText" ></div>

                </li>
            </ul>
        </nav>
    </div>
</div>
<li class="full-width divider-menu-h-large"></li>


<script>
    fetch("https://dolarapi.com/v1/dolares")
        .then(response=>response.json())
        .then(data=>{
            const container = document.getElementById('dolarText')
            data.forEach(dolar => {
                const card = document.createElement('div');
                card.innerHTML = `
                    <p>Dolar ${dolar.nombre}: $${dolar.venta}</p>
                `
                if(dolar.nombre == "Blue"){
                    container.appendChild(card)
                }
                
            });
            
        })

        .catch(error=>{
            console.log("Error al obtener los datos.")
        })
</script>