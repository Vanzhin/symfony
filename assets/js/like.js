import $ from 'jquery'

$(function () {
    $('[data-item=likes]').each(function () {
        const $container = $(this);
        $container.on('click', function (e){
            e.preventDefault();
            const type = $container.data('type')
            const slug = $container.data('slug')
            $.ajax({
                url: `/articles/${slug}/like/${type}`,
                method: 'POST'
            }).then(function (data){
                $container.data('type', type === 'like' ? 'dislike' : 'like')
                $container.find('.like').toggleClass('bi-star-fill bi-star');
                $container.find('[data-item=likesCount]').text(data.likes);

            })
        })
    })
});