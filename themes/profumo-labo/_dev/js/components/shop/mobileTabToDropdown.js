/*
* Product shop single page tabs convert to dropdown on mobile
*/
function mobileDropdown(){
    var mobileDropdownSection = $('.page-product .arpl-group-tabbed');

    mobileDropdownSection.each(function(){
        var mobileDropdownElem = $(this).find('.nav-tabs');
        var mobileDropdownElemLink = $(this).find('.nav-link');
        var mobileDropdownElemActive = $(this).find('.nav-link.active').text();

        if( $(this).find('.nav-tabs-mobile-label').length == 0 ){
            $(this).prepend('<h3 class="nav-tabs-mobile-label">' + mobileDropdownElemActive + '</h3>');
        }

        mobileDropdownElemLink.each(function(){
            $(this).on('click', function(){
                var activeMobileDropdownText = $(this).text();

                $(this).closest('.arpl-group-tabbed').find('.nav-tabs-mobile-label').text(activeMobileDropdownText);
                mobileDropdownElem.hide();
            });
        });

        $(this).find('.nav-tabs-mobile-label').on('click', function(){
            mobileDropdownElem.toggle();
        });
    });
}

if( $('.page-product .arpl-group-tabbed .nav-item').length > 1 ){
    mobileDropdown();
}else{
    $('.page-product .arpl-group-tabbed').addClass('no-tab-available');
}