/**
 * Add dynamic width base on the maximum menu link list 
 * width of the middle footer menu item
 */
function footerMiddleItemWidth(){
    $('.footer-card').each(function(){
        const linksListElem = $(this).find('.links-list__link');
        const linksListArr = linksListElem.map(function(){
            return $(this).outerWidth();
        });
        
        for(i=0; i < linksListArr.length; i++){
            const linksListArrMax = Math.max.apply(Math,linksListArr);
    
            if($(this).index() == 1){
                $(this).css('maxWidth', linksListArrMax + 1);
            }
        }
    });
}

footerMiddleItemWidth();

$(window).on('load', function(){
    footerMiddleItemWidth();
});

$('.footer-card__title a').each(function(){
    $(this).on('click', function(e){
        e.stopPropagation();
    });
});