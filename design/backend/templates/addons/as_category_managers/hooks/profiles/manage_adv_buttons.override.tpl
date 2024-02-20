{if $smarty.request.user_type}
    {if $can_add_user}
        {$user_type_request = $smarty.request.user_type}

        {if $user_type_request == "A"}
            {capture name="dropdown_list"}
                <li>{btn type="list" text=__("add_administrator") href="{"profiles.add?user_type=`$user_type_request`"|fn_url}"}</li>
                <li>{btn type="list" text=__("as_category_managers.add_cm_user") href="{"profiles.add?user_type=`$user_type_request`&is_cm_user=Y"|fn_url}"}</li>
                {$smarty.capture.tools_list_items nofilter}
            {/capture}
            {dropdown content=$smarty.capture.dropdown_list icon="icon-plus" no_caret=true placement="right"}
        {else}
            <a class="btn cm-tooltip" href="{"profiles.add?user_type=`$user_type_request`"|fn_url}" title="{__("add_user")}">
                {include_ext file="common/icon.tpl" class="icon-plus"}
            </a>
        {/if}

    {/if}
{/if}