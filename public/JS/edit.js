function submitForm() {
    $.ajax({
        url: $('#editForm').attr('action'),
        type: 'POST',
        data: $('#editForm').serialize(),
        success: function(response) {
            console.log("成功です。");
        },
        error: function(xhr, status, error) {
            console.log("エラーです。");
        }
    });
}
