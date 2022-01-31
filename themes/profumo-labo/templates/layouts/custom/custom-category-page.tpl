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
            <h1>I am filter</h1>
        </div>
        <div class="col-md-9">
            {include file='_partials/pagination.tpl' pagination=$listing.pagination}
            {hook h='arCategoryPageHook2'}
        </div>
    </div>

</div>