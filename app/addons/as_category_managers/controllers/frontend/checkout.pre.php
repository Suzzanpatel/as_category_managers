<?php

use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/** @var array $cart */
$cart = &Tygh::$app['session']['cart'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($mode == 'place_order') {
        if (isset($_FILES['po_document']) && $_FILES['po_document']['error'] === UPLOAD_ERR_OK) {
            $documentPath = as_cm_upload_order_document(0, $_FILES['po_document'], 'po', 'po_documents');
            $cart['po_document_path'] = $documentPath;
        }
    }
}