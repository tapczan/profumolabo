<h2>createIT Accordion</h2>

{foreach from=$accordion_list item='accordion'}

<div class="translations tabbable">
    <div class="translationsFields tab-content">

        {foreach from=$languages item=language }

            <div data-toggle="tab" data-locale="{$language.iso_code}" class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}active{/if}">
                <strong class="mt-1 d-inline-block">{$accordion['field_label'][$language.id_lang]['content']}</strong>
                <textarea class="serp-watched-description form-control autoload_rte" name="createit_accordion[{$accordion.id_createit_accordion}][{$language.id_lang}][content]">{$accordion_value[$accordion['id_createit_accordion']]['field_content'][$language.id_lang]['content']}</textarea>
            </div>

        {/foreach}

    </div>
</div>

{/foreach}