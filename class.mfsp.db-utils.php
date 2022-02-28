<?php

// FS OBJECT

function dbListFs()
{
    global $wpdb;
    $result = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}dbmfsp_logical_filesystem ORDER BY object_text"
    );
    return $result;
}

function dbGetFs($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT
                *, concat(original_name, '.', extension) AS `filename`
            FROM {$wpdb->prefix}dbmfsp_logical_filesystem
            WHERE id=%d",
            intval($id)
        )
    );
    return $result;
}

function dbMakeRoot($id, $parent)
{
    global $wpdb;
    $wpdb->update(
        "{$wpdb->prefix}dbmfsp_logical_filesystem",
        ["parent" => $parent],
        ["id" => $id]
    );
}

function dbCreateFolder($name, $parent)
{
    global $wpdb;
    $created_at = date("Y-m-d H:i:s");
    $updated_at = date("Y-m-d H:i:s");
    $db_result = $wpdb->insert(
        "{$wpdb->prefix}dbmfsp_logical_filesystem",
        array(
            "object_text" => $name,
            "object_type" => "D",
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "parent" => $parent,
        )
    );
}

// SHARING

function dbGetSharing($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_sharing
            WHERE id=%d;",
            intval($id)
        )
    );
    return $result;
}



function dbUpdateSharing($id, $is_sharing, $max_num_of_downloads, $passphrase, $email_text)
{
    global $wpdb;
    $wpdb->update(
        "{$wpdb->prefix}dbmfsp_logical_sharing",
        [
            "is_sharing" => $is_sharing,
            "max_num_of_downloads" => $max_num_of_downloads,
            "passphrase" => $passphrase,
            "text" => $email_text
        ],
        ["id" => $id]
    );
}

function dbUpdateSharingExpiration($id, $expires)
{
    global $wpdb;
    $wpdb->update(
        "{$wpdb->prefix}dbmfsp_logical_sharing",
        [
            "expires" => $expires
        ],
        ["id" => $id]
    );
}

function dbCreateSharing($id)
{
    global $wpdb;
    $result = $wpdb->insert(
        "{$wpdb->prefix}dbmfsp_logical_sharing",
        array(
            "id" => $id,
            "text" => "Shared with you:\n{{URL}}"
        )
    );
    return $result;
}

function dbSharingDeleteProcedure($id)
{
    global $wpdb;
    $wpdb->get_results(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}dbmfsp_logical_sharing_users WHERE sharing_object=%d;",
            intval($id)
        )
    );
    $wpdb->get_results(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}dbmfsp_logical_sharing WHERE id=%d;",
            intval($id)
        )
    );
}

// SHARING USERS

function dbListSharingUser($sharing_object)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_sharing_users
            WHERE sharing_object=%d;",
            intval($sharing_object)
        )
    );
    return $result;
}

function dbGetSharingUser($sharing_object, $id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_sharing_users
            WHERE sharing_object=%d AND id=%d;",
            intval($sharing_object),
            intval($id),
        )
    );
    return $result;
}

function dbGetSharingUserBySecret($sharing_secret)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_sharing_users
            WHERE secret=%d",
            $sharing_secret
        )
    );
    return $result;
}

function dbCreateSharingUser($email, $sharing_object)
{
    global $wpdb;
    $result = $wpdb->insert(
        "{$wpdb->prefix}dbmfsp_logical_sharing_users",
        array(
            "sharing_object" => $sharing_object,
            "email" => $email,
            "secret" => generateUUID4(),
            "secret_explorer" => generateUUID4(),
        )
    );
    return $result;
}

function dbDeleteSharingUser($sharing_object, $id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "DELETE
            FROM {$wpdb->prefix}dbmfsp_logical_sharing_users
            WHERE sharing_object=%d AND id=%d;",
            intval($sharing_object),
            intval($id),
        )
    );
    return $result;
}

function dbSetSharingForSharingUser($sharing_user, $val)
{
    global $wpdb;
    $id = $sharing_user->id;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "UPDATE
            {$wpdb->prefix}dbmfsp_logical_sharing_users
            SET is_sharing=%d WHERE id=%d;",
            $val,
            intval($id),
        )
    );
    return $result;
}

function dbIncNodForSharingUser($sharing_user)
{
    global $wpdb;
    $id = $sharing_user->id;
    $current_num_of_downloads = $sharing_user->num_of_downloads;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "UPDATE
            {$wpdb->prefix}dbmfsp_logical_sharing_users
            SET num_of_downloads=%d WHERE id=%d;",
            $current_num_of_downloads + 1,
            intval($id),
        )
    );
    return $result;
}

function dbIncEmailSendedForSharingUser($sharing_user)
{
    global $wpdb;
    $id = $sharing_user->id;
    $current_num_of_sended = $sharing_user->num_of_sended;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "UPDATE
            {$wpdb->prefix}dbmfsp_logical_sharing_users
            SET num_of_sended=%d WHERE id=%d;",
            $current_num_of_sended + 1,
            intval($id),
        )
    );
    return $result;
}

function dbGetSharingUserBySecretExplorer($secret_explorer)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM {$wpdb->prefix}dbmfsp_logical_sharing_users
            WHERE secret_explorer=%s",
            $secret_explorer
        )
    );
    return $result;
}

// TAG

function dbListTag($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}dbmfsp_tags
            WHERE `fs_object`=%d",
            intval($id)
        )
    );
    return $result;
}

function dbCreateTag($fs_object, $text)
{
    global $wpdb;
    $result = $wpdb->insert(
        "{$wpdb->prefix}dbmfsp_tags",
        array(
            "fs_object" => $fs_object,
            "text" => $text
        )
    );
    return $result;
}

function dbDeleteTag($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}dbmfsp_tags WHERE id=%d;",
            intval($id)
        )
    );
    return $result;
}

function dbGetFsObjectByTagId($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}dbmfsp_tags
            WHERE `id`=%d",
            intval($id)
        )
    );
    return $result;
}

// NOTES

function dbListNote($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}dbmfsp_notes
            WHERE `fs_object`=%d",
            intval($id)
        )
    );
    return $result;
}

function dbCreateNote($fs_object, $text, $fullname)
{
    global $wpdb;
    $result = $wpdb->insert(
        "{$wpdb->prefix}dbmfsp_notes",
        array(
            "fs_object" => $fs_object,
            "text" => $text,
            "fullname" => $fullname,
        )
    );
    return $result;
}

function dbDeleteNote($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}dbmfsp_notes WHERE id=%d;",
            intval($id)
        )
    );
    return $result;
}

function dbGetFsObjectByNoteId($id)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}dbmfsp_notes
            WHERE `id`=%d",
            intval($id)
        )
    );
    return $result;
}
