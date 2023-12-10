$(document).ready(function() {

    // 検索フォーム入力時
    $('#searchForm input, #searchForm select').on('input change', function () {
        performSearch();
    });

    // 検索フォームのsubmit
    $('#searchForm').submit(function (event) {
        event.preventDefault();
        performSearch();
    });

    // 検索
    function performSearch() {
        var formData = $('#searchForm').serialize();
        var searchUrl = $('#searchForm').attr('action');

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
    $('.sort_button').click(function(e) {
        e.preventDefault();
        var sortValue = $(this).data('value');
        var route = $('#productsIndexRoute').val();
    
        // 連続クリック時に昇順・降順を切り替える
        if (sortOrder[sortValue] === 'asc') {
            sortOrder[sortValue] = 'desc';
        } else {
            sortOrder[sortValue] = 'asc';
        }
    
        $.ajax({
            url: route,
            method: 'GET',
            data: {
                sort: sortValue,
                order: sortOrder[sortValue]
            },
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

    // 消去
    $('.delete-form').submit(function(event) {
        event.preventDefault();

        var productId = $(this).find('.product-id').val();
        var confirmation = window.confirm("ID:" + productId + "を消去しますか？");
        if (confirmation) {
            $(this).unbind('submit').submit();
        }
    });
});

function saveSearchParamsAndRedirect(params, detailUrl) {
    sessionStorage.setItem('searchParams', params);
    window.location.href = detailUrl;
}
