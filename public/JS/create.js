$(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log("成功です。");
                window.alert("登録完了しました。");
                form[0].reset();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    }
                    window.alert(errorMessage);
                } else {
                    window.alert("エラーが発生しました。");
                }
                console.log("エラーです。");
            }
        });
    });
});
