
var numlineas = 0;
var fs_nf0 = 2;
var fs_nf0_art = 2;
var all_direcciones = [];
var all_impuestos = [];
var default_impuesto = '';
var all_series = [];
var cliente = false;
var nueva_venta_url = '';
var fin_busqueda1 = true;
var fin_busqueda2 = true;
var siniva = false;
var irpf = 0;
var fechaemisl = false;
var fechaemist = false;
var solo_con_stock = true;

function usar_cliente(codcliente)
{
    if (nueva_venta_url !== '') {
        $.getJSON(nueva_venta_url, 'datoscliente=' + codcliente, function (json) {
            cliente = json;
            document.f_buscar_articulos.codcliente.value = cliente.codcliente;
            if (cliente.regimeniva == 'Exento') {
                irpf = 0;
                for (var j = 0; j < numlineas; j++) {
                    if ($("#linea_" + j).length > 0) {
                        $("#iva_" + j).val(0);
                        $("#recargo_" + j).val(0);
                    }
                }
            }
            //recalcular();
        });
    }
}



function disable_inputs(name, value)
{
    for (var i = 0; i < numlineas; i++) {
        if ($("#linea_" + i).length > 0) {
            $("#" + name + "_" + i).prop('disabled', value);
        }
    }
}


function add_articulo(nrocheque, banco, nrocuenta, fechaemi, fechapago, importe, ordende, clientetitular,idbanco )
{
    

    //banco = Base64.decode(banco);
    $("#lineas_doc").append("<tr id=\"linea_" + numlineas + "\" data-nrocheque=\"" + nrocheque + "\">\n\
      <td><input type=\"hidden\" name=\"idlinea_" + numlineas + "\" value=\"-1\"/>\n\
         <input type=\"hidden\" name=\"nrocheque_" + numlineas + "\" value=\"" + nrocheque + "\"/>\n\
         <div class=\"form-control\"><small>" + nrocheque + "</small></div></td>\n\
        <input type=\"hidden\" name=\"idbanco_" + numlineas + "\" value=\"" + idbanco + "\"/> </td>\n\
      <td><textarea class=\"form-control\" disabled=\"\" id=\"banco_" + numlineas + "\" name=\"banco_" + numlineas + "\" rows=\"1\">" + banco + "</textarea>\n\
          <input type=\"hidden\" name=\"banco_" + numlineas + "\" value=\"" + banco + "\"/>  </td>\n\
      <td><button class=\"btn btn-sm btn-danger\" type=\"button\" onclick=\"$('#linea_" + numlineas + "').remove();\">\n\
         <span class=\"glyphicon glyphicon-trash\"></span></button></td>\n\
      <td><input type=\"text\"  class=\"form-control text-right\" id=\"nrocuenta_" + numlineas + "\" name=\"nrocuenta_" + numlineas + "\" value=\"" + nrocuenta +
            "\" onkeyup=\"\" onclick=\"this.select()\" autocomplete=\"off\"/>\n\
            <input type=\"hidden\" name=\"nrocuenta_" + numlineas + "\" value=\"" + nrocuenta + "\"/> </td>\n\
      <td><input type=\"text\" id=\"fechaemi_" + numlineas + "\" name=\"fechaemi_" + numlineas + "\" value=\"" + fechaemi +
            "\" class=\"form-control text-right\" onclick=\"this.select()\" autocomplete=\"off\"/></td>\n\
   <td><input type=\"text\" id=\"fechapago_" + numlineas + "\" name=\"fechapago_" + numlineas + "\" value=\"" + fechapago +
            "\" class=\"form-control text-right\" onclick=\"this.select()\" autocomplete=\"off\"/></td>\n\
    <td><input type=\"" + input_number + "\" step=\"any\" id=\"importe_" + numlineas + "\" class=\"form-control text-right\" name=\"importe_" + numlineas +
            "\" onchange=\"\" onkeyup=\"\" autocomplete=\"off\" value=\"" + importe + "\"/></td>\n\
  <td><input type=\"text\" id=\"ordende_" + numlineas + "\" name=\"ordende_" + numlineas + "\" value=\"" + ordende +
            "\" class=\"form-control text-right\" onclick=\"this.select()\" autocomplete=\"off\"/></td>\n\
<td><input type=\"text\" id=\"titular_" + numlineas + "\" name=\"titular_" + numlineas + "\" value=\"" + clientetitular +
            "\" class=\"form-control text-right\" onclick=\"this.select()\" autocomplete=\"off\"/></td>\n\
         <input type=\"text\"  class=\"form-control text-right\" id=\"total_" + numlineas + "\" name=\"total_" + numlineas +
            "\" onchange=\"ajustar_total(" + numlineas + ")\" onclick=\"this.select()\" autocomplete=\"off\"/></td></tr>");
    numlineas += 1;
    $("#numlineas").val(numlineas);
   // recalcular();

    $("#modal_articulos").modal('hide');

    $("#banco_" + (numlineas - 1)).select();
     
    return false;
}

