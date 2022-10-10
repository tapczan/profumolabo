<style>
    .ui-autocomplete {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    * html .ui-autocomplete {
        height: 300px;
    }
</style>


<h2>{l s='Product Customfield' d='Modules.Createitproductfield2.Admin'}</h2>

<div class="translations tabbable">
    <div class="translationsFields tab-content">

        <div class="search search-with-icon">
            <input
                    type="text"
                    id="createit-productlist"
                    class="form-control autocomplete search mb-1"
                    value="{$productfield2_list['product_name']|default:''}"
                    placeholder="{l s='Search a product' d='Modules.Createitproductfield2.Admin'}">

            <input name="createit_productfield2" type="hidden" value="{$productfield2_list['product_id']|default:''}">

        </div>

    </div>
</div>