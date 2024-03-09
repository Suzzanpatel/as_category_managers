{if ($smarty.request.user_type == "A" && $smarty.request.is_cm_user == "Y") || ($user_data.is_root == "N" && $user_data.is_cm_user == "Y")}
    <input type="hidden" name="user_data[is_cm_user]" value="Y">
{/if}