$(document).ready(function() {
    url = host + "estadisticas/alimentosVencidosSinEntregar";
    $('#tablaVencidos').DataTable( {
       "retrieve": true,
       "ajax": url, 
       "columns": [
                      { "data": "alimento_codigo" },
                      { "data": "fecha_vencimiento" },
                      { "data": "stock" }
        ]
    });
});
