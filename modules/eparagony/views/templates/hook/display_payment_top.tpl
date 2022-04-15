{**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 *}

<div id="eparagony_provide_phone" style="display: none; margin-bottom: 1em;">
    <div>
        {l s='Please provide phone number.' d='Modules.Eparagony.Eparagony'}
    </div>
    <input type="tel">
    <div class="error" style="display: none; color: red;">
        {l s='Provided phone is invalid for Poland.' d='Modules.Eparagony.Eparagony'}
    </div>
</div>

<script>
    addEventListener('DOMContentLoaded', function(ev) {
        /* We assume there is jQuery. */

        var telephoneSet;  /* Not defined yet. */
        var $buttonSet; /* Not defined yet. */

        var tryChangeButtonState = function () {
            if (telephoneSet) {
                if ($buttonSet.hasClass('eparagony-delayed-display')) {
                    $buttonSet.removeClass('disabled');
                    $buttonSet.prop('disabled', false);
                }
                $buttonSet.removeClass('eparagony-delayed-display');
            } else {
                if (!$buttonSet.hasClass('disabled')) {
                    $buttonSet.addClass('disabled').addClass('eparagony-delayed-display');
                    $buttonSet.prop('disabled', true)
                }
            }
        };

        var armObserver = function () {
            $buttonSet = $('#payment-confirmation .btn');
            if ($buttonSet.length) {
                var observer = new MutationObserver(tryChangeButtonState);
                observer.observe($buttonSet[0], { attributeFilter: ['class'] });
            } else {
                console.log('Cannot find button.');
            }
        };

        var armSubmitPhone = function($box) {
            var $input = $box.find('input');
            var $error = $box.find('.error');
            $input.on('change', function (e) {
                jQuery.ajax({
                    url: '{$urlSetPhone|escape:'html':'UTF-8'}',
                    data: {
                        telephone: $input.val()
                    }
                }).done(function(data) {
                    console.log(data);
                    telephoneSet = true;
                    $error.hide();
                    tryChangeButtonState();
                }).fail(function () {
                    telephoneSet = false;
                    $error.show();
                    tryChangeButtonState();
                });
            });
        };

        var getAddress = function () {
            jQuery.ajax({
                url: '{$urlCheck|escape:'html':'UTF-8'}',
            }).done(function(data) {
                if (!data['phone_preferred']) {
                    /* This very JavaScript code will not be loaded if we do not need the number. */
                    telephoneSet = false;
                    var $box = $('#eparagony_provide_phone');
                    armSubmitPhone($box);
                    armObserver();
                    $box.show();
                }
            });
        };

        getAddress();

    });
</script>
