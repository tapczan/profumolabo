function matchHeightScripts(){
    $('.mega-menu-header-kobieta .mm_columns_li').matchHeight();
    $('.mega-menu-header-mezczyzna .mm_columns_li').matchHeight();
    $('.mega-menu-header-kolekje .mm_columns_li').matchHeight();
    $('.mega-menu-header-marki .mm_columns_li').matchHeight();
    $('.mega-menu-header-kontakt .mm_columns_li').matchHeight();
    $('#phblogrecentposts .card-block h3').matchHeight();
    $('#phblogrecentposts .card-block p').matchHeight();
}

matchHeightScripts();

$(window).on('load', function(){
    matchHeightScripts();
});