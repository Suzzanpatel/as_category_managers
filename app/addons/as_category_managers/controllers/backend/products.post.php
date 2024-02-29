<?php

use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode === 'manage' || $mode === 'p_subscr') {
    // Get $search from main controller
    $search = Tygh::$app['view']->getTemplateVars('search');

    if (!empty($search['cid']) && is_array($search['cid'])) {
        // Change the $search['cid] value
        $search['cid'] = (string) implode(',', $search['cid']);
    
        // Assign to view
        Tygh::$app['view']->assign('search', $search);
    }
}