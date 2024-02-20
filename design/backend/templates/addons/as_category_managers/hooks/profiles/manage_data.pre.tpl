{if $smarty.request.user_type == "A"}
    <td class="row-status">
        {if $user.is_cm_user == "Y"}
            <span class="label label-success">{__("yes")}</span>
        {else}
            <span class="label label-default">{__("no")}</span>
        {/if}
    </td>
    <td class="row-status">
        {if $user.is_cm_leader == "Y"}
            <span class="label label-success">{__("yes")}</span>
        {else}
            <span class="label label-default">{__("no")}</span>
        {/if}
{/if}