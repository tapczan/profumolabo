<div class="m-b-1 m-t-1">
    <h2>createIT Custom Field</h2>

    <fieldset class="form-group" id="createit-customfield-form-group">
        {if empty($custom_fields)}
            Add custom field.
        {else}
            {foreach $createit_custom_fields as $createit_custom_field}

                <div class="row createit-customfield" id="createit-customfield-{$createit_custom_field@iteration}">

                    <input type="hidden" value="{$createit_custom_field@iteration}" name="createit_custom_field[{$createit_custom_field@iteration}][id]" class="createit_custom_field_id">

                    <div class="col-lg-12 col-xl-6">
                        <label class="form-control-label">{$createit_custom_field.label_name}</label>
                        <input class="form-control createit_custom_field_content" name="createit_custom_field[{$createit_custom_field@iteration}][value]" type="text" value="{$createit_custom_field.content}">
                    </div>

                    <div class="col-lg-11 col-xl-6">

                        <fieldset class="form-group mb-0">

                            <label class="form-control-label" for="createit_custom_field[{$createit_custom_field@iteration}][placeholder]">Custom Field Name (snake_case)</label>
                            <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$createit_custom_field@iteration}][placeholder]" disabled="disabled" type="text" value="{$createit_custom_field.field_name}">

                            <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$createit_custom_field@iteration}][name]" type="hidden" value="{$createit_custom_field.id}">
                        </fieldset>

                    </div>
                </div>

            {/foreach }
        {/if}

    </fieldset>

    <div class="clearfix"></div>

</div>