$(document).ready(function() {
    $('#search_button').click(function() {
        performSearch();
    });

    $('#key_word').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            performSearch();
        }
    });

    $('.delete-form').submit(function(event) {
        event.preventDefault();

        var productId = $(this).find('.product-id').val();
        var confirmation = window.confirm("ID:" + productId + "を消去しますか？");
        if (confirmation) {
            // 確認があればフォームを再度送信して削除を実行
            $(this).unbind('submit').submit();
        }
    });

    $('#search_form').submit(function(event) {
        event.preventDefault();

        var keyword = $('#key_word').val();
        var company = $('#key_company').val();

        $.ajax({
            url: '{{ route("products.index") }}',
            method: 'GET',
            data: {
                key_word: keyword,
                key_company: company
            },
            success: function(response) {
                if (response.trim() === '') {
                    alert('該当する商品は見つかりませんでした。');
                } else {
                    $('#products_table').html(response);
                }
            },
            error: function(error) {
                console.error(error);
                window.alert('検索中にエラーが発生しました。');
            }
        });
    });
});

// ページが維持されるために
function saveSearchParamsAndRedirect(params, detailUrl) {
    sessionStorage.setItem('searchParams', params);
    window.location.href = detailUrl;
}