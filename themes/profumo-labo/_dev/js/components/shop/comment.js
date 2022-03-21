/*
* Comment section close
*/
$('.js-comment-close').on('click', function(){
    $('.js-comment-form').slideToggle();
    $(this).toggleClass('product-comment__close--notactive')
});

/*
* Clear comment input fields and close popup form on submit
*/
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutationRecord) {
        setTimeout(() => {
            $('.js-trigger-click-submit')[0].click();
            $('.js-input-comment, .js-textarea-comment').val('');
        }, 100);
    });    
});

const targetElement = document.getElementsByClassName('js-comment-alert')[0];

if( targetElement ){
    observer.observe(targetElement, { attributes : true, attributeFilter : ['style'] });
}