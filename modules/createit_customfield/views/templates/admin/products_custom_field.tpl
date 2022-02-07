<div class="m-b-1 m-t-1">
    <h2>createIT Custom Field</h2>
    <fieldset class="form-group" id="createit-customfield-form-group">


        {if empty($createit_custom_fields)}

            {assign var=createit_custom_field_empty value=true}

            {include file='./_partials/_custom_field_row.tpl' createit_custom_field=$createit_custom_field}

        {else}

            {assign var=createit_custom_field_empty value=false}

            {foreach $createit_custom_fields as $createit_custom_field}

                {include file='./_partials/_custom_field_row.tpl' createit_custom_field=$createit_custom_field}

            {/foreach }

        {/if}

    </fieldset>

    <div class="row">
        <div class="col-lg-12 col-xl-6">
            <button type="button" id="create_it_add_field" class="btn btn-outline-primary mb-1"><i class="material-icons">add_circle</i> Add more field</button>
        </div>
    </div>

    <div class="clearfix"></div>

</div>