function add_articulo_atributos(nrocheque, banco, nrocuenta, fechaemi, fechapago, importe)
{
    if (nueva_venta_url !== '') {
        $.ajax({
            type: 'POST',
            url: nueva_venta_url,
            dataType: 'html',
            data: "nrochequeerencia4combi=" + nrocheque + "&banco=" + banco + "&nrocuenta=" + nrocuenta + "&fechaemi=" + fechaemi
                    + "&fechapago=" + fechapago + "&importe=" + importe,
            success: function (datos) {
                $("#nav_articulos").hide();
                $("#search_results").html(datos);
            },
            error: function () {
                bootbox.alert({
                    message: 'Se ha producido un error al obtener los atributos.',
                    title: "<b>Atención</b>"
                });
            }
        });
    }
}

function new_articulo()
{
    if (nueva_venta_url !== '') {
        $.ajax({
            type: 'POST',
            url: nueva_venta_url + '&new_articulo=TRUE',
            dataType: 'json',
            data: $("form[name=f_nuevo_articulo]").serialize(),
            success: function (datos) {
                if (typeof datos[0] == 'undefined') {
                    bootbox.alert({
                        message: 'Se ha producido un error al crear el cheque.',
                        title: "<b>Atención</b>"
                    });
                } else {
                    document.f_buscar_articulos.query.value = document.f_nuevo_articulo.nrocheque.value;
                    $("#nav_articulos li").each(function () {
                        $(this).removeClass("active");
                    });
                    $("#li_mis_articulos").addClass('active');
                    $("#search_results").show();
                    $("#nuevo_articulo").hide();
//function add_articulo(nrocheque, banco, nrocuenta, fechaemi, fechapago, importe, ordende, clientetitular,idbanco )
                    add_articulo(datos[0].nrocheque, datos[0].banconombre,datos[0].nrocuenta, datos[0].fec_emision, datos[0].fec_pago, datos[0].importe,datos[0].aordende,datos[0].clientenombre,datos[0].idbanco);
                }
            },
            error: function () {
                bootbox.alert({
                    message: 'Se ha producido un error al crear el cheque 2.',
                    title: "<b>Atención</b>"
                });
            }
        });
    }
}

