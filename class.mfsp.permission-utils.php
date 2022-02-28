<?php

/**
 * Check if sharing has set global sharing flag
 */
function permCheckIfGlobalSharingTurnedOn($sharing)
{
    return boolval(intval($sharing->is_sharing));
}

/**
 * Check if sharing user has set local sharing flag
 */
function permCheckIfUserSharingTurnedOn($sharing_user)
{
    return boolval(intval($sharing_user->is_sharing));
}

/**
 * Check if sharing expired
 */
function permCheckIfSharingExpired($sharing)
{
    if ($sharing->expires) {
        $now_local = toCurrentTimezone(date("Y-m-d H:i:s", time()));
        $expires_local = toCurrentTimezone($sharing->expires);
        if ($now_local > $expires_local) {
            return true;
        }
    }
    return false;
}

/**
 * Check if user is admin or employee
 */
function permCheckIsEmployeeOrAdmin()
{
    return current_user_can('administrator') or current_user_can('mfsp_employee');
}

/**
 * Returns secret_explorer GET param if set else null
 */
function maybeGetSecretExplorer()
{
    if (isset($_GET["secret_explorer"]) and $_GET["secret_explorer"] != "") {
        return $_GET["secret_explorer"];
    }
    return null;
}

// TODO: Refactor below

/**
 * Try to get fs src id by secret explorer
 */
function maybeGetSrcIdBySecretExplorer($secret_explorer)
{
    // Try to get sharing user
    $sharing_user = dbGetSharingUserBySecretExplorer($secret_explorer);
    if (count($sharing_user) == 0) {
        return null;
    }
    $sharing_user = $sharing_user[0];
    // Try to get sharing object
    $sharing = dbGetSharing($sharing_user->sharing_object);
    if (count($sharing) == 0) {
        return null;
    }
    $sharing = $sharing[0];
    //// Check permissions
    // Check global sharing, user sharing, expired
    if (
        !permCheckIfGlobalSharingTurnedOn($sharing) ||
        !permCheckIfUserSharingTurnedOn($sharing_user) ||
        permCheckIfSharingExpired($sharing)
    ) {
        return null;
    }
    return intval($sharing_user->sharing_object);
}


/**
 * Check if sharing user connected with secret explorer has
 * perm to given id
 */
function permSecretExplorer($secret_explorer, $fsobject_dst_id)
{
    $fsobject_src_id = maybeGetSrcIdBySecretExplorer($secret_explorer);
    if ($fsobject_src_id == null) {
        return false;
    }
    $arrOfIds = array();
    getAllChildrenIdsByParentId($fsobject_src_id, $arrOfIds);
    return in_array(intval($fsobject_dst_id), $arrOfIds);
}

/**
 * Try to get src id
 */
function maybeGetSrcId()
{
    $secret_explorer = maybeGetSecretExplorer();
    if ($secret_explorer == null) {
        return false;
    }
    $src_id = maybeGetSrcIdBySecretExplorer($secret_explorer);
    return $src_id;
}

/**
 * Check if user has perms to given child of src
 */
function hasIdPerm($dst_id)
{
    if (permCheckIsEmployeeOrAdmin()) {
        return true;
    }
    $secret_explorer = maybeGetSecretExplorer();
    if ($secret_explorer == null) {
        return false;
    }
    return permSecretExplorer($secret_explorer, $dst_id);
}

/**
 * Check if given id is src id
 */
function isSrcId($src_id_candid)
{
    return maybeGetSrcId() == $src_id_candid;
}

/**
 * Check if user has perms to given src id
 */
function hasSrcIdPerm($dst_id)
{
    if (permCheckIsEmployeeOrAdmin()) {
        return true;
    }
    return isSrcId($dst_id);
}
