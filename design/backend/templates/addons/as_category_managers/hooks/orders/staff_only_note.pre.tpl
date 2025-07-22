<div class="order-notes statistic">
    <div class="note clearfix">
        <div class="span3">
            <label for="cm_notes">Category Manager Notes</label>
            <textarea class="span12" name="update_order[cm_notes]" id="cm_notes" cols="40" rows="3" {if $auth.is_cm_user != "Y" && $auth.is_root != "Y"}readonly{/if}>{$order_info.cm_notes}</textarea>
        </div>

        <div class="span3">
            <label for="wh_notes">Warehouse Notes</label>
            <textarea class="span12" name="update_order[wh_notes]" id="wh_notes" cols="40" rows="3" {if $auth.is_wh_user != "Y" && $auth.is_root != "Y"}readonly{/if}>{$order_info.wh_notes}</textarea>
        </div>

        <div class="span3">
            <label for="aa_notes">Approval Authority Notes</label>
            <textarea class="span12" name="update_order[aa_notes]" id="aa_notes" cols="40" rows="3" {if $auth.is_aa_user != "Y" && $auth.is_root != "Y"}readonly{/if}>{$order_info.aa_notes}</textarea>
        </div>

        <div class="span3">
            <label for="cit_notes">CIT Notes</label>
            <textarea class="span12" name="update_order[cit_notes]" id="cit_notes" cols="40" rows="3" {if !$auth.user_id|as_pb_is_cit_user && $auth.is_root != "Y"}readonly{/if}>{$order_info.cit_notes}</textarea>
        </div>
    </div>
</div>
