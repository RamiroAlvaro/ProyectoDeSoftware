$(document).ready(function() {
    
    $('.solo-numero').keyup(function() {
        this.value = (this.value + '').replace(/[^0-9+\-Ee.]/g, '');
    });


    $('#enviar').click(function() {
        if ($("#alimento").val() == -1) {
            alert("Se debe elegir un alimento.");
            return false;
        }
        if ($("#donanteNombre").val() == -1) {
            alert("Se debe elegir nombre de donante.");
            return false;
        }
    });
    
});