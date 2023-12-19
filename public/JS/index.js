$(document).ready(function() {

    // 検索フォーム入力時
    $('#searchForm input, #searchForm select').on('input change', function () {
        console.log('検索フォームが変更されました。');
        performSearch();
    });

    // 検索フォームのsubmit
    $('#searchForm').submit(function (event) {
        event.preventDefault();
        console.log('検索フォームが送信されました。');
        performSearch();
    });

    // 検索
    function performSearch() {
        var formData = $('#searchForm').serialize();
        var searchUrl = $('#searchForm').attr('action');
        console.log('検索フォームの内容:', formData);

        $.ajax({
            url: searchUrl,
            method: 'GET',
            data: formData,
            success: function(response) {
                var $response = $(response);
                var tableContent = $response.find('#products_table').html();
                var paginationContent = $response.find('.pagination').html();
                
                $('#products_table').html(tableContent);
                $('.pagination').html(paginationContent);
            },
            error: function (error) {
                console.error(error);
                window.alert('検索中にエラーが発生しました。');
            }
        });
    }

    // ソートの各初期状態
    var sortOrder = {
        '1': 'asc',
        '2': 'asc'
    };
    
    // 商品名・メーカー名ソートボタン
    // 価格とソートボタンが反応しない時があったので、ここを$(document).on('click',～～に変えると解決できました。
    $(document).on('click', '.sort_button',function(e) {
        e.preventDefault();
        console.log('商品名・メーカー名ソートボタンがクリックされました。');
        var sortValue = $(this).data('value');
        var route = $('#productsIndexRoute').val();

        if (sortOrder[sortValue] === 'asc') {
            sortOrder[sortValue] = 'desc';
        } else {
            sortOrder[sortValue] = 'asc';
        }

        var formData = $('#searchForm').serialize();

        var minPrice = $('.min-box-Price').val();
        var maxPrice = $('.max-box-Price').val();
        var minStock = $('.min-box-Stock').val();
        var maxStock = $('.max-box-Stock').val();

        formData += '&minPrice=' + minPrice + '&maxPrice=' + maxPrice +
            '&minStock=' + minStock + '&maxStock=' + maxStock;

        $.ajax({
            url: route,
            method: 'GET',
            data: formData + '&sort=' + sortValue + '&order=' + sortOrder[sortValue],
            success: function(response) {
                var $response = $(response);
                var tableContent = $response.find('#products_table').html();
                var paginationContent = $response.find('.pagination').html();

                $('#products_table').html(tableContent);
                $('.pagination').html(paginationContent);
            },
            error: function(error) {
                console.error(error);
                window.alert('ソート中にエラーが発生しました。');
            }
        });
    });

    // 価格のソート
    $('.price-sort').on('click', function(e) {
        e.preventDefault();
        console.log('価格ソートボタンがクリックされました。');
        var minPrice = $('.min-box-Price').val();
        var maxPrice = $('.max-box-Price').val();
        var formData = $('#searchForm').serialize();

        formData += '&minPrice=' + minPrice + '&maxPrice=' + maxPrice;

        sendSortRequest(formData, $(this).data('value'));
    });

    // 在庫のソート
    $('.stock-sort').on('click', function(e) {
        e.preventDefault();
        var minStock = $('.min-box-Stock').val();
        var maxStock = $('.max-box-Stock').val();
        var formData = $('#searchForm').serialize();

        formData += '&minStock=' + minStock + '&maxStock=' + maxStock;

        sendSortRequest(formData, $(this).data('value'));
    });

    function sendSortRequest(formData, sortValue) {
    
        var route = $('#productsIndexRoute').val();
    
        $.ajax({
            url: route,
            method: 'GET',
            data: formData + '&sort=' + sortValue + '&order=' + sortOrder,
            success: function(response) {
                var $response = $(response);
                var tableContent = $response.find('#products_table').html();
                var paginationContent = $response.find('.pagination').html();
    
                $('#products_table').html(tableContent);
                $('.pagination').html(paginationContent);
            },
            error: function(error) {
                console.error(error);
                window.alert('ソート中にエラーが発生しました。');
            }
        });
    }

    // 消去
    $(document).on('submit', '.delete-form', function(event) {
        event.preventDefault();

        var form = $(this);
        var productId = form.find('.product-id').val();
        var confirmation = window.confirm("ID:" + productId + "を消去しますか？");

        if (confirmation) {
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // 削除成功時の処理
                    form.closest('tr').remove();
                    alert('商品を削除しました。');
                },
                error: function(error) {
                    console.error(error);
                    window.alert('エラーが発生しました。');
                }
            });
        }
    });
});

// これがないと詳細ボタンなどが押せない
function saveSearchParamsAndRedirect(params, detailUrl) {
    sessionStorage.setItem('searchParams', params);
    window.location.href = detailUrl;
}
