<div class="col-md-12 product-category">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="product-category__title">
                    {$category.name}
                </h1>

                <div class="product-category__description">
                    {$category.description|strip_tags:'UTF-8'}
                </div>

                <div class="product-category__listing">
                    {hook h='arCategoryPageHook1'}
                </div>
            </div>
        </div>

        <div class="product-category__main">
            <div class="row">
                <div class="col-md-3">
                    <div class="product-filter">
                        {hook h='displayLeftColumn'}
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="product-pagination">
                        {include file='_partials/pagination.tpl' pagination=$listing.pagination}
                    </div>
                    <div class="product-listing">
                        {hook h='arCategoryPageHook2'}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>