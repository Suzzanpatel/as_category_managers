<div class="terms-section clearfix" style="margin-bottom: 20px;">
    <div class="span12">
        <div class="span4">
            <label for="freight_terms">Freight Terms</label>
            <select name="update_order[freight_terms]" id="freight_terms" class="input-full form-control">
                <option value="">--</option>
                {foreach $freight_terms as $key => $term}
                    <option value="{$key}" {if $order_info.freight_terms == $key}selected="selected"{/if}>{$term}</option>
                {/foreach}
            </select>
        </div>
        <div class="span4">
            <label for="payment_terms">Payment Terms</label>
            <select name="update_order[payment_terms]" id="payment_terms" class="input-full form-control">
                <option value="">--</option>
                {foreach $insurance_terms as $key => $term}
                    <option value="{$key}" {if $order_info.payment_terms == $key}selected="selected"{/if}>{$term}</option>
                {/foreach}
            </select>
        </div>
        <div class="span4">
            <label for="insurance_terms">Insurance Terms</label>
            <select name="update_order[insurance_terms]" id="insurance_terms" class="input-full form-control">
                <option value="">--</option>
                {foreach $payment_terms as $key => $term}
                    <option value="{$key}" {if $order_info.insurance_terms == $key}selected="selected"{/if}>{$term}</option>
                {/foreach}
            </select>
        </div>
    </div>
</div>