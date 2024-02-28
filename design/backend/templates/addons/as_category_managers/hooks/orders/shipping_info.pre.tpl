{if $auth.is_cm_user == "Y" && $auth.is_cm_leader == "Y"}
    {include file="common/subheader.tpl" title=__("as_category_managers.assign_order_to_member")}
    <div class="control-group shift-top" id="select_member">
        <div class="control">
            <div class="order-manager">
                {include file="views/profiles/components/picker/picker.tpl"
                    input_name="update_order[assigned_cm_member_id]"
                    item_ids=[$order_info.assigned_cm_member_id]
                    url="profiles.get_member_list"
                    show_advanced=false
                }
            </div>
        </div>
    <!--select_member--></div>
{/if}