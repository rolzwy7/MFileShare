<?php

function fsZipDirectory($current_path, $record, &$zipObj)
{
    global $wpdb;
    // Get children
    $children = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_filesystem
            WHERE parent=%d;",
            intval($record->id)
        )
    );
    // Iterate through children, add to zip archive
    foreach ($children as $child) {
        if ($child->object_type == "F") { // File
            $disk_path = "wp-content/uploads/mfsp/$child->disk_name.$child->extension";
            $fullname = "$child->object_text.$child->extension";
            $zip_file_path = ($current_path == "" ? $current_path : "$current_path/") . $fullname;

            $src = fopen($disk_path, "r") or die("Unable to open file!");
            $src_content = fread($src, filesize($disk_path));
            fclose($src);
            $zipObj->addFromString($zip_file_path, msfpDecrypt($src_content));
        }
        if ($child->object_type == "D") { // Directory
            $zip_dir_path = ($current_path == "" ? $current_path : "$current_path/") . $child->object_text;
            $zipObj->addEmptyDir($zip_dir_path);
            fsZipDirectory($zip_dir_path, $child, $zipObj);
        }
    }
}

function getAllChildrenIdsByParentId($parent_id, &$arrayOfIds)
{
    global $wpdb;
    // Get children
    $children = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_filesystem
            WHERE parent=%d;",
            intval($parent_id)
        )
    );
    // Iterate through children
    foreach ($children as $child) {
        if ($child->object_type == "F") { // File
            array_push($arrayOfIds, intval($child->id));
            continue;
        }
        if ($child->object_type == "D") { // Directory
            array_push($arrayOfIds, intval($child->id));
            getAllChildrenIdsByParentId($child->id, $arrayOfIds);
        }
    }
}

function toCurrentTimezone($date)
{
    $timezone = new DateTimeZone("Europe/Warsaw"); // TODO: options ?
    $ret = new DateTime($date, new DateTimeZone("UTC"));
    $ret->setTimezone($timezone);
    return $ret->format('Y-m-d H:i:s');
}

function generateUUID4()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function generateDiskName($str)
{
    return hash(
        'sha256',
        $str . "|" . time() . "|" . mt_rand() . "|" . sin(mt_rand())
    );
}

function buildFsFileResponse($record)
{
    return [
        "id" => intval($record->id),
        "type" => "FILE",
        "original_name" => $record->original_name,
        "extension" => $record->extension,
        "filename" => $record->filename,
        "text" => $record->object_text,
        "created_at" => toCurrentTimezone($record->created_at),
        "updated_at" => toCurrentTimezone($record->updated_at),
        "mime_type" => $record->mime_type
    ];
}

function buildFsDirResponse($record)
{
    return [
        "id" => intval($record->id),
        "type" => "DIR",
        "text" => $record->object_text,
        "created_at" => toCurrentTimezone($record->created_at),
        "updated_at" => toCurrentTimezone($record->updated_at),
    ];
}

function buildFsResponse($record)
{
    if ($record->object_type == "F") {
        return buildFsFileResponse($record);
    } else {
        return buildFsDirResponse($record);
    }
}


function getWebrootURI()
{
    $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $host = $_SERVER['HTTP_HOST'];
    return "$proto://$host";
}
