jQuery(document).ready(function ($) {
    $('.mabox-rating .star').on('click', function () {
        var rating = $(this).data('value');
        var postId = $(this).closest('.mabox-rating').data('post-id');

        $.ajax({
            url: maboxRating.ajax_url,
            type: 'POST',
            data: {
                action: 'submit_rating',
                post_id: postId,
                rating: rating,
                nonce: maboxRating.nonce,
            },
            success: function (response) {
                if (response.success) {
                    var ratingWidget = $('.mabox-rating[data-post-id="' + postId + '"]');
                    var avg = response.data.average;
                    var count = response.data.count;
                    var stars = ratingWidget.find('.star');
                    stars.each(function (i) {
                        $(this).text(i + 1 <= avg ? '★' : '☆');
                    });
                    ratingWidget.find('.rating-text').text(avg + ' 分 (' + count + ' 人评分)');
                } else {
                    alert(response.data);
                }
            },
            error: function () {
                alert('评分失败，请重试');
            },
        });
    });
});
