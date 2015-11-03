$(document).ready(function() {

    options2 = {
        chart: {
            renderTo: 'diagramaTorta',
            type: 'pie'
        },
        title: {
            text: ''
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
                    }
                }
            }
        },
        series: [{
                type: 'pie',
                name: 'Alimentos entregados a ER (en kgs)',
                data: []
            }]
    };


});

$(function() {

    var fechaInicio2, fechaFin2;

    $("#fechaInicio2").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#fechaFin2").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#fechaFin2").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#fechaInicio2").datepicker("option", "maxDate", selectedDate);
        }
    });

    $("#boton2").click(function() {
        $('#diagramaTorta').show();
        fechaInicio2 = $(".datepicker[name=fechaInicio2]").val();
        fechaFin2 = $(".datepicker[name=fechaFin2]").val();
        $.getJSON(host + "estadisticas/graficarTorta/&desde2=" + fechaInicio2 + "&hasta2=" + fechaFin2, function(json) {
            options2.series[0].data = json;
            chart = new Highcharts.Chart(options2);
        });

    });

    $('#botonListado2').click(function() {
        $('#listado2').show();
        fechaInicio = $(".datepicker[name=fechaInicio2]").val();
        fechaFin = $(".datepicker[name=fechaFin2]").val();
        url = host + "estadisticas/listarAlimentosPorEntidad/&desde2=" + fechaInicio + "&hasta2=" + fechaFin;
        var tabla = $('#tablaKgsEntidad').DataTable();
        $.ajax({
            type: "POST",
            url: url,
            datatype: "json",
            success: function(result) {
                var data = jQuery.parseJSON(result);
                tabla.clear();
                $.each(data, function(dx, fila){
                    tabla.row.add([
                                    fila.entidad,
                                    fila.kgs
                    ]).draw();
                });
            }
        });
    });
    
    
    
});

function validarPDF2() {
    if ( !($('#fechaInicio2').val()) || !($('#fechaFin2').val()) ) {
        alert('Debe completar el rango de fechas');
        return false;
    }
}

