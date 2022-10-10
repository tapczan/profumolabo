/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 *
 * Configuration for admin page only.
 */

$(function() {
    var $mailButton = $('#eparagony-force-email')
    if ($mailButton.length) {
        $mailButton.on('click', function(e) {
            e.preventDefault();
            var url = $mailButton.attr('href');
            $.ajax({
                url: url
            }).done(function (data) {
                alert(data);
            });
        });
    }
});

$(function() {
    var $forceDownloadButtons = $('a.js-eparagony-force-download')
    if ($forceDownloadButtons.length) {
        $forceDownloadButtons.on('click', function(e) {
            e.preventDefault();
            $forceDownloadButtons.css('visibility', 'hidden');
            var url = $(e.target).attr('href');
            $.ajax({
                url: url
            }).done(function () {
                location.reload();
            });
        });
    }
});
