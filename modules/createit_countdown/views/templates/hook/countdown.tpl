<style>

    .createit_countdown_wrapper {
        display: inline-block;
        color: {$textColor};
        background-color: {$backgroundColor};
        border-color: {$borderColor};
        padding: 10px;
    }
    .createit_countdown_wrapper ~ .createit_countdown_wrapper {
        display: none;
    }

</style>

{assign var='countDownPriceCurrency' value='<span id="createit_countdown_value">'|cat:$freeShippingTotalPriceLeft:"&nbsp;"|cat:$currency.sign:'</span>'}

<span class="createit_countdown_wrapper">
    <span class="createit_countdown">

        {if $hasProducts}

            {if $freeShippingTotalPriceLeft<0}

                {l s='Free Shipping!'
                d='Modules.CreateitCountdown.Admin'}

            {else}

                {l s='Spend %countDownPriceCurrency% more and get Free shipping!'
                d='Modules.CreateitCountdown.Admin'
                sprintf=[
                '%countDownPriceCurrency%' => $countDownPriceCurrency
                ]}

            {/if}

        {else}

            {l s='Spend %countDownPriceCurrency% and get Free shipping!'
                d='Modules.CreateitCountdown.Admin'
            sprintf=[
            '%countDownPriceCurrency%' => $countDownPriceCurrency
            ]}

        {/if}


    </span>
</span>