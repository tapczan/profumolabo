<div class="container">

    <div class="row mb-4" style="border-bottom: 1px solid #C5C5C5;">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="my-6">{$category.name}</h1>
            {$category.description|strip_tags:'UTF-8'}
        </div>
        
        <div class="col-md-12 mt-3 mb-5" >
            {hook h='arCategoryPageHook1'}
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
    
            <div class="product-accordion" id="productSingleAccordion">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader1" data-toggle="collapse" data-target="#productAccordionContent1" aria-expanded="true" aria-controls="productAccordionContent1">
                        +  ROZWIŃ FILTRY
                    </div>
                    <div class="product-accordion__body collapse show" id="productAccordionContent1" aria-labelledby="productAccordionHeader1" data-parent="#productSingleAccordion">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>379 produktów</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion2">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader2" data-toggle="collapse" data-target="#productAccordionContent2" aria-expanded="true" aria-controls="productAccordionContent2">
                        +  DLA KOGO
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent2" aria-labelledby="productAccordionHeader2" data-parent="#productSingleAccordion2">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>Kobieta</li>
                            <li>Mężczyzna</li>
                            <li>Unisex</li>
                        </ul>
                         
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion3">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader3" data-toggle="collapse" data-target="#productAccordionContent3" aria-expanded="true" aria-controls="productAccordionContent3">
                        +  RODZAJ PRODUKTU
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent3" aria-labelledby="productAccordionHeader3" data-parent="#productSingleAccordion3">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>
                         
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion4">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader4" data-toggle="collapse" data-target="#productAccordionContent4" aria-expanded="true" aria-controls="productAccordionContent4">
                        +  KATEGORIA
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent4" aria-labelledby="productAccordionHeader4" data-parent="#productSingleAccordion4">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>
                         
                    </div>
                </div>
            </div>

            <div class="product-accordion" id="productSingleAccordion5">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader5" data-toggle="collapse" data-target="#productAccordionContent5" aria-expanded="true" aria-controls="productAccordionContent5">
                        +  INSPIRACJA MARKI
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent5" aria-labelledby="productAccordionHeader5" data-parent="#productSingleAccordion5">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>   
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion6">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader6" data-toggle="collapse" data-target="#productAccordionContent6" aria-expanded="true" aria-controls="productAccordionContent6">
                        +  KOLEKCJA 
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent6" aria-labelledby="productAccordionHeader6" data-parent="#productSingleAccordion6">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>
                         
                    </div>
                </div>
            </div> 
            <div class="product-accordion" id="productSingleAccordion7">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader7" data-toggle="collapse" data-target="#productAccordionContent7" aria-expanded="true" aria-controls="productAccordionContent7">
                        +  OCENA
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent7" aria-labelledby="productAccordionHeader7" data-parent="#productSingleAccordion7">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>Kobieta</li>
                            <li>Mężczyzna</li>
                            <li>Unisex</li>
                        </ul>   
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion8">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader8" data-toggle="collapse" data-target="#productAccordionContent8" aria-expanded="true" aria-controls="productAccordionContent8">
                        +  POJEMNOŚĆ
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent8" aria-labelledby="productAccordionHeader8" data-parent="#productSingleAccordion8">
                        <ul style="padding: 5px 5px 5px 16px; color:#C4C4C4;">
                            <li>80 ML</li>
                            <li>90 ML</li>
                            <li>100 ML</li>
                            <li>110 ML</li>
                        </ul>   
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion9">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader9" data-toggle="collapse" data-target="#productAccordionContent9" aria-expanded="true" aria-controls="productAccordionContent9">
                        +  NA JAKĄ PORĘ DNIA
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent9" aria-labelledby="productAccordionHeader9" data-parent="#productSingleAccordion9">
                        <ul style="padding: 5px 5px 5px 16px">
                            <li>Kobieta</li>
                            <li>Mężczyzna</li>
                            <li>Unisex</li>
                        </ul>   
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion10">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader10" data-toggle="collapse" data-target="#productAccordionContent10" aria-expanded="true" aria-controls="productAccordionContent10">
                        +  NA JAKĄ PORĘ ROKU
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent10" aria-labelledby="productAccordionHeader10" data-parent="#productSingleAccordion10">
                        <ul style="padding: 5px 5px 5px 16px">
                            <li>Kobieta</li>
                            <li>Mężczyzna</li>
                            <li>Unisex</li>
                        </ul>   
                    </div>
                </div>
            </div>
            <div class="product-accordion" id="productSingleAccordion11">
                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader11" data-toggle="collapse" data-target="#productAccordionContent11" aria-expanded="true" aria-controls="productAccordionContent11">
                        +  GRUPA ZAPACHOWA
                    </div>
                    <div class="product-accordion__body collapse" id="productAccordionContent11" aria-labelledby="productAccordionHeader11" data-parent="#productSingleAccordion11">
                        <ul style="padding: 5px 5px 5px 16px">
                            <li>Kobieta</li>
                            <li>Mężczyzna</li>
                            <li>Unisex</li>
                        </ul>   
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            {include file='_partials/pagination.tpl' pagination=$listing.pagination}
            {hook h='arCategoryPageHook2'}
        </div>
    </div>

</div>