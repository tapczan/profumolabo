function rolloverImages() {
    $('.product-miniature__thumb').each(function(){
        let newSrc = $(this).find('.rollover-images').data('rollover');
        if(newSrc == 0) return;
        let oldSrc;

        $(this).on("mouseover", function() {
            oldSrc = $(this).find('.rollover-images').attr('src');
            $(this).find('.rollover-images').attr('src', newSrc).stop(true,true);
            $(this).css('background', '#f4f4f4');
        }), 

        $(this).on('mouseout', function() {
            $(this).find('.rollover-images').attr('src', oldSrc).stop(true,true);
            $(this).css('background', 'none');
        });
    });
}

rolloverImages();