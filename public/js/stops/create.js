let $code_id;

$(function() {
    $code_id = $('#code_id');

    $code_id.on("change", () => {
        const codeId = $code_id.val();
        const url = `/api/codes/${codeId}`;
        $.getJSON(url, function(data) {
            var type = data.type
            $('#type_stop').val(type)
        });
    });
});