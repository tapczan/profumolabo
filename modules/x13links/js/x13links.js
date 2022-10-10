$(document).ready(function() {
    if (typeof prestashop === 'undefined') {
        $('.breadcrumb .home, #header_logo a:first-of-type, #header_logo, .breadcrumb > a:first-child').attr('href', x13HomepageUrl);
        $('.footer_links a[href="' + baseUri + '"]').attr('href', x13HomepageUrl);
    } else {
        $('#_desktop_logo a:first-of-type, #_mobile_logo a:first-of-type').attr('href', prestashop.urls.pages.index);
        $('#header img.logo').parents('a').attr('href', prestashop.urls.pages.index);
    }
});