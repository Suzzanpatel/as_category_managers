<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'member_migration') {
    $is_col_exists = db_get_row("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '?:users' AND COLUMN_NAME = 'cm_member_ids' AND TABLE_SCHEMA = DATABASE()");

    if (empty($is_col_exists)) {
        die(json_encode(array('success' => true, 'code' => 0)));
    }

    $users = db_get_array("SELECT user_id, cm_member_ids FROM ?:users WHERE cm_member_ids IS NOT NULL");

    foreach ($users as $user) {
        if (empty($user['cm_member_ids'])) continue;

        $cm_member_ids = explode(",", $user['cm_member_ids']);
        foreach ($cm_member_ids as $cm_member_id) {
            if (db_get_field("SELECT 1 FROM ?:cm_members WHERE leader_id = ?i AND member_id = ?i", $user['user_id'], $cm_member_id)) {
                continue;
            }

            db_query("INSERT INTO ?:cm_members (leader_id, member_id) VALUES (?i, ?i)", $user['user_id'], $cm_member_id);
        }
    }

    die(json_encode(array('success' => true, 'code' => 1)));
}