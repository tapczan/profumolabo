function footerParalaxEffect() {
    const placeholder = $('.paralax-placeholder');
    const footer = $('.footer-container');

    let placeholderTop
    let ticking
    $(window).on('resize', onResize);

    updateHolderHeight();
    checkFooterHeight();

    function onResize() {
        updateHolderHeight();
        checkFooterHeight();
    }

    function updateHolderHeight() {
        placeholder.css('height', `${footer.outerHeight()}px`)
    }

    function checkFooterHeight() {
        if (footer.outerHeight() > $(window).innerHeight()) { 
            $(window).on('scroll', onScroll);
            footer.css('bottom', '0')
            footer.css('top', 'unset')
        } else {
            $(window).off("scroll", onScroll);
            footer.css('top', 'unset');
            footer.css('bottom', '0');
        }
    }

    function onScroll() {
        placeholderTop = Math.round(placeholder[0].getBoundingClientRect().top) 
        requestTick();
    }

    function requestTick() {
        if (!ticking) requestAnimationFrame(updateBasedOnScroll)
        ticking = true
    }

    function updateBasedOnScroll() {
        ticking = false

        if (placeholderTop <= 0) {
            footer.css('top', `${placeholderTop}px`)
        }
    }
}
  
footerParalaxEffect();