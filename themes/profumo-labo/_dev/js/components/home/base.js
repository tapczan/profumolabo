/**
 * Youtube Aspect Ratio
 * blockcontentvideo homepage section
 */
const $allVideos = $(".blockcontentvideo__item iframe");
const $fluidEl = $(".blockcontentvideo__item");

$allVideos.each(function() {
    $(this)
    .data('aspectRatio', this.height / this.width)
    .removeAttr('height')
    .removeAttr('width');
});

$(window).resize(function() {
    const newWidth = $fluidEl.width();

    $allVideos.each(function() {
        const $el = $(this);
        $el.width(newWidth).height((newWidth * $el.data('aspectRatio')));
    });
}).resize();