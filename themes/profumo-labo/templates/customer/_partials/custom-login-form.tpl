{block name='login_form'}
    <form id="login-form" action="{$urls.pages.authentication}" method="post">
        <div>
        
            {block name='login_form_fields'}
                {foreach from=$loginFormFields.formFields item="field"}
                {block name='form_field'}
                    {form_field field=$field}
                {/block}
                {/foreach}
            {/block}

            {block name='login_form_footer'}
                <footer class="form-footer text-center clearfix">
                    <input type="hidden" name="submitLogin" value="1">
                    {block name='form_buttons'}
                    <button id="submit-login" class="btn btn-primary d-none d-md-inline-block btn-logindropdown" data-link-action="sign-in" type="submit" class="form-control-submit">
                        {l s='Sign in' d='Shop.Theme.Actions'}
                    </button>
                    <button id="submit-login" class="btn btn-primary btn-block d-block d-md-none btn-logindropdown" data-link-action="sign-in" type="submit" class="form-control-submit">
                        {l s='Sign in' d='Shop.Theme.Actions'}
                    </button>
                    {/block}
                </footer>
            {/block}

            <div class="forgot-password text-center mb-3">
                <a href="{$urls.pages.password}" rel="nofollow">
                {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
                </a>
            </div> 

            <div class="register-account text-center mb-3">
                <div>Don't have an account?</div>
                <a href="{$urls.pages.register}" class="btn btn-primary d-none d-md-inline-block btn-logindropdown-register" rel="nofollow">
                    {l s='Register' d='Shop.Theme.Global'}
                </a>
            </div>

        </div>
    </form>
{/block}