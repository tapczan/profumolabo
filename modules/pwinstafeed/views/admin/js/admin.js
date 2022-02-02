/*=============================================================================*/
/*  PW Instagram feed
/*  ---
/*  PRESTAWORKS AB (www.prestaworks.se)
/*=============================================================================*/
jQuery.support.cors = true;
$(document).ready(function(){
    $('.custom-tabs .nav-tabs a').click(function(){
        $(this).parent().addClass('active').siblings().removeClass('active');
        var fieldset_arr = $(this).attr('data-fieldset').split(',');
        var fieldset_dom = $('form.defaultForm .panel');
        fieldset_dom.removeClass('selected');
        $.each(fieldset_arr,function(i,n){
            $('.panel[id^="fieldset_'+n+'"]').addClass('selected');
        });
        $('#pwi-analytics').fadeOut(150);
        $('#custom-tabs:hidden').delay(150).fadeIn(150);
        $('#analytics-toggle').removeClass('active');
    });
    $('.custom-tabs .nav-tabs a').each(function(){
        var fieldset_arr = $(this).attr('data-fieldset').split(',');
        if($.inArray(pwi_refer, fieldset_arr) > -1) {
            $(this).trigger('click');
            return false;
        }
    });
    $('strong.settings-header').parent().addClass('settings-header-parent');
    $('.panel').each(function(){
        $(this).find('strong.settings-header:first').addClass('is-first');
    });
    $('strong.settings-subheader').parent().addClass('settings-subheader-parent');
    $('#analytics-toggle').click(function(){
        $(this).addClass('active');
        $('.nav-tabs li').each(function(){
            $(this).removeClass('active')
        });
        $('#custom-tabs').fadeOut(150);
        $('#pwi-analytics').delay(150).fadeIn(150);
    });
});
