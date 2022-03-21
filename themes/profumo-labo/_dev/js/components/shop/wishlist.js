$(document).ready(function(){
   /*
    * Reload page on success wishlist
    */
    const currentWindowURL = window.location.href;
    const observerWishlist = new MutationObserver((mutations) => { 
        mutations.forEach((mutation) => {
            const el = mutation.target;
            if ((!mutation.oldValue || !mutation.oldValue.match(/\bisActive\b/)) 
                && mutation.target.classList 
                && mutation.target.classList.contains('isActive')){
                const wishlistToastText = $('.wishlist-toast').text().trim();
                const wishlistCounterTop = $('.js-wishlist-counter-top');
                const wishlistCounterNav = $('.js-wishlist-counter-nav');
                const wishlistButtonAdd = $('.wishlist-button-add');
                let wishlistTopAdd;

                if(wishlistToastText == 'Product added' || wishlistToastText == 'Produkt dodany'){
                    wishlistTopAdd = parseInt(wishlistCounterTop.text()) + parseInt(1);
                    wishlistCounterTop.text(wishlistTopAdd);
                    wishlistCounterNav.text(wishlistTopAdd);
                    wishlistButtonAdd.addClass('wishlist-button-wait');

                    setTimeout(() => {
                        wishlistButtonAdd.removeClass('wishlist-button-wait');
                    }, 3000);
                }
                
                if(wishlistToastText == 'Product successfully removed'){
                    wishlistTopAdd = parseInt(wishlistCounterTop.text()) - parseInt(1);
                    wishlistCounterTop.text(wishlistTopAdd);
                    wishlistCounterNav.text(wishlistTopAdd);
                    wishlistButtonAdd.addClass('wishlist-button-wait');

                    setTimeout(() => {
                        wishlistButtonAdd.removeClass('wishlist-button-wait');
                    }, 3000);
                }
                
                if(wishlistToastText == 'List has been removed' || wishlistToastText == 'Lista została usunięta'){
                    setTimeout(() => {
                        $(location).prop('href', currentWindowURL);
                    }, 500);  
                }
            }
        });
    });

    const targetElementWishlist = document.getElementsByClassName('wishlist-toast')[0];
    const targetElementWishlistJquery = $('.wishlist-toast');

    if(targetElementWishlistJquery.length > 0){
        observerWishlist.observe(targetElementWishlist, { 
            attributes: true, 
            attributeOldValue: true, 
            attributeFilter: ['class'] 
        });
    } 
});