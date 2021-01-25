let $code_id, $div_machine, $div_product, $div_color, $div_meters, $div_comment,
    $machine_id, $product_id, $color_id, $meters, $comment;

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
    $meters = $('#meters');
    $div_meters = $('#div_meters');
    $comment = $('#comment');
    $div_comment = $('div_comment')


    var codeId = $code_id.val();
    const url = `/api/codes/${codeId}`;

    $.getJSON(url, function(data) {
        var type = data.type;
        var code = data.code;

        console.log(code);

        switch (code) {
            case 0:
                $div_machine.show();
                $machine_id.attr('required', '');
                $div_product.show();
                $product_id.attr('required', '');
                $div_color.show();
                $color_id.attr('required', '');
                $div_meters.show();
                $meters.removeAttr('required');
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
                $div_meters.hide();
                $meters.removeAttr('required');
                $comment.removeAttr('required');
                break;
        }

        $('#type_stop').val(type);
    });

};