function buscar_articulos()
{
   

    if (document.f_buscar_articulos.query.value === '') {
        $("#nav_articulos").hide();
        $("#search_results").html('');
        $("#nuevo_articulo").hide();

        fin_busqueda1 = true;
        fin_busqueda2 = true;
    } else {
        $("#nav_articulos").show();

        if (nueva_venta_url !== '') {
            fin_busqueda1 = false;
            $.getJSON(nueva_venta_url, $("form[name=f_buscar_articulos]").serialize(), function (json) {
                var items = [];
                var insertar = false;
                $.each(json, function (key, val) {
                   
                    var bancoripcion_visible = val.nrocheque;                   

                    var funcion = "add_articulo('" + val.nrocheque + "','" + val.banconombre + "','" + val.nrocuenta + "',\n\
                                '"+ val.fec_emision + "','" + val.fec_pago + "','" + val.importe + "',\n\
                                '" + val.aordende + "','" + val.clientenombre + "','" + val.idbanco + "')";

                      

                     items.push("<tr class=\"success\"><td><a href=\"#\" onclick=\" " + val.nrocheque + "\" title=\"más detalles\">\n\
                     <span class=\"glyphicon glyphicon-eye-open\"></span></a>\n\
                     &nbsp; <a href=\"#\" onclick=\"return " + funcion + "\">" + val.nrocheque + '</a> ' + bancoripcion_visible + "</td>\n\
                     <td class=\"text-right\"><a href=\"#\" onclick=\"return " + funcion + "\" title=\"actualizado el " 
                                + "\">" + val.banconombre + "</a></td>\n\
                     <td class=\"text-right\"><a href=\"#\"  title=\"actualizado el " 
                                + "\">" + val.nrocuenta + "</a></td>\n\
                    <td class=\"text-right\">" + val.fec_emision + "</td>\n\
                    <td class=\"text-right\">" + val.fec_pago + "</td>\n\
                    <td class=\"text-right\">" + val.importe + "</td>\n\
                    <td class=\"text-right\">" + val.aordende + "</td>\n\
                    <td class=\"text-right\">" + val.clientenombre + "</td>\n\
                    <td class=\"text-right\">" + val.idbanco + "</td></tr>");
                    
                    
          
                        insertar = true;                        
                        fin_busqueda1 = true;
                        
                
                });

                if (items.length == 0 && !fin_busqueda1) {
                    items.push("<tr><td colspan=\"4\" class=\"warning\">Sin resultados. Usa la pestaña\n\
                              <b>Nuevo</b> para crear uno.</td></tr>");
                   // document.f_nuevo_articulo.nrochequeerencia.value = document.f_buscar_articulos.query.value;
                    insertar = true;
                    
                }

                if (insertar) {
                    $("#search_results").html("<div class=\"table-responsive\"><table class=\"table table-hover\"><thead><tr>\n\
                  <th class=\"text-left\"  width=\"80\">Nro. Cheque</th>\n\
                  <th class=\"text-right\" width=\"80\">Banco</th>\n\
                  <th class=\"text-right\" width=\"80\">Nro. Cuenta</th>\n\
                  <th class=\"text-right\" width=\"80\">Fecha Emi.</th>\n\
                  <th class=\"text-right\" width=\"80\">Fecha Pago</th>\n\
                  <th class=\"text-right\" width=\"80\">Importe</th>\n\
                  <th class=\"text-right\" width=\"80\">A orden de</th>\n\
                  <th class=\"text-right\" width=\"80\">Titular</th>\n\
                  <th class=\"text-right\" width=\"1\">ID</th>\n\
                  </tr></thead>" + items.join('') + "</table></div>");
                }
            });
        }
    }
}




$(document).ready(function () {
    /**
     * Renombramos el id "lineas_albaran" a "lineas_doc", para asegurar que no deja de funcionar
     * hasta que todos los plugins gratuitos y de pago hayan aplicado el cambio.
     */
    $("#lineas_albaran").attr('id', 'lineas_doc');

    show = false;
    if (!show) {
        fechaemisl = false;
        $('.fechaemisl').hide();
    } else {
        fechaemisl = true;
        $('.fechaemisl').show();
    }
    $("#i_new_line").click(function () {
        $("#i_new_line").val("");
        $("#nav_articulos li").each(function () {
            $(this).removeClass("active");
        });
        $("#li_mis_articulos").addClass('active');
        $("#search_results").show();
        $("#nuevo_articulo").hide();
        $("#modal_articulos").modal('show');
        document.f_buscar_articulos.query.select();
    });

    $("#i_new_line").keyup(function () {
        document.f_buscar_articulos.query.value = $("#i_new_line").val();
        $("#i_new_line").val('');
        $("#nav_articulos li").each(function () {
            $(this).removeClass("active");
        });
        $("#li_mis_articulos").addClass('active');
        $("#search_results").html('');
        $("#search_results").show();
        $("#nuevo_articulo").hide();
        $("#modal_articulos").modal('show');
        document.f_buscar_articulos.query.select();
        buscar_articulos();
    });

    $("#f_buscar_articulos").keyup(function () {
        buscar_articulos();
    });

    $("#f_buscar_articulos").submit(function (event) {
        event.preventDefault();
        buscar_articulos();
    });

    $("#b_mis_articulos").click(function (event) {
        event.preventDefault();
        $("#nav_articulos li").each(function () {
            $(this).removeClass("active");
        });
        $("#li_mis_articulos").addClass('active');
        $("#nuevo_articulo").hide();
        $("#search_results").show();
        document.f_buscar_articulos.query.focus();
    });

    $("#b_nuevo_articulo").click(function (event) {
        event.preventDefault();
        $("#nav_articulos li").each(function () {
            $(this).removeClass("active");
        });
        $("#li_nuevo_articulo").addClass('active');
        $("#search_results").hide();
        $("#nuevo_articulo").show();
        document.f_nuevo_articulo.nrocheque.select();
    });
});
