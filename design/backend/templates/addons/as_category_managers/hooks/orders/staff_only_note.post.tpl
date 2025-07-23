<div class="terms-section clearfix" style="margin-bottom: 20px;">
    <div class="span12">
        <div class="span4">
            <label for="freight_terms">Freight Terms</label>
            <select name="update_order[freight_terms]" id="freight_terms" class="input-full form-control" {if $order_info.status == $approved_status}disabled{/if}>
                <option value="">--</option>
                {foreach $freight_terms as $key => $term}
                    <option value="{$key}" {if $order_info.freight_terms == $key}selected="selected"{/if}>{$term}</option>
                {/foreach}
            </select>
        </div>
        <div class="span4">
            <label for="payment_terms">Payment Terms</label>
            <select name="update_order[payment_terms]" id="payment_terms" class="input-full form-control" {if $order_info.status == $approved_status}disabled{/if}>
                <option value="">--</option>
                {foreach $insurance_terms as $key => $term}
                    <option value="{$key}" {if $order_info.payment_terms == $key}selected="selected"{/if}>{$term}</option>
                {/foreach}
            </select>
        </div>
        <div class="span4">
            <label for="insurance_terms">Insurance Terms</label>
            <select name="update_order[insurance_terms]" id="insurance_terms" class="input-full form-control" {if $order_info.status == $approved_status}disabled{/if}>
                <option value="">--</option>
                {foreach $payment_terms as $key => $term}
                    <option value="{$key}" {if $order_info.insurance_terms == $key}selected="selected"{/if}>{$term}</option>
                {/foreach}
            </select>

            <div id="insurance_policy_number_wrapper" class="{if !$order_info.insurance_policy_number}hidden{/if}" style="margin-top: 10px;">
                <label for="policy_number">Policy Number</label>
                <input type="text" name="update_order[insurance_policy_number]" id="policy_number" class="input-full form-control" value="{$order_info.insurance_policy_number|default:''}">
            </div>
        </div>
    </div>
</div>