<div class="col-lg-8 col-md-10 mx-auto mb-6">
    <div class="row">
        <div class="col-md-9">
            {widget name="contactform"}
        </div>

        <div class="col-md-3">
            {widget name="ps_contactinfo" hook='displayRightColumn'}
        </div>

        <div class="col-md-12">
            
            <div class="contact-footer">
                <div class="row">
                   
                    <div class="col-lg-8 col-md-8 col-12 contact-footer__big">
                    
                    <div class="contact-data__item">
                        <ul class="contact-data__list">
                        <li class="contact-data__title">{l s='Information' d='Shop.Theme.Global'}</li>
                        {assign var='pages' value=FrontController::getCMSPages([8,9,10,11,12,13],$language.id)}
                        {foreach from=$pages item='page'}
                            <li>{$page nofilter}</li>
                        {/foreach}
                        <li><a href="{$urls.pages['index']}blog">{l s='Blog' d='Shop.Theme.Global'}</a></li>
                        </ul>      
                    </div>
                    
                    <div class="contact-data__item">
                        <ul class="contact-data__list">
                        <li class="contact-data__title">{l s='Cooperation' d='Shop.Theme.Global'}</li>
                       
                        {if $pslanguage == 'pl'}
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=współpraca_z_influencerami">Influencer Cooperation</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=współpraca_medialna">Media Collaboration</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=współpraca_b2b">B2B Cooperation</a></li>
                        {else}
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=influencer_cooperation">Influencer Cooperation</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=media_cooperation">Media Collaboration</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=b2b_cooperation">B2B Cooperation</a></li>
                        {/if}

                        <li class="contact-data__title contact-data__title--top">{l s='Partner Program' d='Shop.Theme.Global'}</li>
                        </ul>      
                    </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12 contact-social">
                    <ul class="contact-social__list">
                        <li>{l s='Visit Us' d='Shop.Theme.Global'}</li>
                        <li><a href="https://www.facebook.com/" target="_blank"><span class="contact-icon contact-icon--facebook"></span></a></li>
                        <li><a href="https://www.instagram.com/" target="_blank"><span class="contact-icon contact-icon--instagram"></span></a></li>
                        <li><a href="https://www.youtube.com/" target="_blank"><span class="contact-icon contact-icon--youtube"></span></a></li>
                        <li><a href="https://twitter.com/" target="_blank"><span class="contact-icon contact-icon--twitter"></span></a></li>
                        <li><a href="https://www.pinterest.com/" target="_blank"><span class="contact-icon contact-icon--pinterest"></span></a>
                        </li>
                    </ul>
                    </div>

                    
                </div>
            </div>
        </div>
       
    </div>
</div>