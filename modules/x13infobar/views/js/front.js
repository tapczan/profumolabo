$(document).ready(function () {
  function offSetToFixedBanner() {
    var $body = $('body');
    var $counterContainer = $('.x13-counter-container');

    if (!$counterContainer.length) {
      return;
    }

    var isFixed = x13InfoBar_displayStyle == 'fixed' || x13InfoBar_displayStyle == 'fixedBottom';

    if (isFixed && !$('.x13counter_offset').length) {
      if (x13InfoBar_displayStyle == 'fixed') {
        $counterContainer.before('<div class="x13counter_offset"></div>');
      } else if (x13InfoBar_displayStyle == 'fixedBottom') {
        $body.append('<div class="x13counter_offset"></div>');
      }
    }

    var $counterOffset = $('.x13counter_offset');

    if (isFixed && $(window).innerWidth() > 768) {
      if (!$body.hasClass('x13fixed-start')) {
        $body.addClass('x13fixed-start');
      }
      $counterOffset.css('height', $counterContainer.outerHeight());
    } else if (x13InfoBar_displayStyle == 'fixed' && $(window).innerWidth() < 767) {
      $counterOffset.css('height', 0)
    }
  }

  function initInfoBar() {
    var $counterContainer = $('.x13-counter-container');
    if (!($('.x13-js-counter').length > 0)) {
      $counterContainer.addClass('x13countdownStart')
    }

    if ($counterContainer.hasClass('x13-counter-container-animate')) {
      var bgColor = $counterContainer.css('backgroundColor');
      // https://github.com/PimpTrizkit/PJs/wiki/12.-Shade,-Blend-and-Convert-a-Web-Color-(pSBC.js)
      // pSBC(0.1, bgColor, false, true) 4 parametr określa zmiane wartości miedzy funckja logarytmiczna, a liniowa.
      var lighten = pSBC(0.1, bgColor, false, true);
      var darken = pSBC(-0.1, bgColor, false, true);
      var dark = true;
      var time = 3000;
      $counterContainer.css({
        'transition': 'background-color ' + time + 'ms'
      })
      var changeColor = function () {
        setTimeout(function () {
          $counterContainer.css({
            'backgroundColor': dark ? lighten : darken
          });
          dark = !dark;
          changeColor();
        }, time)
      };
      changeColor();
    }

  }

  function initBarMultipleTextBlocks() {
    var $textContainer = $('.counter-multiple-text'),
        $counterContainer = $('.x13-counter-container');

    if (!$textContainer.length) {
      return;
    }

    $('.counter-close-btn').css('top', ($counterContainer.outerHeight()/2-12)+'px');

    var $textBlocks = $('.counter-text');
    var activeClass = 'counter-text-active';

    $textBlocks.each(function (i, el) {
      if (i != 0) {
        $(el).hide();
      } else {
        $(el).addClass(activeClass)
      }
    });

    $textContainer.addClass('counter-multiple-text-initialized');

    var activeIndex = 0;
    var textBlocksLength = $textBlocks.length;
    var animationTime = 800;
    var intervalTime = (parseInt($counterContainer.data('section-interval')) * 1000);

    var animateElements = function () {
      var $activeElement = $($textBlocks[activeIndex]);
      var indexPlus = activeIndex + 1;
      var nextIndex = indexPlus == textBlocksLength ? 0 : indexPlus;
      var $next = $($textBlocks[nextIndex]);

      if ($counterContainer.is(':hover')) {
        return;
      }

      activeIndex = nextIndex;
      $activeElement.fadeOut(animationTime, function () {
        $activeElement.removeClass(activeClass);
        $next.addClass(activeClass);
        $next.fadeIn(animationTime);
      });
    }

    var initChange = function () {
      setInterval(function () {
          animateElements();
      }, (intervalTime + animationTime));
    };

    initChange();
  }

  setTimeout(function () {
    offSetToFixedBanner();
    initBarMultipleTextBlocks();
    initInfoBar();
  }, 100);

  $(document).on('click', '.counter-close-btn', function (e) {
    e.preventDefault();
    $el = $(this);
    $.ajax({
      url: $el.data('url'),
      type: 'POST',
      dataType: 'json',
      data: {
        id_information_bar: parseInt($el.data('infobar'), 10),
        token: $el.data('token'),
        closeInformationBar: 1,
        ajax: 1
      },
    })
      .done(function (json) {
        $('#x13-counter-container').fadeOut(200);
        location.reload();
      });
  });

  $(window).on('resize', offSetToFixedBanner);

  if (typeof x13InfoBar_dateTo !== 'undefined' && x13InfoBar_dateTo !== '0000/00/00 00:00:00') {
    $('.x13-js-counter').x13infocountdown(x13InfoBar_dateTo, function (event) {
      if (event.offset.totalSeconds == 0) {
        if (x13InfoBar_afterEnd === 1) {
          window.location.reload();
        } else {
          $(this).hide();
        }
      }

      var $counterContainer = $('.x13-counter-container');
      var x13counter_theme = $counterContainer.data('theme');
      var x13counter_secLabel = $counterContainer.data('text-sec');
      var x13counter_minLabel = $counterContainer.data('text-min');
      var x13counter_hourLabel = $counterContainer.data('text-hour');
      var x13counter_daysLabel = $counterContainer.data('text-days');
 
      if (x13counter_theme == 'flip') {
        var timer = event.strftime(''
        + '<span class="counter-item"><span class="number day">%D</span><span class="counter-label">' + x13counter_daysLabel + '</span></span>'
        + '<span class="counter-item"><span class="number hrs">%H</span><span class="counter-label">' + x13counter_hourLabel + '</span></span>'
        + '<span class="counter-item"><span class="number min">%M</span><span class="counter-label">' + x13counter_minLabel + '</span></span>'
        + '<span class="counter-item"><span class="number sec">%S</span><span class="counter-label">' + x13counter_secLabel + '</span></span>');

        timer = timer.replace(/(\d)/g, function(n){
          var now = n;
          var bef = parseInt(now) + 1 > 9 ? 0 : parseInt(now) + 1; 
          return '<span class="flip"><span class="flip-top">'+now+'</span><span class="flip-bottom" data-value="'+bef+'"></span><span class="flip-back" data-value="'+bef+'"><span class="flip-bottom" data-value="'+now+'"></span></span></span>'
        });

        if ($(this).attr('data-first') != 'true') {
          $(this).html(timer);
          $(this).attr('data-first', 'true');
        } else {
          x13infoCheck('sec', timer, $(this), event.offset);
          x13infoCheck('min', timer, $(this), event.offset);
          x13infoCheck('hrs', timer, $(this), event.offset);
          x13infoCheck('day', timer, $(this), event.offset);
  
        }
    
      }
      else{
        if (event.offset.totalMinutes == 0) {
          $(this).html(event.strftime(''
            + '<span class="counter-item"><span class="number">%S</span><span class="counter-label">' + x13counter_secLabel + '</span></span>'));
        } else if (event.offset.totalHours == 0) {
          $(this).html(event.strftime(''
            + '<span class="counter-item"><span class="number">%M</span><span class="counter-label">' + x13counter_minLabel + '</span></span>'
            + '<span class="counter-item"><span class="number">%S</span><span class="counter-label">' + x13counter_secLabel + '</span></span>'));
        } else if (event.offset.totalDays == 0) {
          $(this).html(event.strftime(''
            + '<span class="counter-item"><span class="number">%H</span><span class="counter-label">' + x13counter_hourLabel + '</span></span>'
            + '<span class="counter-item"><span class="number">%M</span><span class="counter-label">' + x13counter_minLabel + '</span></span>'
            + '<span class="counter-item"><span class="number">%S</span><span class="counter-label">' + x13counter_secLabel + '</span></span>'));
        } else {
          $(this).html(event.strftime(''
            + '<span class="counter-item"><span class="number">%-D</span><span class="counter-label">' + x13counter_daysLabel + '</span></span>'
            + '<span class="counter-item"><span class="number">%H</span><span class="counter-label">' + x13counter_hourLabel + '</span></span>'
            + '<span class="counter-item"><span class="number">%M</span><span class="counter-label">' + x13counter_minLabel + '</span></span>'
            + '<span class="counter-item"><span class="number">%S</span><span class="counter-label">' + x13counter_secLabel + '</span></span>'));
        }
      }
      if (!$('.x13-counter-container').hasClass('x13countdownStart')) {
        $('.x13-counter-container').addClass('x13countdownStart')
      }

    });
  }

  function pSBC(p, c0, c1, l) {
    let r, g, b, P, f, t, h, i = parseInt, m = Math.round, a = typeof (c1) == "string";
    if (typeof (p) != "number" || p < -1 || p > 1 || typeof (c0) != "string" || (c0[0] != 'r' && c0[0] != '#') || (c1 && !a)) return null;
    if (!this.pSBCr) this.pSBCr = (d) => {
      let n = d.length, x = {};
      if (n > 9) {
        [r, g, b, a] = d = d.split(","), n = d.length;
        if (n < 3 || n > 4) return null;
        x.r = i(r[3] == "a" ? r.slice(5) : r.slice(4)), x.g = i(g), x.b = i(b), x.a = a ? parseFloat(a) : -1
      } else {
        if (n == 8 || n == 6 || n < 4) return null;
        if (n < 6) d = "#" + d[1] + d[1] + d[2] + d[2] + d[3] + d[3] + (n > 4 ? d[4] + d[4] : "");
        d = i(d.slice(1), 16);
        if (n == 9 || n == 5) x.r = d >> 24 & 255, x.g = d >> 16 & 255, x.b = d >> 8 & 255, x.a = m((d & 255) / 0.255) / 1000;
        else x.r = d >> 16, x.g = d >> 8 & 255, x.b = d & 255, x.a = -1
      } return x
    };
    h = c0.length > 9, h = a ? c1.length > 9 ? true : c1 == "c" ? !h : false : h, f = this.pSBCr(c0), P = p < 0, t = c1 && c1 != "c" ? this.pSBCr(c1) : P ? { r: 0, g: 0, b: 0, a: -1 } : { r: 255, g: 255, b: 255, a: -1 }, p = P ? p * -1 : p, P = 1 - p;
    if (!f || !t) return null;
    if (l) r = m(P * f.r + p * t.r), g = m(P * f.g + p * t.g), b = m(P * f.b + p * t.b);
    else r = m((P * f.r ** 2 + p * t.r ** 2) ** 0.5), g = m((P * f.g ** 2 + p * t.g ** 2) ** 0.5), b = m((P * f.b ** 2 + p * t.b ** 2) ** 0.5);
    a = f.a, t = t.a, f = a >= 0 || t >= 0, a = f ? a < 0 ? t : t < 0 ? a : a * P + t * p : 0;
    if (h) return "rgb" + (f ? "a(" : "(") + r + "," + g + "," + b + (f ? "," + m(a * 1000) / 1000 : "") + ")";
  }

})

