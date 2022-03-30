/**
 * 2015-2021 Bonpresta
 *
 * Bonpresta Instagram Carousel Social Feed Photos
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    Bonpresta
 *  @copyright 2015-2021 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function () {
    var data = {};
    let i = 1;
    var imgName;

    $('.form-group.display').hide();
    $(document).on('click', '#BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL_off', function () {
        $('.form-group.display').hide();
    });

    $(document).on('click', '#BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL_on', function () {
        $('.form-group.display').show();
    });

    if ($('input[name="BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL"]:checked').val() == 1) {
        $('.form-group.display').show();
    }

    $( "#bon-upload" ).click(function() {
        if (BONINSTAGRAMCAROUSEL_TYPE == 'tagged') {
            $.get("https://images" + ~~(Math.random() * 9999) + "-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=https://www.instagram.com/explore/tags/" + user_tag + "/", function (json) {
                if (json) {
                    var pattern = /_sharedData = ({.*);<\/script>/m,
                        json = JSON.parse(pattern.exec(json)[1]),
                        edges = json.entry_data.TagPage[0].graphql.hashtag.edge_hashtag_to_media.edges;
                    var count = 0;

                    $.each(edges, function name(j, examp) {
                        if (count >= BONINSTAGRAMCAROUSEL_LIMIT) {
                            return false;
                        }
                        count++;
                        let img = encodeURI(examp.node.thumbnail_src);
                        imgName = "sample-";
                        imgName = imgName + i;
                        i++;
                        $('img.pull-left').removeClass('hidden');
                        data = {
                            url: img,
                            imgName: imgName
                        }
                        $.ajax({
                            type: "POST",
                            data: JSON.stringify(data),
                            dataType: "JSON",
                            url: base_dir + 'controllers/back/insta_parser.php',

                            success: function(msg) {
                                // console.log(msg)
                            },
                            failure : function(msg) {
                            }
                        });

                        $('.boninsta.alert-success').removeClass('hidden');
                        $('.boninsta.alert-warning').addClass('hidden');
                    });
                    setTimeout(() => {
                        setTimeInst();
                    }, 1500);
                } else {
                    $('.boninsta.alert-success').addClass('hidden');
                    $('.boninsta.alert-warning').removeClass('hidden');
                }
            });
        } else if (BONINSTAGRAMCAROUSEL_TYPE == 'user') {
            $.get(
                'https://images' +
                ~~(Math.random() * 9999) +
                '-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=https://www.instagram.com/'+ user_id + '/',
                function (json) {
                    var pattern = /_sharedData = ({.*);<\/script>/m,
                        json = JSON.parse(pattern.exec(json)[1]),
                        edges =
                            json.entry_data.ProfilePage[0].graphql.user
                                .edge_owner_to_timeline_media.edges;
                    var count = 0;
                    $.each(edges, function (n, edge) {

                        let img = encodeURI(edge.node.thumbnail_src);
                        imgName = "sample-";
                        imgName = imgName + i;
                        i++;
                        $('img.pull-left').removeClass('hidden');
                        data = {
                            url: img,
                            imgName: imgName
                        }
                        $.ajax({
                            type: "POST",
                            data: JSON.stringify(data),
                            dataType: "JSON",
                            url: base_dir + 'controllers/back/insta_parser.php',

                            success: function(msg) {
                            },
                            failure : function(msg) {
                            }
                        });

                        $('.boninsta.alert-success').removeClass('hidden');
                        $('.boninsta.alert-warning').addClass('hidden');
                    });
                    setTimeout(() => {
                        setTimeInst();
                    }, 1500);
                }
            );
        }

        $('.boninsta.alert-success').addClass('hidden');
        function setTimeInst() {
            $('img.pull-left').addClass('hidden');
        }
    });
});