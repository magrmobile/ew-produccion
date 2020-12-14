let $code_id, $div_machine, $div_product, $div_color, $div_meters, $div_comment,
    $machine_id, $product_id, $color_id, $meters, $comment;

$(function() {
    $code_id = $('#code_id');
    $machine_id = $('#machine_id');
    $product_id = $('#product_id');
    $color_id = $('#color_id');
    $meters = $('#meters');
    $comment = $('#comment');
    $div_machine = $('#div_machine');

    $code_id.on("change", () => {
        const codeId = $code_id.val();
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
                    $('#div_color').show();
                    $('#color_id').attr('required', '');
                    $('#div_meters').show();
                    $('#meters').attr('required', '');
                    $('#comment').removeAttr('required');
                    break;
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    $('#div_machine').show();
                    $('#machine_id').attr('required', '');
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
                    $('#div_comment').show();
                    $('#comment').attr('required', '');
                    break;
                default:
                    $('#div_machine').show();
                    $('#machine_id').attr('required', '');
                    $('#div_product').hide();
                    $('#product_id').removeAttr('required');
                    $('#div_color').hide();
                    $('#color_id').removeAttr('required');
                    $('#div_meters').hide();
                    $('#meters').removeAttr('required');
                    $('#comment').removeAttr('required');
                    break;
            }

            console.log(code);

            $('#type_stop').val(type);
        });
    });
});