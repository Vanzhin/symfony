import $ from 'jquery'

$(function () {
    $('[data-item=likes]').each(function () {
        const $container = $(this);
        $container.on('click', function (e) {
            e.preventDefault();
            $container.prop('disabled', true);
            const slug = $container.data('slug');
            $.ajax({
                url: `/articles/${slug}/like`,
                method: 'POST'
            }).then(function (data) {
                if (data.likes !== 'noUser') {
                    $container.find('[data-item=likesCount]').text(data.likes);
                    $container.find('.like').toggleClass('bi-star-fill bi-star');
                }
                $container.prop('disabled', false);
            })
        })
    })
});