<?php

use Tygh\Enum\Addons\VendorDataPremoderation\ProductStatuses;
use Tygh\Enum\SiteArea;
use Tygh\Registry;
use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_as_category_managers_install()
{
    $cm_usergroup_id = db_get_row("SELECT usergroup_id FROM ?:usergroup_descriptions WHERE usergroup = ?s", "Addon: Category Manager");
    $cm_usergroup_id = $cm_usergroup_id['usergroup_id'] ?? -1;

    if (!fn_is_usergroup_exists($cm_usergroup_id)) {
        // Create usergroup
        $cm_usergroup_data = [
            "usergroup" => "Addon: Category Manager",
            "type" => "A",
            "status" => "A",
            "privileges" => [
                "edit_order" => "Y",
                "update_order_details" => "Y",
                "delete_orders" => "Y",
                "change_order_status" => "Y",
                "create_order" => "Y",
                "view_orders" => "Y",
                "manage_catalog" => "Y",
                "view_catalog" => "Y",
                "manage_product_premoderation" => "Y",
            ],
        ];
    
        fn_update_usergroup($cm_usergroup_data, 0, DESCR_SL);
    }
}

function fn_as_category_managers_uninstall()
{
    // Delete usergroup
    $cm_usergroup_id = db_get_row("SELECT usergroup_id FROM ?:usergroup_descriptions WHERE usergroup = ?s", "Addon: Category Manager");

    if (!empty($cm_usergroup_id) && fn_is_usergroup_exists($cm_usergroup_id['usergroup_id'])) {
        fn_delete_usergroups(explode(",", $cm_usergroup_id['usergroup_id']));
    }
}

/**
 * Get category managers user data
 * 
 * @return array
 */
function fn_as_category_managers_get_cm_user_data() : array
{
    $auth = Tygh::$app['session']['auth'];
    $user_id = $auth['user_id'] ?? 0;

    $cm_user_data = db_get_row("SELECT is_cm_user, is_cm_leader, cm_member_ids, cm_category_ids FROM ?:users WHERE user_id = ?i", $user_id);

    return $cm_user_data;
}

function fn_as_category_managers_fill_auth(&$auth, &$user_data, &$area, &$original_auth)
{
    $auth['is_cm_user'] = $user_data['is_cm_user'] ?? false;
    $auth['is_cm_leader'] = $user_data['is_cm_leader'] ?? false;
    $auth['cm_category_ids'] = $user_data['cm_category_ids'] ?? [];
}

//-- Currently not used
function fn_as_category_managers_get_user_type_description(&$type_descr)
{
    // Add M for AS Category Manager
    $type_descr['S']['M'] = 'as_category_managers.category_manager';
    $type_descr['P']['M'] = 'as_category_managers.category_managers';

    return $type_descr;
}

function fn_as_category_managers_get_users_pre(&$params, &$auth, &$items_per_page, &$custom_view)
{
    $controller = Registry::get('runtime.controller');
    $mode = Registry::get('runtime.mode');

    $is_cm_user_param = $params['is_cm_user'] ?? false;
    $user_type = $params['user_type'] ?? false;
    $picker_for = $params['picker_for'] ?? false;

    if ($user_type == 'A' && $is_cm_user_param) {
        $params['user_type'] = 'A';
        $params['is_cm_user'] = true;
    }
    
    // For users picker
    if ($controller == "profiles" && $mode == "picker" && $picker_for == "assign_cm_member") {
        // Exclude already assigned member ids
        $already_assigned_member_ids = fn_as_category_managers_get_already_assigned_member_ids();
        if (!empty($already_assigned_member_ids)) {
            $params['exclude_user_ids'] = $already_assigned_member_ids;
        }

        // Change user type to V and C
        $params['exclude_user_types'] = ['V', 'C'];
    }
}

function fn_as_category_managers_get_users($params, &$fields, &$sortings, &$condition, &$join, &$auth)
{
    $controller = Registry::get('runtime.controller');
    $mode = Registry::get('runtime.mode');
    $picker_for = $params['picker_for'] ?? false;

    // For users picker
    if ($controller == "profiles" && $mode == "picker" && $picker_for == "assign_cm_member") {
        $condition['status'] = db_quote(" AND ?:users.status = 'A'");
        $condition['is_cm_user'] = db_quote(" AND ?:users.is_cm_user = 'Y'");
        $condition['is_cm_leader'] = db_quote(" AND ?:users.is_cm_leader = 'N'");
    }

    // Add `is_cm_user`
    $fields['is_cm_user'] = "?:users.is_cm_user";

    // Add `is_cm_leader`
    $fields['is_cm_leader'] = "?:users.is_cm_leader";

    // Add `cm_member_ids`
    $fields['cm_member_ids'] = "?:users.cm_member_ids";

    // Add `cm_category_ids`
    $fields['cm_category_ids'] = "?:users.cm_category_ids";
}

