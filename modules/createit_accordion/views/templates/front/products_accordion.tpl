{foreach from=$accordion_list item=field}
    {if $accordion_value[$field.id_createit_accordion]['field_content'][$language.id]['content'] != ""}
        <div class="product-accordion__item">
            {if $field@index eq 0}
                {include file='module:createit_accordion/views/templates/front/_partials/_accordion_field.tpl' field=$field field_iteration=$field@iteration header_class='product-accordion__header' body_class='product-accordion__body collapse show' aria_expanded='true'}
            {else}
                {include file='module:createit_accordion/views/templates/front/_partials/_accordion_field.tpl' field=$field field_iteration=$field@iteration header_class='product-accordion__header collapsed' body_class='product-accordion__body collapse' aria_expanded='false'}
            {/if}
        </div>
    {/if}
{/foreach}