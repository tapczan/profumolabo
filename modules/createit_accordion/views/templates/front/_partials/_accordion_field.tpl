<div
        class="{$header_class}"
        id="productAccordionHeader{$field_iteration}"
        data-toggle="collapse"
        data-target="#productAccordionContent{$field_iteration}"
        aria-expanded="{$aria_expanded}"
        aria-controls="productAccordionContent{$field_iteration}">
        {$field.field_label[$language.id]['content']}

</div>
<div
        class="{$body_class}"
        id="productAccordionContent{$field_iteration}"
        aria-labelledby="productAccordionHeader{$field_iteration}"
        data-parent="#productSingleAccordion">
        {$accordion_value[$field.id_createit_accordion]['field_content'][$language.id]['content']|unescape: "html" nofilter}
</div>