<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function as_cm_upload_order_document(int $orderID, array $files, string $docType, string $folderName) : string
{
    die(json_encode([
        'order_id' => $orderID,
        'files' => $files,
        'doc_type' => $docType,
        'folder_name' => $folderName,
    ]));

    $uploadDir = fn_get_files_dir_path() . $folderName;

    if (!is_dir($uploadDir)) {
        fn_mkdir($uploadDir);
    }

    $docKey = 'file_' . $docType . '_document';

    if (
        (isset($files[$docKey]['name']) && $files[$docKey]['error'] === UPLOAD_ERR_OK) ||
        (isset($files['name']) && $files['error'] === UPLOAD_ERR_OK)
    ) {
        $isNested = isset($files[$docKey]);
        $fileInfo = $isNested ? $files[$docKey] : $files;

        if (!empty($orderID)) {
            as_cm_delete_order_document($orderID, $docType);
        }

        $fileName = TIME . '_' . $fileInfo['name'];
        $fileTmpPath = $fileInfo['tmp_name'];

        move_uploaded_file($fileTmpPath, $uploadDir . '/' . $fileName);
    } else {
        return '';
    }

    $documentPath = $folderName . '/' . $fileName;

    if (!empty($orderID)) {
        db_query("UPDATE ?:orders SET `" . $docType . "_document_path` = ?s WHERE order_id = ?i", $documentPath, $orderID);
    }

    return $documentPath;
}

function as_cm_delete_order_document(int $orderID, string $docType) : bool
{
    $file = db_get_field("SELECT " . $docType . "_document_path FROM ?:orders WHERE order_id = ?i", $orderID);

    if ($file) {
        $filePath = fn_get_files_dir_path() . $file;
        if (file_exists($filePath)) {
            unlink($filePath);
            db_query("UPDATE ?:orders SET `" . $docType . "_document_path` = NULL WHERE order_id = ?i", $orderID);
        }
    }

    return true;
}