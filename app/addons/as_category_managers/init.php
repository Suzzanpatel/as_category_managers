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
    'vendor_data_premoderation_request_approval_for_products_pre',
);