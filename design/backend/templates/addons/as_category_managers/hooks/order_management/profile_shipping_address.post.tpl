{if $runtime.controller == "orders" && $runtime.mode == "details" && $auth.is_cm_user == "Y"}
<form action="{""|fn_url}" method="post" id="shipping_address_form">
    <input type="hidden" name="order_id" value="{$smarty.get.order_id}" />
    <div class="control-group" style="margin-top: 10px; margin-bottom: 0px !important;">
        <label class="control-label" for="order_profile_id">{__("select_profile")}</label>
        <div class="controls">
            <select name="profile_id" id="order_profile_id" class="select-expanded">
                {foreach from=$user_profiles item="user_profile"}
                    <option value="{$user_profile.profile_id}" {if $order_info.profile_id == $user_profile.profile_id}selected="selected"{/if}>
                        {$user_profile.profile_name} | {$user_profile.s_address} {$user_profile.s_city}, {$user_profile.s_state_descr}, {$user_profile.s_country_descr} - {$user_profile.s_zipcode}
                    </option>
                {/foreach}
            </select>
        </div>
    </div>

    {include file="buttons/save.tpl" but_name="dispatch[orders.set_shipping_address]" but_meta="cm-confirm" but_role="submit-link" save=$id but_target_form="shipping_address_form"}
</form>

<a class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="create_new_profile">Create a new profile</a>

<div class="hidden" title="Create new profile" id="create_new_profile">
    <form action="{""|fn_url}" method="POST" name="new_profile_form" class="form-horizontal form-edit">
        <input type="hidden" name="order_id" value="{$smarty.get.order_id}" />
        <input type="hidden" name="user_id" value="{$order_info.user_id}" />

        <div class="control-group">
            <label for="profile_name" class="cm-required">Profile name:</label>
            <input type="text" name="user_data[profile_name]" id="profile_name" value="" class="input-full form-control" />
        </div>
        <div class="control-group">
            <label for="s_address" class="cm-required">Address:</label>
            <input type="text" name="user_data[s_address]" id="s_address" value="" class="input-full form-control" />
        </div>
        <div class="control-group">
            <label for="s_city" class="cm-required">City:</label>
            <input type="text" name="user_data[s_city]" id="s_city" value="" class="input-full form-control" />
        </div>
        <div class="control-group">
            <label for="s_country" class="cm-required">Country:</label>
            <select name="user_data[s_country]" id="s_country" class="input-full form-control">
                <option value="">-- Select Country --</option>
                {foreach from=$countries item="country" key="code"}
                    <option value="{$code}">{$country}</option>
                {/foreach}
            </select>
        </div>
        <div class="control-group">
            {$_country = $user_profile.s_country}
            <label for="s_state" class="cm-required">State:</label>
            <select name="user_data[s_state]" id="s_state" class="input-full form-control">
                <option value="">-- Select State --</option>
                {foreach from=$states.$_country item=state}
                    <option value="{$state.code}">{$state.state}</option>
                {/foreach}
            </select>
        </div>
        <div class="control-group">
            <label for="s_zipcode" class="cm-required">Zip Code:</label>
            <input type="text" name="user_data[s_zipcode]" id="s_zipcode" value="" class="input-full form-control" />
        </div>

        <div class="modal-footer buttons-container">
            <div class="pull-right">
                <a class="cm-dialog-closer cm-cancel tool-link btn bulkedit-unchanged" data-dismiss="modal">{__("cancel")}</a>
                {include file="buttons/save.tpl" but_name="dispatch[orders.create_new_profile]" but_role="submit-link" save=$id but_target_form="new_profile_form"}
            </div>
        </div>
    </form>
</div>
{/if}