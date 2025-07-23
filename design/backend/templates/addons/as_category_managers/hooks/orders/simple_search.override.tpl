{if $smarty.request.redirect_url}
<input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
{/if}
{if $selected_section != ""}
<input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
{/if}

{$extra nofilter}

<div class="sidebar-field">
    <label for="elm_company">{__("company")}</label>
    <input type="text" name="company" id="elm_company" value="{$search.company}" size="10" />
</div>

<div class="sidebar-field">
    <label for="email">{__("email")}</label>
    <input type="text" name="email" id="email" value="{$search.email}" size="30"/>
</div>

<div class="sidebar-field">
    <label for="phone">{__("phone")}</label>
    <input class="cm-phone" type="text" name="phone" id="phone" value="{$search.phone}" size="50"/>
</div>

<div class="sidebar-field">
    <label for="total_from">{__("total")}&nbsp;({$currencies.$primary_currency.symbol nofilter})</label>
    <input type="text" class="input-small" name="total_from" id="total_from" value="{$search.total_from}" size="3" /> - <input type="text" class="input-small" name="total_to" value="{$search.total_to}" size="3" />
</div>

{include file="common/period_selector.tpl" period=$search.period form_name="orders_search_form" display="form"}

<div class="sidebar-field">
    <label for="assigned_warehouse_id">Warehouse</label>
    <div style="margin-bottom: 15px;">
        {include file="addons/as_po_bifurcation/pickers/users/picker.tpl"
            data_id="return_users"
            but_meta="btn"
            picker_for="assign_warehouse"
            input_name="assigned_warehouse_id"
            item_ids=$smarty.get.assigned_warehouse_id
            extra_url="&user_type=A"
            readonly=$is_picker_readonly
            display="radio"
            view_mode="single_button"
        }
    </div>
</div>