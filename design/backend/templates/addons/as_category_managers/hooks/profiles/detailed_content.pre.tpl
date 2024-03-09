{if $smarty.request.is_cm_user == "Y" || ($user_data.is_root == "N" && $user_data.is_cm_user == "Y")}
    {if !$hide_title}
        {include file="common/subheader.tpl" title=__("as_category_managers.cm_user_type") target="#acc_is_cm_leader"}
    {/if}
    <div id="acc_is_cm_leader" class="collapsed in">
        <div class="control-group">
            <label class="control-label" for="elm_is_cm_leader">{__("as_category_managers.is_leader")}:</label>
            <div class="controls">
                <div class="input-group">
                    <input type="checkbox"
                        name="user_data[is_cm_leader]"
                        id="elm_is_cm_leader"
                        value="{if $id}{$user_data.is_cm_leader}{else}Y{/if}"
                        {if !$id || $user_data.is_cm_leader == "Y"}checked="checked"{/if}
                    />
                </div>
            </div>
        </div>

        <div class="control-group" id="cm_members_picker_container">
            <label class="control-label">{__("as_category_managers.members")}:</label>
            <div class="controls">
                {include file="pickers/users/picker.tpl" data_id="return_users" but_meta="btn" picker_for="assign_cm_member" input_name="user_data[cm_member_ids]" item_ids=$user_data.cm_member_ids placement="right"}
            </div>
        </div>
    </div>


    <div id="cm_categories_picker_container">
        {if !$hide_title}
            {include file="common/subheader.tpl" title=__("as_category_managers.assign_categories_to_cm") target="#acc_cm_category_picker"}
        {/if}
        <div id="acc_cm_category_picker" class="collapsed in">
            {include file="pickers/categories/picker.tpl" but_text=__("add_categories") data_id="return_categories" but_meta="btn" input_name="user_data[cm_category_ids]" item_ids=$user_data.cm_category_ids placement="right" multiple=true}
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            let cmMembersPickerContainer = $('#cm_members_picker_container');
            let cmCategoriesPickerContainer = $('#cm_categories_picker_container');
            let cmIsLeader = $('#elm_is_cm_leader');
            let cmLeaderId = $('#assign_cm_member');

            cmIsLeader.on('change', function() {
                if (cmIsLeader.is(':checked')) {
                    cmMembersPickerContainer.show();
                    cmCategoriesPickerContainer.show();
                } else {
                    cmMembersPickerContainer.hide();
                    cmCategoriesPickerContainer.hide();
                }
            });

            if (cmIsLeader.is(':checked')) {
                cmMembersPickerContainer.show();
                cmCategoriesPickerContainer.show();
            } else {
                cmMembersPickerContainer.hide();
                cmCategoriesPickerContainer.hide();
            }

            if (cmLeaderId.val()) {
                cmIsLeader.prop('checked', true);
                cmMembersPickerContainer.show();
                cmCategoriesPickerContainer.show();
            }
        });
    </script>
{/if}
