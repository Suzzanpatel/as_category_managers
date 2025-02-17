<?php

use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'get_member_list') {
    $page_number = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
    $page_size = isset($_REQUEST['page_size']) ? (int) $_REQUEST['page_size'] : 10;
    $search_query = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;

    $cm_member_ids = fn_as_category_managers_get_member_ids($auth['user_id']);
    $exclude_user_ids = db_get_fields("SELECT user_id FROM ?:users WHERE user_id NOT IN (?a)", $cm_member_ids);

    $params = array(
        'area' => 'A',
        'page' => $page_number,
        'extended_search' => false,
        'search_query' => $search_query,
        'items_per_page' => $page_size,
        'exclude_user_types' => array ('V', 'C'),
        'exclude_user_ids' => $exclude_user_ids,
    );

    list($users, $params) = fn_get_users($params, $auth, $page_size);

    $objects = array_values(array_map(function ($customer_list) {
        $customer_name = trim($customer_list['firstname'] . ' ' . $customer_list['lastname']);
        return array(
            'id' => $customer_list['user_id'],
            'text' => $customer_name ? $customer_name : $customer_list['email'],
            'email' => $customer_list['email'],
            'phone' => $customer_list['phone'],
        );
    }, $users));

    Tygh::$app['ajax']->assign('objects', $objects);
    Tygh::$app['ajax']->assign('total_objects', isset($params['total_items']) ? $params['total_items'] : count($objects));

    exit;
}