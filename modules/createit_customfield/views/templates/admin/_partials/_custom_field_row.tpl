{if $createit_custom_field_empty eq true}
    <div class="row createit-customfield" id="createit-customfield-0">
{else}
    <div class="row createit-customfield" id="createit-customfield-{$createit_custom_field.id_createit_customfield}">
{/if}


        {if $createit_custom_field_empty eq true}
            <input type="hidden" value="0" name="createit_custom_field[{$createit_custom_field.id_createit_customfield}][id]" class="createit_custom_field_id">
        {else}
            <input type="hidden" value="{$createit_custom_field.id_createit_customfield}" name="createit_custom_field[{$createit_custom_field@iteration}][id]" class="createit_custom_field_id">
        {/if}


    <div class="col-lg-12 col-xl-6">
        <label class="form-control-label">Custom Field</label>

        {if $createit_custom_field_empty eq true}
            <input class="form-control createit_custom_field_content" name="createit_custom_field[0][value]" type="text" value="">
        {else}
            <input class="form-control createit_custom_field_content" name="createit_custom_field[{$createit_custom_field@iteration}][value]" type="text" value="{$createit_custom_field.content}">
        {/if}


    </div>
    <div class="col-lg-11 col-xl-5">
        <fieldset class="form-group mb-0">
            <label class="form-control-label">Custom Field Name (snake_case)</label>

            {if $createit_custom_field_empty eq true}
                <input class="form-control createit_custom_field_field_name" name="createit_custom_field[0][name]" type="text" value="">
            {else}
                <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$createit_custom_field@iteration}][name]" type="text" value="{$createit_custom_field.field_name}">
            {/if}
        </fieldset>

    </div>
    <div class="col-lg-1 col-xl-1">
        <a class="btn tooltip-link delete pl-0 pr-0"><i class="material-icons">delete</i></a>
    </div>
</div>