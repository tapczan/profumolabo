/**
 * Used by collapsed template (FAQ Page)
 * Converts tabs to accordion or collapse style on mobile responsive
 */
$('.js-trigger-collapsed-mobile').on('click', function () {
    $(this).closest('.collapsed__tab-pane').find('.collapsed__collapse--mobile').toggle();
    $(this).toggleClass('collapsed__collapse--mobile-active');
});

/**
 * Used by collapsed cms template (Information Page)
 * @param {int} cmsArrLangLink - get default links of the language switcher
 * @function cmsLangSwitchHandler - store and update language switcher href depends on what collapse item being clicked
 */
const cmsCurrentCleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
const cmsCollapseTitle = $('.js-collapse-no-tab .collapsed__collapse-title');
const cmsLangSwitcher = $('.language-selector__link');
const cmsUrlParamSearch = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});
const cmsArrLangLink = cmsLangSwitcher.map(function(){
    return $(this).attr('href');
});
var cmsUrlParamValue = cmsUrlParamSearch.contentCollapse;

function cmsLangSwitchHandler(paramUpdate){
    cmsLangSwitcher.each(function(index){
        if (cmsUrlParamValue) {
            $(this).attr('href', cmsArrLangLink[index] + '?contentCollapse=' + paramUpdate)
        }
    });
}

cmsLangSwitchHandler(cmsUrlParamValue);

cmsCollapseTitle.each(function (cmsIndex) {
    $(this).on('click', function () {
        window.history.pushState({ path: cmsCurrentCleanUrl }, '', cmsCurrentCleanUrl  + '?contentCollapse=' + cmsIndex);

        cmsLangSwitchHandler(cmsIndex);

        $(this).toggleClass('collapsed__collapse-title--active');
        $(this).closest('.collapsed__collapse').find('.collapsed__collapse-content').toggle();
    });

    if (cmsUrlParamValue) {
        if (cmsIndex == cmsUrlParamValue) {
            $(this).addClass('collapsed__collapse-title--active');
            $(this).closest('.collapsed__collapse').find('.collapsed__collapse-content').show();
        }
    }
});

/**
 * Used by tab menu cms template (Cooperation Page)
 */
const cmsTabMenu = $('#cms-tab-menu');
const tabMenuLink = $(cmsTabMenu).find('.collapsed__tab-nav .nav-link');
const tabMenuContent = $(cmsTabMenu).find('.collapsed__tab-pane');

$(tabMenuLink).on('click', function () {
    $(tabMenuLink).removeClass('active');
    $(this).addClass('active');
    let tab = $(this).attr('href');
    $(tabMenuContent).removeClass('active');
    $(tab).addClass('active');
    $("html, body").animate({ scrollTop: 0 });
});

if (cmsUrlParamValue == null) {
    cmsUrlParamValue = "influencer_cooperation";
    if (cmsUrlParamValue) {
        $(this).addClass('active');
    } else {
        $(this).removeClass('active');
    }
}

tabMenuLink.each(function () {
    let url = $(this).attr('href');
    let hash = url.replace(/#/g, '');

    if (cmsUrlParamValue == hash) {
        $(this).addClass('active');
    } else {
        $(this).removeClass('active');
    }
});

tabMenuContent.each(function () {
    let url = $(this).attr('id');
    let hash = url.replace(/#/g, '');

    if (cmsUrlParamValue == hash) {
        $(this).addClass('active');
    } else {
        $(this).removeClass('active');
    }
});