let $code_id, $div_machine, $div_product, $div_color, $div_conversion, $div_quantity, $div_meters, $div_comment,
    $machine_id, $product_id, $color_id, $meters, $comment, $conversion_id, $quantity;

$(document).ready(function() {
    cargarFormulario();
});

function cargarFormulario() {
    $code_id = $('#code_id');
    $div_code = $('#div_code');
    $machine_id = $('#machine_id');
    $div_machine = $('#div_machine');
    $product_id = $('#product_id');
    $div_product = $('#div_product');
    $color_id = $('#color_id');
    $div_color = $('#div_color');
    $conversion_id = $('#conversion_id');
    $div_conversion = $('#div_conversion');
    $quantity = $('#quantity');
    $div_quantity = $('#div_quantity');
    $meters = $('#meters');
    $div_meters = $('#div_meters');
    $comment = $('#comment');
    $div_comment = $('div_comment')

    $conversion_id = $('#conversion_id');


    var codeId = $code_id.val();
    const url = `/api/codes/${codeId}`;

    var machine_process;

    $.getJSON(url, function(data) {
        var type = data.type;
        var code = data.code;

        var machineId = $machine_id.val();
        $.ajax({
            url: `/api/machines/${machineId}`,
            async: false,
            dataType: 'json',
            success: function(machine) {
                machine_process = machine.process;
            }
        });

        console.log(machine_process)

        /*var conversionId = $conversion_id.val();
        $.ajax({
            url: `/api/conversions/${conversionId}`,
            async: false,
            dataType: 'json',
            success: function(conversion) {
                conversion_factor = conversion.factor;
            }
        });

        console.log(conversion_factor);*/


        switch (code) {
            case 0:
                $div_machine.show();
                $machine_id.attr('required', '');
                $div_product.show();
                $product_id.attr('required', '');
                $div_color.show();
                $color_id.attr('required', '');
                if (machine_process == "Trefilado" || machine_process == "Fraccionado") {
                    $div_conversion.show();
                    $div_quantity.show();
                }
                $div_meters.show();
                //$meters.removeAttr('required');
                $comment.removeAttr('required');
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $div_product.hide();
                $product_id.removeAttr('required');
                $color_id.val(null);
                $div_color.hide();
                $color_id.removeAttr('required');
                $product_id.val(null);
                $div_conversion.hide();
                $conversion_id.val(null);
                $div_quantity.hide();
                $quantity.val(null);
                $div_meters.hide();
                $meters.removeAttr('required');
                $meters.val(null)
                $div_machine.show();
                $machine_id.attr('required', '');
                $comment.removeAttr('required');
                break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                $div_machine.show();
                $machine_id.attr('required', '');
                $div_comment.show();
                $comment.attr('required', '');
                $div_product.hide();
                $product_id.removeAttr('required');
                $product_id.val(null);
                $div_color.hide();
                $color_id.removeAttr('required');
                $color_id.val(null);
                $div_conversion.hide();
                $conversion_id.val(null);
                $div_quantity.hide();
                $quantity.val(null);
                $div_meters.hide();
                $meters.removeAttr('required');
                $meters.val(null)
                break;
            default:
                $div_machine.show();
                $machine_id.attr('required', '');
                $div_product.hide();
                $product_id.removeAttr('required');
                $div_color.hide();
                $color_id.removeAttr('required');
                $div_conversion.hide();
                $conversion_id.val(null);
                $div_quantity.hide();
                $quantity.val(null);
                $div_meters.hide();
                $meters.removeAttr('required');
                $comment.removeAttr('required');
                break;
        }

        $('#type_stop').val(type);
    });
};

function calcularConversion() {
    var conversionId = $conversion_id.val();
    var conversion_factor;

    var quantity = $quantity.val();

    $.ajax({
        url: `/api/conversions/${conversionId}`,
        async: false,
        dataType: 'json',
        success: function(conversion) {
            conversion_factor = conversion.factor;
        }
    });

    $('#meters').val((conversion_factor * quantity).toFixed(2))

    console.log(conversion_factor * quantity)
}

$("#quantity").on('change keyup paste', function() {
    calcularConversion();
})