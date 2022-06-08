<h2>{l s='Createit Inspiration field' d='Modules.Createitinspirationfield.Admin'}</h2>

<div class="translations tabbable">
    <div class="translationsFields tab-content">

        {foreach from=$languages item=language }

            <div data-toggle="tab" data-locale="{$language.iso_code}" class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}active{/if}">
                <strong>{l s='Product Inspiration Label' d='Modules.Createitinspirationfield.Admin'}</strong>

                <input
                        class="serp-watched-description form-control"
                        type="text"
                        placeholder="{l s='Product Inspiration field - %lang%' sprintf=['%lang%' => $language.name] d='Modules.Createitinspirationfield.Admin'}"
                        value="{$inspirationfield[$language.id_lang]['content']}"
                        name="createit_inspirationfield[{$language.id_lang}][content]"
                >

            </div>

        {/foreach}

    </div>
</div>