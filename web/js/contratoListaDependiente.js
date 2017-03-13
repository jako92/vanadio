function cargar_clientes() {
    $.get("../../../../../../web/scripts/cargar-Cliente.php", function (resultado) {
        if (resultado == false) {
            alert("Error");
        } else
        {
            $('#cliente').append(resultado);
        }
    });
}

function dependencia_sucursal() {
    var code = $('#cliente').val();
    $.get("../../../../../../web/scripts/cargar-Sucursal.php", {code: code},
            function (resultado)
            {
                if (resultado == false) {
                    alert("Error");
                } else {
                    $("#sucursal").attr("disable", false);
                    document.getElementById("sucursal").options.length = 1;
                    $("#sucursal").append(resultado);
                }
            }
    );
}

function dependencia_centro() {
    var code = $("#sucursal").val();
    $.get("../../../../../../web/scripts/cargar-Centro.php", {code: code},  
        function (resultado){
        if (resultado == false) {
            alert("Error");
        } else {
            $("#centro").attr("disable", false);
            document.getElementById("centro").options.length = 1;
            $("#centro").append(resultado);
        }
    }
    );
}
