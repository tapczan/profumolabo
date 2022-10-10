function resizeAddClassNav(){
    var winW = $(window).outerWidth();

    if( winW >= 992 ){
        $('.ets_mm_megamenu').removeClass('changestatus');
    }

    if( winW <= 991 ){
        $('.ets_mm_megamenu').addClass('changestatus');
    }
}

resizeAddClassNav();

$(window).on('load', function(){
    resizeAddClassNav();

    $(window).on('resize', function(){
        resizeAddClassNav();
    });
});