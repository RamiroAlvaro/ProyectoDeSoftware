$(document).ready(function() { 
    $( "#fechaInicio" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function( selectedDate ) {
            $( "#fechaFin" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $( "#fechaFin" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function( selectedDate ) {
            $( "#fechaInicio" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
});