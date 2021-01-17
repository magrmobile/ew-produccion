let $code_id, $div_machine, $div_product, $div_color, $div_meters, $div_comment,
    $machine_id, $product_id, $color_id, $meters, $comment;

$(document).ready(function() {
    cargarFormulario();
});

function cargarFormulario() {
    $code_id = $('#code_id');
    $machine_id = $('#machine_id');
    $product_id = $('#product_id');
    $color_id = $('#color_id');
    $meters = $('#meters');
    $comment = $('#comment');
    $div_machine = $('#div_machine');


    var codeId = $code_id.val();
    const url = `/api/codes/${codeId}`;

    $.getJSON(url, function(data) {
        var type = data.type;
        var code = data.code;

        switch (code) {
            case 0:
                $('#div_machine').show();
                $('#machine_id').attr('required', '');
                $('#div_product').show();
                $('#product_id').attr('required', '');
                $('#product_id').empty();
                $('#div_color').show();
                $('#color_id').attr('required', '');
                $('#color_id').empty();
                $('#div_meters').show();
                $('#meters').removeAttr('required');
                $('#comment').removeAttr('required');
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $('#div_product').hide();
                $('#product_id').removeAttr('required');
                $('#product_id').empty();
                $('#div_color').hide();
                $('#color_id').removeAttr('required');
                $('#color_id').empty();
                $('#div_meters').hide();
                $('#meters').removeAttr('required');
                $('#div_machine').show();
                $('#machine_id').attr('required', '');
                $('#machine_id').empty();
                $('#comment').removeAttr('required');
                break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                $('#div_machine').show();
                $('#machine_id').attr('required', '');
                $('#machine_id').empty();
                $('#div_comment').show();
                $('#comment').attr('required', '');
                $('#div_product').hide();
                $('#product_id').removeAttr('required');
                $('#product_id').empty();
                $('#div_color').hide();
                $('#color_id').removeAttr('required');
                $('#color_id').empty();
                $('#div_meters').hide();
                $('#meters').removeAttr('required');
                break;
            default:
                $('#div_machine').show();
                $('#machine_id').attr('required', '');
                $('#machine_id').empty();
                $('#div_product').hide();
                $('#product_id').removeAttr('required');
                $('#product_id').empty();
                $('#div_color').hide();
                $('#color_id').removeAttr('required');
                $('#color_id').empty();
                $('#div_meters').hide();
                $('#meters').removeAttr('required');
                $('#comment').removeAttr('required');
                break;
        }

        $('#type_stop').val(type);
    });

};