function fn_as_category_managers_update_user_pre(&$user_id, &$user_data, &$auth, &$ship_to_another, &$notify_user)
{
    $user_type = $user_data['user_type'] ?? false;
    $is_cm_user = $user_data['is_cm_user'] ?? false;

    if ($user_type == 'A' && $is_cm_user) {
        // If from $user_data['cm_member_ids'] include the current user_id, return error
        if (!empty($user_id) && isset($user_data['cm_member_ids'])) {
            $cm_member_ids = explode(",", $user_data['cm_member_ids']);
            if (in_array($user_id, $cm_member_ids)) {
                // Remove the current user_id from $user_data['cm_member_ids']
                $cm_member_ids = array_diff($cm_member_ids, [$user_id]);
                $user_data['cm_member_ids'] = implode(",", $cm_member_ids);

                fn_set_notification('E', __('error'), __('as_category_managers.cm_member_ids_error.cannot_assign_yourself'));

                return false;
            }
        }

        $user_data['user_type'] = 'A';
        $user_data['is_cm_user'] = 'Y';
        $user_data['is_cm_leader'] = isset($user_data['is_cm_leader']) ? 'Y' : 'N';

        // Reset member's cm_category_ids
        if ($user_data['is_cm_leader'] == 'Y') {
            $old_cm_member_ids = db_get_field("SELECT cm_member_ids FROM ?:users WHERE user_id = ?i", $user_id);
            $cm_member_ids = explode(",", $old_cm_member_ids);

            if (!empty($cm_member_ids)) {
                foreach ($cm_member_ids as $cm_member_id) {
                    // Empty the member's cm_category_ids
                    db_query("UPDATE ?:users SET cm_category_ids = ?s WHERE user_id = ?i", "", $cm_member_id);
                }
            }
        }

        return true;
    }
}

function fn_as_category_managers_update_profile($action, $user_data, $current_user_data)
{
    if ($user_data['user_type'] == 'A' && $user_data['is_cm_user'] == 'Y') {
        if ($action === 'add') {
            fn_change_usergroup_status('A', $user_data['user_id'], fn_as_category_managers_get_cm_usergroup_id());
        }

        if ($user_data['is_cm_leader'] == 'Y') {
            $cm_member_ids = explode(",", $user_data['cm_member_ids']);

            if (!empty($cm_member_ids)) {
                foreach ($cm_member_ids as $cm_member_id) {
                    // Get leader cm_category_ids
                    $leader_cm_category_ids = $user_data['cm_category_ids'];

                    // Assign leader cm_category_ids to cm_member
                    db_query("UPDATE ?:users SET cm_category_ids = ?s WHERE user_id = ?i", $leader_cm_category_ids, $cm_member_id);
                }
            }
        }
    }
}

function fn_as_category_managers_get_cm_usergroup_id() : int
{
    return db_get_row("SELECT usergroup_id FROM ?:usergroup_descriptions WHERE usergroup = ?s", "Addon: Category Manager")['usergroup_id'];
}

function fn_as_category_managers_get_categories(&$params, &$join, &$condition, &$fields, &$group_by, &$sortings, &$lang_code)
{
    $is_cm_user = fn_as_category_managers_get_cm_user_data()['is_cm_user'] ?? false;

    if ($is_cm_user == "Y") {
        $category_ids = fn_as_category_managers_get_cm_user_data()['cm_category_ids'] ?? 0;

        // If category_ids is not empty
        if (!empty($category_ids)) {
            $category_ids = explode(",", $category_ids);
            $condition .= db_quote(" AND ?:categories.category_id IN (?a)", $category_ids);
        } else {
            $condition .= db_quote(" AND ?:categories.category_id = 0");
        }
    }
}

function fn_as_category_managers_get_products_before_select (
    &$params,
    &$join,
    $condition,
    $u_condition,
    $inventory_join_cond,
    $sortings,
    $total,
    $items_per_page,
    $lang_code,
    $having
) {
    $is_cm_user = fn_as_category_managers_get_cm_user_data()['is_cm_user'] ?? false;
    
    if ($is_cm_user == "Y") {
        $controller = Registry::get('runtime.controller');
        $mode = Registry::get('runtime.mode');

        if (!isset($params['cid'])) {
            $category_ids = fn_as_category_managers_get_cm_user_data()['cm_category_ids'] ?? 0;
    
            // If category_ids is not empty
            if (!empty($category_ids)) {
                $category_ids = explode(",", $category_ids);
                $params['cid'] = $category_ids;
            } else {
                $params['cid'] = [0];
            }
        }

        if ($controller == "categories" && $mode == "manage") {
            // Add join for counting products
            $join .= " LEFT JOIN ?:products_categories pc ON products.product_id = pc.product_id";
            $join .= " LEFT JOIN ?:categories ON pc.category_id = ?:categories.category_id";
        }
    }
}

