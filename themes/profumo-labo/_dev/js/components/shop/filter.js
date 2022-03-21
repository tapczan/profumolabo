/*
* Product shop category page trigger mobile dropdown click
*/
$('.js-filtermobile-slider').on('click', function(){
    $(this).toggleClass('istoggled');
    $('.js-search-filters').slideToggle();
});