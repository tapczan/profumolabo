/**
 * Add dynamic width base on the maximum menu link list 
 * width of the middle footer menu item
 */
$('.footer-card').each(function(){
    const linksListElem = $(this).find('.links-list__link');
    const linksListArr = linksListElem.map(function(){
        return $(this).outerWidth();
    });
    
    for(i=0; i < linksListArr.length; i++){
        const linksListArrMax = Math.max.apply(Math,linksListArr);

        if($(this).index() == 1){
            $(this).css('maxWidth', linksListArrMax);
        }
    }
});