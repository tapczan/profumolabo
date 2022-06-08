$(document).ready(function (){

    $('#createit-productlist').keyup(function() {

        var searchValue = this.value;

        var inputField = $(this);

        $(this).autocomplete({

            source: function (request, response) {

                    $.ajax({
                        url: window.baseAdminDir  + 'index.php',
                        success: function (data) {
                            // console.log(data)
                            response(data);
                        },
                        dataType: 'json',
                        data: {
                            controller: 'AdminProducts',
                            ajax: true,
                            action: 'productsList',
                            forceJson: true,
                            disableCombination: true,
                            exclude_packs: false,
                            excludeVirtuals: 0,
                            limit:20,
                            token: window.token,
                            q: searchValue,
                        },
                    });

                },

                select: function (event, ui) {
                    inputField.val( ui.item.name );
                    inputField.siblings('input[name=createit_productfield2]').val(ui.item.id);
                    return false;
                },
                minLength: 1

        }).data("ui-autocomplete")._renderItem = function (ul, item) {


            var $li = $('<li>'),
                $img = $('<img>');


            $img.attr({
                src: item.image,
                alt: item.name,
                height: '50px'
            });

            $li.attr('data-value', item.name);
            $li.append('<a href="javascript:void(0);">');
            $li.find('a').append($img).append(item.name);

            return $li.appendTo(ul);


        };


    });

});