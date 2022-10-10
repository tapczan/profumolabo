<div class="m-b-1 m-t-1">
    <h2>createIT Custom Field</h2>

    <fieldset class="form-group" id="createit-customfield-form-group">
        {if empty($custom_fields)}
            Add custom field.
        {else}

                {foreach $empty_fields as $fields}

                    <div class="row createit-customfield" id="createit-customfield-{$fields.id_createit_customfield}">
                        <div class="col-lg-12 col-xl-6">

                            <strong>{$fields.label_name}</strong>

                            <div class="translations tabbable">
                                <div class="translationsFields tab-content">

                                    {foreach from=$languages item=language }

                                        {if $fields.field_type eq 0}

                                            <div data-toggle="tab" data-locale="{$language.iso_code}" class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}active{/if}">
                                                <strong>{$fields['field_label'][$language.id_lang]['content']}</strong>
                                                <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$fields.id_createit_customfield}][{$language.id_lang}][content]" type="text" value="{$createit_custom_fields[$fields.id_createit_customfield][$language.id_lang]['content']}">
                                            </div>

                                        {elseif $fields.field_type eq 1}

                                            <div data-toggle="tab" data-locale="{$language.iso_code}" class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}active{/if}">
                                                <strong>{$fields['field_label'][$language.id_lang]['content']}</strong>
                                                <textarea class="serp-watched-description form-control" name="createit_custom_field[{$fields.id_createit_customfield}][{$language.id_lang}][content]">{$createit_custom_fields[$fields.id_createit_customfield][$language.id_lang]['content']}</textarea>
                                            </div>

                                        {/if}

                                        <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$fields.id_createit_customfield}][{$language.id_lang}][id_createit_products_customfield]" type="hidden" value="{$fields['id_createit_customfield']}">
                                        <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$fields.id_createit_customfield}][{$language.id_lang}][id_lang]" type="hidden" value="{$language.id_lang}">
                                        <input class="form-control createit_custom_field_field_name" name="createit_custom_field[{$fields.id_createit_customfield}][{$language.id_lang}][lang_iso_code]" type="hidden" value="{$language.iso_code}">

                                    {/foreach}

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-11 col-xl-6">
                            <fieldset class="form-group mb-0">
                                <label class="form-control-label" for="createit_custom_field[{$fields.id_createit_customfield}][placeholder]">Custom Field Name (snake_case)</label>
                                <input class="form-control createit_custom_field_field_name" disabled="disabled" type="text" value="{$fields.field_name}">
                            </fieldset>
                        </div>

                    </div>

                {/foreach}

        {/if}

    </fieldset>

    <div class="clearfix"></div>

</div>