function x13infoCheck(infoClass, timer, $elm, offset) {


  if ( $($elm.find('.'+infoClass+' .flip')[1]).html() != $($(timer).find('.'+infoClass+' .flip')[1]).html() ) {
    var now =  $($(timer).find('.'+infoClass+' .flip')[1]).find('.flip-top').html();
    var num =  $($(timer).find('.'+infoClass+' .flip')[1]).html();

    if (infoClass == 'hrs' && offset.totalHours % 24 == 23 && now == 3 && offset.minutes == 59 && offset.seconds == 59) {
      num = '<span class="flip-top">'+3+'</span><span class="flip-bottom" data-value="'+0+'"></span><span class="flip-back" data-value="'+0+'"><span class="flip-bottom" data-value="'+3+'"></span></span>';
      $($elm.find('.'+infoClass+' .flip')[1]).html(num);
      $($elm.find('.'+infoClass+' .flip')[1]).removeClass('play');
      $($elm.find('.'+infoClass+' .flip')[1]).addClass('play');
    } else if ((infoClass == 'hrs' && offset.totalHours % 24 < 23 && offset.minutes == 59 && offset.seconds == 59) || infoClass == 'day' || infoClass == 'min' || infoClass == 'sec') {
      $($elm.find('.'+infoClass+' .flip')[1]).html(num);
      $($elm.find('.'+infoClass+' .flip')[1]).removeClass('play');
      $($elm.find('.'+infoClass+' .flip')[1]).addClass('play');
    }

    var numTen =  $($(timer).find('.'+infoClass+' .flip')[0]).html();
    var nowTen =  $($(timer).find('.'+infoClass+' .flip')[0]).find('.flip-top').html();

    if ((infoClass == 'min' || infoClass == 'sec') && now == 9) {
      if (nowTen == 5) {
        var bef = 0;
        numTen = '<span class="flip-top">'+nowTen+'</span><span class="flip-bottom" data-value="'+bef+'"></span><span class="flip-back" data-value="'+bef+'"><span class="flip-bottom" data-value="'+nowTen+'"></span></span>';
      }

      $($elm.find('.'+infoClass+' .flip')[0]).html(numTen);
      $($elm.find('.'+infoClass+' .flip')[0]).removeClass('play');
      $($elm.find('.'+infoClass+' .flip')[0]).addClass('play');
    }

    if (infoClass == 'hrs' && offset.hours >= 9 && offset.totalHours % 24 <= 23 && offset.minutes == 59 && offset.seconds == 59) {
      if (nowTen == 2) {
        var bef = 0;
        numTen = '<span class="flip-top">'+nowTen+'</span><span class="flip-bottom" data-value="'+bef+'"></span><span class="flip-back" data-value="'+bef+'"><span class="flip-bottom" data-value="'+nowTen+'"></span></span>';
      }

      $($elm.find('.'+infoClass+' .flip')[0]).html(numTen);
      $($elm.find('.'+infoClass+' .flip')[0]).removeClass('play');
      $($elm.find('.'+infoClass+' .flip')[0]).addClass('play');
    }

    if (infoClass == 'day' && offset.totalDays >= 9 && offset.minutes == 59 && offset.seconds == 59)  {
      $($elm.find('.'+infoClass+' .flip')[0]).html(numTen);
      $($elm.find('.'+infoClass+' .flip')[0]).removeClass('play');
      $($elm.find('.'+infoClass+' .flip')[0]).addClass('play');
    }
  }
}