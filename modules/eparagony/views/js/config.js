/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 *
 * Configuration for admin page only.
 */

$(function() {
   let $pack = $('.eparagony-form-password-pack');
   console.log($pack);

   $pack.each(function (idx, elm) {
       let $elm = $(elm);
       let $eye = $elm.find('.js-eye');
       let $withValue = $elm.find('input[type=hidden]');
       let $censored = $elm.find('input[type=password]');
       $eye.css('position', 'absolute').css('top', '1em').css('right', '1em').css('cursor', 'pointer');
       $eye.show();
       $censored.on('change', function() {
           console.log($censored.val());
           $withValue.val($censored.val());
       });
       $eye.on('click', function(){
           $withValue.attr('type', 'text');
           $censored.remove();
           $eye.remove();
       });
   });
});
