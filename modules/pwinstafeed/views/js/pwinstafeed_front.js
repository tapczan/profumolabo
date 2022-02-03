//  ======================================================================
//  Functions
//  ======================================================================

    //  Setup the modal functionality
    //  ------------------------------------------------------------------
    function pwipModal() {
        $('.pwip__feed__entry__link').fancybox({
            'width'             : '600px',
            'height'            : '680px',
            'autoScale'         : true,
            'transitionIn'      : 'none',
            'transitionOut'     : 'none',
            'type'              : 'iframe'
        });
    }



    //  The actual request
    //  ------------------------------------------------------------------

    function pwipRun() {
        var pwipMedia = '';
        var pwipTarget = $('#pwip__feed');
        var pwipLoader = $('#pwip__loadmore');
        $.ajax({
            type:     'GET',
            url:      pwip_url,
            data:     { format: 'json' },
            dataType: 'jsonp',
            error: function(result) {
            },
            success: function(result) {
                if (result.data.length) {
                    for(var i = 0; i < result.data.length; i++) {
                        var object = result.data[i];
                        pwipMedia += '<div class="pwip__feed__entry pwi-xs-'+pwi_pagegrid_xs+' pwi-sm-'+pwi_pagegrid_sm+' pwi-md-'+pwi_pagegrid_md+' pwi-lg-'+pwi_pagegrid_lg+' pwi-xl-'+pwi_pagegrid_xl+'">';
                            pwipMedia += '<div class="pwip__feed__entry__inner">';
                            if (pwi_pagemodal) {
                            pwipMedia += '<a href="'+object.link+'embed" class="pwip__feed__entry__link rel="nofollow" target="_blank">';
                            } else {
                            pwipMedia += '<a href="'+object.link+'" class="pwip__feed__entry__link rel="nofollow" target="_blank">';
                            }
                                pwipMedia += '<div class="pwip__feed__hover" style="background-color: '+pwi_pagebgcolor+'; color: '+pwi_pagefgcolor+';">';
                                    if ((object.likes.count > 0) || (object.comments.count > 0)) {
                                    pwipMedia += '<div class="pwip__feed__entry__meta">';
                                        if (pwip_likes == 1 && object.likes.count > 0) {
                                        pwipMedia += '<span class="pwip__feed__entry__likes"><i class="material-icons">&#xE87D;</i>'+object.likes.count+'</span>'; }
                                        if (pwip_comments == 1 && object.comments.count > 0) {
                                        pwipMedia += '<span class="pwip__feed__entry__comments"><i class="material-icons">&#xE0B7;</i>'+object.comments.count+'</span>'; }
                                    pwipMedia += '</div>';
                                } else {
                                    pwipMedia += '<i class="icon-zoom></i>"'
                                }
                                pwipMedia += '</div>';
                                pwipMedia += '<img class="pwip__feed__entry__media img-responsive" src="'+object.images.low_resolution.url+'">';
                            pwipMedia += '</a>';
                            pwipMedia += '</div>';
                        pwipMedia += '</div>';
                    }
                }
                pwipTarget.append(pwipMedia);
                if (pwi_pagemodal) {
                    pwipModal();
                }
                pwip_url = result.pagination.next_url;
                if (typeof(pwip_url) == 'undefined') {
                    pwipLoader.remove();
                }
                else {
                    pwipLoader.show();
                }
            }
        });
    }










//  ======================================================================
//  Run everything
//  ======================================================================

    $(document).ready(function(){
        pwipRun(pwip_url);
    });
