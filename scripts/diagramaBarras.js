$(document).ready(function() {

    options = {
        chart: {
            renderTo: 'diagramaBarras',
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            title: {
                text: 'Fecha'
            },
            categories: [],
            labels: {
                align: 'center',
                x: -3,
                y: 20
//					formatter: function() {
//						return Highcharts.dateFormat('%l%p', Date.parse(this.value +' UTC'));
//					}
            }
        },
        yAxis: {
            title: {
                text: 'Peso (en kgs)'
            }
        },
        tooltip: {
            enabled: false,
            formatter: function() {
                return '<b>' + this.series.name + '</b><br/>' +
                        this.x + ': ' + this.y;
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
                type: 'column',
                name: 'Alimentos entregados',
                pointWidth: 25,
                data: []
            }]
    };


});

$(function() {

    var fechaInicio, fechaFin;

    $("#fechaInicio").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#fechaFin").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#fechaFin").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#fechaInicio").datepicker("option", "maxDate", selectedDate);
        }
    });

    $("#boton").click(function() {
        $('#diagramaBarras').show();
        fechaInicio = $(".datepicker[name=fechaInicio]").val();
        fechaFin = $(".datepicker[name=fechaFin]").val();
        $.getJSON( host + "estadisticas/graficarBarras/&desde=" + fechaInicio + "&hasta=" + fechaFin, function(json) {
            options.xAxis.categories = json['fecha'];
            options.series[0].data = json['kgs'];
            chart = new Highcharts.Chart(options);
        });

    });

    $('#botonListado1').click(function() {
        $('#listado1').show();
        fechaInicio = $(".datepicker[name=fechaInicio]").val();
        fechaFin = $(".datepicker[name=fechaFin]").val();
        url = host + "estadisticas/listarTotalAlimentos/&desde=" + fechaInicio + "&hasta=" + fechaFin;
        var tabla = $('#tablaTotalKgs').DataTable();
        $.ajax({
            type: "POST",
            url: url,
            datatype: "json",
            success: function(result) {
                var data = jQuery.parseJSON(result);
                tabla.clear();
                $.each(data, function(dx, fila){
                    tabla.row.add([
                                    fila.fecha,
                                    fila.kgs
                    ]).draw();
                });
            }
        });
    });  
   

});

function validarPDF1() {
    if ( !($('#fechaInicio').val()) || !($('#fechaFin').val()) ) {
        alert('Debe completar el rango de fechas');
        return false;
    }
}

