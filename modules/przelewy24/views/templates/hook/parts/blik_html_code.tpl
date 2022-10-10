{*
* @license https://www.gnu.org/licenses/lgpl-3.0.en.html
*}

<div
        id="p24-blik-config-element"
        data-ajaxurl="{$p24_charge_blik_url|escape:'html':'UTF-8'}"
        data-returnurl="{$p24_url_return|escape:'html':'UTF-8'}"
        data-pagetype="{$p24_blik_page_type|escape:'html':'UTF-8'}"
        data-cartid="{$p24_blik_cart_id|escape:'html':'UTF-8'}"
></div>

<div id="p24-blik-modal-background">
    <div id="p24-blik-modal-holder">
        <div id="p24-blik-modal">
            <h1>{l s='Enter BLIK code' mod='przelewy24'}</h1>
            <a href="" class="close-modal">✖</a>
            <form>
                <div>
                    <p>{l s='Enter 6-digit BLIK code from Your bank application.' mod='przelewy24'}</p>
                    <p>
                        <input
                                minlength="6"
                                maxlength="6"
                                pattern="^\d{'{6}'|escape:'htmlall':'UTF-8'}$"
                                type="text"
                                name="blik"
                                placeholder="______"
                        >
                    </p>
                    <p class="error">{l s='Invalid BLIK code.' mod='przelewy24'}</p>
                    <p>
                        <button>{l s='Pay' mod='przelewy24'}</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
