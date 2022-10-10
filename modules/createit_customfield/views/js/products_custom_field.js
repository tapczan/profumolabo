$(document).ready(function (){

    $('#createit_custom_field').on('keyup', '#createit_custom_field_field_name', function() {

        var createit_custom_field_field_name_val = $(this).val();

        $(this).val(convert_to_snake_case(createit_custom_field_field_name_val));

    });



    $('#createit-customfield-form-group').on('click', '.add_custom_field_repeat_btn', function (e){

        var id = $(e.target).closest('button').attr('id');

        var wrapperLength = $('#createit_customfield_left_input_wrapper input').length;

        var newWrapperId = wrapperLength + 1;

        var newForm =  $('#createit_customfield_left_input_wrapper input').first().clone();
        var newFormHidden =  $('input[name="createit_custom_field['+wrapperLength+'][id]"]').clone();

        $(newForm).attr('name','createit_custom_field['+newWrapperId+'][value]');
        $(newFormHidden).attr('name','createit_custom_field['+newWrapperId+'][id]')

        $(newForm).appendTo('#createit_customfield_left_input_wrapper');
        $(newFormHidden).appendTo('#createit_customfield_left_input_wrapper');

        console.log(wrapperLength);

    });

    function convert_to_snake_case(string) {
        return string.charAt(0).toLowerCase() + string.slice(1) // lowercase the first character
            .replace(/\W+/g, " ") // Remove all excess white space and replace & , . etc.
            .replace(/([a-z])([A-Z])([a-z])/g, "$1 $2$3") // Put a space at the position of a camelCase -> camel Case
            .split(/\B(?=[A-Z]{2,})/) // Now split the multi-uppercases customerID -> customer,ID
            .join(' ') // And join back with spaces.
            .split(' ') // Split all the spaces again, this time we're fully converted
            .join('_') // And finally snake_case things up
            .toLowerCase() // With a nice lower case
    }
});