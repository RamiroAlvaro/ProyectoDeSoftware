$(function() {
    $('#datetimepicker5').datetimepicker({
        pickTime: false
    });
});

$(function() {
    $('#mostrarEnvios').click(function() {
        $('#tablaEnvios').show();
        fechaEnvios = $("#fechaEnvio").val();
        url = host + "turnoentrega/listarPorFecha/&fechaEnvio=" + fechaEnvios;
        var tabla = $('#tablaEnv').DataTable();
        $.ajax({
            type: "POST",
            url: url,
            datatype: "json",
            success: function(result) {
                var data = jQuery.parseJSON(result);
                tabla.clear();
                $.each(data, function(dx, fila){
                    tabla.row.add([
                                    fila.numero,
                                    fila.hora,
                                    fila.razon_social,
                                    fila.con_envio,
                                    fila.entregado
                    ]).draw();
                });
            }
        });
    });
});
