/* global $ */
$(document).ready(function () {
    var $searchInput = $('.js-search-input');
    var ajaxUrl = $('[data-search-controller-url]').data('search-controller-url');
    var $body = $('body');
    var $inputForm = $searchInput.closest('form');

    var search = new SearchInput({
        searchUrl: ajaxUrl,
        $input: $searchInput,
        appendTo: '.js-search-form',
        perPage: 12,
        onResult: function(e) {
            $body.addClass('header-dropdown-open search-result-open');

            $('.header__search').addClass('search-loading');

            if($('.header__inner .js-search-input').val == ''){
                $('.header__search').removeClass('search-loading');
            }

            if($('.header__nav .js-search-input').val == ''){
                $('.header__search').removeClass('search-loading');
            }
        },
        onResultAfter: function(e) {
            prestashop.pageLazyLoad.update();

            $('.header__search .js-search-result .search-result__products').on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
                setTimeout(function(){
                    if ( $('.header__search .js-search-result .search-result__products').hasClass('slick-initialized')) {
                        if(slick.slideCount < 4 ){
                            $('.header__search .js-search-result .search-result__products').slick('unslick');
                        }
                    }    
                }, 500); 
            });

            $('.header__search .js-search-result .search-result__products').slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 4,
                dots: true,
                arrow: false,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    }
                ]
            });
        },
        onRemoveResult: function(e) {
            $body.removeClass('header-dropdown-open search-result-open');
        },
        beforeSend: function(e) {
            // console.log('BEFORE SEND ' + e);
        },
        onType: function(e) {
            // console.log('ON TYPE ' + e);
        }
    });

    $body.on('click', function(e) {
        var $target = $(e.target);
        if ($body.hasClass('search-result-open') && $target != $inputForm && !$target.closest($inputForm).length) {
            $body.removeClass('header-dropdown-open search-result-open');
            search.removeResults();
            $('.header__search').removeClass('search-loading');
        }

        $('.header__search').removeClass('search-loading');
    })

});


var SearchInput = function({
    searchUrl,
    $input,
    onType,
    onResult,
    onResultAfter,
    beforeSend,
    onRemoveResult,
    perPage,
    appendTo,
    min,
    timeout
}) {
    this.searchUrl = searchUrl;
    this.$input = $input;
    this.appendTo = appendTo;
    this.$appendTo = $(appendTo);
    this.onType = onType || function() {};
    this.onResult = onResult || function() {};
    this.onResultAfter = onResultAfter || function() {};
    this.onRemoveResult = onRemoveResult || function() {};
    this.beforeSend = beforeSend || function() {};
    this.min = min || 3;
    this.perPage = perPage || 10;
    this.timeout = timeout || 300;
    this.$resultBox = false;

    var typeTimeout = false;
    var self = this;
    var resultBoxClass = 'js-search-result';

    this.$input.on('keyup', function() {
        if(typeTimeout) {
            clearTimeout(typeTimeout);
        }

        var str = getInputString();

        self.onType({
            input: self.$input,
            appendTo: self.appendTo,
            s: str
        })

        if(!handleResultIfStringMatchMinLength(str)) {
            resetResultIfExits();
            return;
        }

        typeTimeout = setTimeout(function() {
            handleAjax(str);
        }, self.timeout)
    })

    function handleAjax(str) {
        $.ajax({
            url: self.searchUrl,
            type: 'POST',
            dataType: 'json',
            data: {
              s: str,
              perPage: self.perPage
            },
            beforeSend: function() {
                self.beforeSend({
                    input: self.$input,
                    appendTo: self.appendTo,
                    s: str
                });
                $('.header__search').addClass('search-loading');
            },
            success: function(data) {
                resetResultIfExits();

                self.onResult({
                    input: self.$input,
                    appendTo: self.appendTo,
                    s: str,
                    data: data
                });

                var $el = $('<div>').addClass(resultBoxClass).html(data.content);
                self.$appendTo.append($el);
                self.$resultBox= $('.' + resultBoxClass);

                self.onResultAfter({
                    input: self.$input,
                    appendTo: self.appendTo,
                    s: str,
                    data: data
                });

                setTimeout(function(){
                    $('.header__search').removeClass('search-loading');   
                }, 1000); 
            }
          })
          .fail(function(err) {
            error(err);
        });
    }

    function handleResultIfStringMatchMinLength(str) {
        return str.length >= self.min;
    }

    function getInputString() {
        return self.$input.val();
    }

    this.removeResults = function() {
        resetResultIfExits();
    }

    function resetResultIfExits() {
        if(self.$resultBox) {
            self.onRemoveResult();
            self.$resultBox.remove();
        }
    }
}
