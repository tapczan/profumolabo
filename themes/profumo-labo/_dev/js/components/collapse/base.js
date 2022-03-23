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
 */
const cmsCurrentCleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
const cmsUrlParamSearch = new Proxy(new URLSearchParams(window.location.search), {
get: (searchParams, prop) => searchParams.get(prop),
});
var cmsUrlParamValue = cmsUrlParamSearch.contentCollapse;
const cmsCollapseTitle = $('.js-collapse-no-tab .collapsed__collapse-title');

cmsCollapseTitle.each(function () {
    const cmsCollapseText = $(this).text();

    $(this).on('click', function () {
        window.history.pushState({ path: cmsCurrentCleanUrl }, '', cmsCurrentCleanUrl);
        $(this).toggleClass('collapsed__collapse-title--active');
        $(this).closest('.collapsed__collapse').find('.collapsed__collapse-content').toggle();
    });

    if (cmsUrlParamValue) {
        const cmsOrigParameterValue = cmsUrlParamValue.replace(/-/g, ' ').toUpperCase();

        if (cmsCollapseText == cmsOrigParameterValue) {
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

if (cmsUrlParamValue == null) {
    let EN = "#influencer_cooperation";
    let PL = "#influencerami";
    $(EN).addClass('active');
    $(PL).addClass('active');
    $('.nav-link[href="' + EN + '"]').addClass('active');
    $('.nav-link[href="' + PL +'"]').addClass('active');
}
