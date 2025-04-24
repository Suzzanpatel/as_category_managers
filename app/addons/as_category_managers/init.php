<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'fill_auth',
    'get_user_type_description',
    'get_users_pre',
    'get_users',
    'update_user_pre',
    'update_profile',
    'get_categories',
    'get_products_before_select',
    'shippings_group_products_list',
    'pre_place_order',
    'create_order_details',
    'get_orders',
    'vendor_data_premoderation_request_approval_for_products_pre',
    'vendor_data_premoderation_approve_products_pre',
    'vendor_data_premoderation_disapprove_products_pre',
    'update_order_details_post',
);