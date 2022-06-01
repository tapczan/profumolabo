{foreach from=$customfield item=field}
    {if $field.content != ""}
        <div class="product-accordion__item">
            <div class="product-accordion__header collapsed" id="productAccordionHeader{$field@iteration}" data-toggle="collapse" data-target="#productAccordionContent{$field@iteration}" aria-expanded="false" aria-controls="productAccordionContent{$field@iteration}">
                {$field.field_label}
            </div>
            <div class="product-accordion__body collapse" id="productAccordionContent{$field@iteration}" aria-labelledby="productAccordionHeader{$field@iteration}" data-parent="#productSingleAccordion">
                {$field.content|unescape: "html" nofilter}
            </div>
        </div>
    {/if}
{/foreach}