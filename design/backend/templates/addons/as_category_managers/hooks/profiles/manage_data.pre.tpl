{if $smarty.request.user_type == "A"}
    <td class="row-status">
        {if $user.is_cm_user == "Y"}
            <span class="label label-success">{__("as_category_managers.cm_user")}</span>
        {else}
            {hook name="as_cm_profiles:manage_data_row"}
                <span class="label label-default">{__("administrator")}</span>
            {/hook}
        {/if}
    </td>
    <td class="row-status">
        {if $user.is_cm_user == "Y"}
            {if $user.is_cm_leader == "Y"}
                <span class="label label-success">{__("yes")}</span>
            {else}
                <span class="label label-default">{__("no")}</span>
            {/if}
        {else}
            -
        {/if}
    </td>
{/if}