function fn_as_category_managers_vendor_data_premoderation_request_approval_for_products_pre($product_ids, $update_product)
{
    if (!empty($product_ids)) {
        foreach ($product_ids as $product_id) {
            $main_category = fn_get_product_main_category_id($product_id);
            $cm_users = fn_as_category_managers_get_cm_user_by_category($main_category);

            if (!empty($cm_users)) {
                foreach ($cm_users as $cm_user) {
                    $user_data = db_get_row("SELECT email, firstname, lastname FROM ?:users WHERE user_id = ?i", $cm_user['user_id']);
                    $receiver = $user_data['email'];
                    $product_name = fn_get_product_name($product_id);

                    try {
                        $event_dispatcher = Tygh::$app['event.dispatcher'];
                        $event_data = [
                            'receiver' => $receiver,
                            'subject' => "New Product Premoderation Request",
                            'fullname' => ($user_data['firstname'] ?? '') . " " . ($user_data['lastname'] ?? ''),
                            'product_name' => $product_name,
                            'url'       => fn_url(
                                'admin:' . 'products.manage?status=' . ProductStatuses::REQUIRES_APPROVAL,
                                SiteArea::ADMIN_PANEL
                            )
                        ];
                        $event_dispatcher->dispatch('as_category_managers.added_product', $event_data);
                    } catch (\Throwable $th) {
                        error_log(__FILE__ . " " . __LINE__ . " " . $th->getMessage());
                    }
                }
            }
        }
    }
}

/**
 * Get category managers by category
 * 
 * @param int $category_id
 * 
 * @return array
 */
function fn_as_category_managers_get_cm_user_by_category($category_id)
{
    $users = db_get_array("SELECT user_id FROM ?:users WHERE cm_category_ids LIKE ?s", "%$category_id%");

    return $users;
}

/**
 * Get already assigned member ids
 * 
 * @return array
 */
function fn_as_category_managers_get_already_assigned_member_ids() : array
{
    $cm_users = db_get_array("SELECT user_id, cm_member_ids FROM ?:users WHERE is_cm_user = 'Y'");

    $already_assigned_member_ids = [];

    foreach ($cm_users as $cm_user) {
        $cm_member_ids = explode(",", $cm_user['cm_member_ids']);
        $already_assigned_member_ids = array_merge($already_assigned_member_ids, $cm_member_ids);
    }

    return $already_assigned_member_ids;
}

function fn_as_category_managers_shippings_group_products_list(&$products, &$groups)
{
    $categories_groups = array();
    foreach ($groups as $group) {
        foreach ($group['products'] as $cart_id => $product) {
            $main_category_id = fn_get_product_main_category_id($product['product_id']);
            $category_name = fn_get_category_name($main_category_id);
            $categories_group_key = $main_category_id ? $group['company_id'] . "_" . $main_category_id : $group['company_id'];

            if (empty($categories_groups[$categories_group_key]) && $main_category_id) {
                $categories_groups[$categories_group_key] = $group;
                $categories_groups[$categories_group_key]['main_category_id'] = $main_category_id;
                $categories_groups[$categories_group_key]['name'] = $group['name'] . ' (' . $category_name . ')';

                if (fn_allowed_for('ULTIMATE')) {
                    $categories_groups[$categories_group_key]['name'] = $category_name;
                }

                $categories_groups[$categories_group_key]['products'] = array();
            }

            if (empty($categories_groups[$categories_group_key]) && !$main_category_id) {
                $categories_groups[$categories_group_key] = $group;
                $categories_groups[$categories_group_key]['products'] = array();
            }

            $categories_groups[$categories_group_key]['products'][$cart_id] = $product;
            $categories_groups[$categories_group_key]['group_key'] = $categories_group_key;
        }
    }

    ksort($categories_groups);
    $groups = array_values($categories_groups);
}

