<div class="col-lg-8 col-md-10 mx-auto mb-6">
    <div class="row">
        <div class="col-md-8">
            {widget name="contactform"}
        </div>

        <div class="col-md-4">
            {widget name="ps_contactinfo" hook='displayRightColumn'}
        </div>

        <div class="col-md-12">
            
            <div class="contact-footer">
                <div class="row">
                   
                    <div class="col-lg-8 col-md-8 col-12 contact-footer__big">
                    
                    <div class="contact-data__item">
                        <ul class="contact-data__list">
                            <li class="contact-data__title contact-data__title--upcase">
                                <a href="{$link->getCMSLink(17)}">
                                    {l s='Information' d='Shop.Theme.Global'}
                                </a>
                            </li>
                            <li><a href="{$link->getCMSLink(8)}">{l s='O PROFUMO LABO' d='Shop.Theme.Global'}</a></li>
                            <li><a href="{$link->getCMSLink(17)}?contentCollapse=0">{l s='Time and Cost Delivery' d='Shop.Theme.Global'}</a></li>
                            <li><a href="{$link->getCMSLink(17)}?contentCollapse=1">{l s='Payment methods' d='Shop.Theme.Global'}</a></li>
                            <li><a href="{$link->getCMSLink(17)}?contentCollapse=2">{l s='Returns and Complaints' d='Shop.Theme.Global'}</a></li>
                            <li><a href="{$link->getCMSLink(12)}">{l s='Regulations of the online store' d='Shop.Theme.Global'}</a></li>
                            <li><a href="{$link->getCMSLink(13)}">{l s='Privacy Policy' d='Shop.Theme.Global'}</a></li>
                            <li><a href="{$urls.pages['index']}blog">{l s='Blog' d='Shop.Theme.Global'}</a></li>
                        </ul>      
                    </div>
                    
                    <div class="contact-data__item">
                        <ul class="contact-data__list">
                        <li class="contact-data__title">{l s='Cooperation' d='Shop.Theme.Global'}</li>          
                        {if $pslanguage == 'pl'}
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=influencerami">Współpraca influenser</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=medialna">Współpraca media</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=b2b">Współpraca B2B</a></li>
                        {else}
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=influencer_cooperation">Influencer Cooperation</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=media_cooperation">Media Collaboration</a></li>
                            <li><a href="{$link->getCMSLink(18)}?contentCollapse=b2b_cooperation">B2B Cooperation</a></li>
                        {/if}

                        <li class="contact-data__title contact-data__title--top contact-data__title--upcase">
                            <a href="{$link->getCMSLink(19)}">
                                {l s='Partner Program' d='Shop.Theme.Global'}
                            </a>
                        </li>
                        </ul>      
                    </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12 contact-social">
                    <ul class="contact-social__list">
                        <li>{l s='Visit Us' d='Shop.Theme.Global'}</li>
                        <li><a href="https://www.facebook.com/PROFUMOLABO" target="_blank"><span class="contact-icon contact-icon--facebook"></span></a></li>
                        <li><a href="https://www.youtube.com/channel/UC-f-s6t7Ymelrj83tj6UcbQ" target="_blank"><span class="contact-icon contact-icon--youtube"></span></a></li>
                        <li><a href="https://www.instagram.com/profumolabo/" target="_blank"><span class="contact-icon contact-icon--instagram"></span></a></li>
                        {*
                        <li><a href="https://twitter.com/" target="_blank"><span class="contact-icon contact-icon--twitter"></span></a></li>
                        <li><a href="https://www.pinterest.com/" target="_blank"><span class="contact-icon contact-icon--pinterest"></span></a></li>
                        *}
                    </ul>
                    </div>

                    
                </div>
            </div>
        </div>
       
    </div>
</div>