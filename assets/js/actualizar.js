document.addEventListener('click', function(e) {
    if (e.target.classList.contains('boton-juego')) {
        e.preventDefault();
        var valor = e.target.getAttribute('data-valor');
        var botonSeleccionado = e.target;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("jugadas").innerHTML = this.responseText;
                botonSeleccionado.classList.remove('primary', 'boton-juego');
                botonSeleccionado.classList.add('ficha-select');
            }
        };
        xhttp.open("GET", "procesar.php?valor=" + valor, true);
        xhttp.send();
    }
});




document.addEventListener('click', function(e) {
    if (e.target.classList.contains('ficha-select')) {
        e.preventDefault();
        var valor = e.target.getAttribute('data-valor');
        var botonSeleccionado = e.target;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("jugadas").innerHTML = this.responseText;
                botonSeleccionado.classList.remove('primary','ficha-select');
                botonSeleccionado.classList.add('ficha-casino','boton-juego');
                
            }
        };
        xhttp.open("GET", "quitar.php?valor=" + valor, true);
        xhttp.send();
    }
});