function fn_as_category_managers_pre_place_order(&$cart, &$allow, &$product_groups)
{
    // products from different categories must have different group keys when placing suborders
    $new_product_groups = array();
    foreach ($product_groups as $key_group => $group) {
        if (empty($new_product_groups[$group['company_id']])) {
            $new_product_groups[$group['company_id']] = $group;
            $new_product_groups[$group['company_id']]['name'] = fn_get_company_name($group['company_id']);
            $new_product_groups[$group['company_id']]['products'] = array();
            $new_product_groups[$group['company_id']]['chosen_shippings'] = array();
            if (!empty($group['main_category_id'])) {
                unset($new_product_groups[$group['company_id']]['main_category_id']);
            }
        }

        if (!empty($group['main_category_id'])) {
            foreach ($group['products'] as $cart_id => $product) {
                $group['products'][$cart_id]['extra']['main_category_id'] = $group['main_category_id'];
                $cart['products'][$cart_id]['extra']['main_category_id'] = $group['main_category_id'];
            }
        }

        $supplier_groups = array();
        foreach ($group['products'] as $cart_id => $product) {
            if (!empty($cart['parent_order_id']) && isset($product['extra']['main_category_id'])) {
                $main_category_id = $product['extra']['main_category_id'];
                if (!isset($supplier_groups[$main_category_id])) {
                    $supplier_groups[$main_category_id] = count($supplier_groups);
                }
                $group['products'][$cart_id]['extra']['group_key'] = $supplier_groups[$main_category_id];
                $cart['products'][$cart_id]['extra']['group_key'] = $supplier_groups[$main_category_id];
            } else {
                $group['products'][$cart_id]['extra']['group_key'] = $key_group;
                $cart['products'][$cart_id]['extra']['group_key'] = $key_group;
            }
        }

        if (!empty($group['chosen_shippings'])) {
            if (!empty($cart['parent_order_id'])) {
                $group['chosen_shippings'][0]['group_key'] = $key_group;
            }
            if (empty($group['chosen_shippings'][0]['group_name'])) {
                $group['chosen_shippings'][0]['group_name'] = $group['name'];
            }
            $new_product_groups[$group['company_id']]['shippings'][$group['chosen_shippings'][0]['shipping_id']] = $group['chosen_shippings'][0];
            $new_product_groups[$group['company_id']]['chosen_shippings'] = array_merge($new_product_groups[$group['company_id']]['chosen_shippings'], $group['chosen_shippings']);
        }
        $new_product_groups[$group['company_id']]['products'] = $new_product_groups[$group['company_id']]['products'] + $group['products'];
    }

    $product_groups = array_values($new_product_groups);


    // Create new product groups based on main category
    $categories_groups = array();
    foreach ($product_groups as $group) {
        foreach ($group['products'] as $cart_id => $product) {
            $main_category_id = fn_get_product_main_category_id($product['product_id']);
            $category_name = fn_get_category_name($main_category_id);
            $categories_group_key = $main_category_id ? $group['company_id'] . "_" . $main_category_id : $group['company_id'];

            if (empty($categories_groups[$categories_group_key]) && $main_category_id) {
                $categories_groups[$categories_group_key] = $group;
                $categories_groups[$categories_group_key]['main_category_id'] = $main_category_id;
                $categories_groups[$categories_group_key]['name'] = $group['name'] . ' (' . $category_name . ')';

                if (fn_allowed_for('ULTIMATE')) {
                    $categories_groups[$categories_group_key]['name'] = $category_name;
                }

                $categories_groups[$categories_group_key]['products'] = array();
            }

            if (empty($categories_groups[$categories_group_key]) && !$main_category_id) {
                $categories_groups[$categories_group_key] = $group;
                $categories_groups[$categories_group_key]['products'] = array();
            }

            $categories_groups[$categories_group_key]['products'][$cart_id] = $product;
            $categories_groups[$categories_group_key]['group_key'] = $categories_group_key;
        }
    }

    ksort($categories_groups);
    $product_groups = array_values($categories_groups);
}

function fn_as_category_managers_create_order_details($order_id, &$cart, &$order_details, $extra)
{
    $product_id = $order_details['product_id'];

    // Add product_main_category_id to order_details
    $product_main_category_id = fn_get_product_main_category_id($product_id);
    $order_details['product_main_category_id'] = $product_main_category_id;
}

function fn_as_category_managers_get_orders($params, $fields, $sortings, &$condition, &$join, &$group)
{
    $cm_user_data = fn_as_category_managers_get_cm_user_data();
    $is_cm_user = $cm_user_data['is_cm_user'] ?? false;
    $is_cm_leader = $cm_user_data['is_cm_leader'] ?? false;

    if ($is_cm_user == "Y") {
        if ($is_cm_leader == "Y") {
            $category_ids = $cm_user_data['cm_category_ids'] ?? 0;

            // If category_ids is not empty
            if (!empty($category_ids)) {
                $category_ids = explode(",", $category_ids);
                $condition .= db_quote(" AND ?:order_details.product_main_category_id IN (?a)", $category_ids);
            } else {
                $condition .= db_quote(" AND ?:order_details.product_main_category_id = 0");
            }
    
            $join .= " LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";
    
            $group .= " GROUP BY ?:orders.order_id";
        } else {
            $condition .= db_quote(" AND ?:orders.assigned_cm_member_id = ?i", Tygh::$app['session']['auth']['user_id']);
        }
    }
}