/*
* Product shop single page tabs convert to dropdown on mobile
*/
function mobileDropdown(){
    var mobileDropdownSection = $('#arpl-group-13');
    var mobileDropdownElem = $('#arpl-group-13 .nav-tabs');
    var mobileDropdownElemActive = $('#arpl-group-13 .nav-link.active').text();

    if( $('.nav-tabs-mobile-label').length == 0 ){
        mobileDropdownSection.prepend('<h3 class="nav-tabs-mobile-label">' + mobileDropdownElemActive + '</h3>');
    }

    $('#arpl-group-13 .nav-link').each(function(){
        $(this).on('click', function(){
            var activeMobileDropdownText = $(this).text();

            $('.nav-tabs-mobile-label').text(activeMobileDropdownText);
            mobileDropdownElem.hide();
        });
    });

    $('.nav-tabs-mobile-label').on('click', function(){
        mobileDropdownElem.toggle();
    });
}
  
if( $('#arpl-group-13 .nav-item').length > 1 ){
    mobileDropdown();
}else{
    $('#arpl-group-13').addClass('no-tab-available')
}