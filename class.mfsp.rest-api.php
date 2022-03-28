<?php

$MFSP_PATH_UPLOAD_DIR_ROOT = "wp-content/uploads/mfsp";

class Mfsp_REST_API_Exception extends Exception
{
}

class Mfsp_REST_API
{
    private const BASE = "msfp/v1";

    public static function allow_Admin_Employee(WP_REST_Request $request)
    {
        return current_user_can('administrator') or current_user_can('mfsp_employee');
    }

    public static function permissionAlwaysAllow(WP_REST_Request $request)
    {
        return true;
    }

    /**
     * Register the REST API routes.
     */
    public static function init()
    {
        // register_rest_route(self::BASE, "/test", array(
        //     [
        //         "methods" => "GET",
        //         "callback" => array("Mfsp_REST_API", "testEndpoint"),
        //         "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
        //     ]
        // ));
        // SHARING
        register_rest_route(self::BASE, "/share/(?P<sharing_secret>(\w+-){4}\w+)", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "shareController"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ],
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "shareController"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<id>\d+)", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "getSharing"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ],
            [
                "methods" => "PATCH",
                "callback" => array("Mfsp_REST_API", "updateSharing"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<id>\d+)/send-bulk-emails", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "sharingSendBulkEmails"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ],
        ));
        register_rest_route(self::BASE, "/sharing/(?P<id>\d+)/delete-procedure", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "sharingDeleteProcedure"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ],
        ));
        register_rest_route(self::BASE, "/sharing/(?P<id>\d+)/set-expire", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "actionSetExpireSharing"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<id>\d+)/unset-expire", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "actionUnsetExpireSharing"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<id>\d+)/users", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "listSharingUsers"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ],
            [
                "methods" => "POST",
                "callback" => ["Mfsp_REST_API", "createSharingUser"],
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"]
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<sharing_object>\d+)/users/(?P<id>\d+)", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "getSharingUser"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ],
            [
                "methods" => "DELETE",
                "callback" => ["Mfsp_REST_API", "deleteSharingUser"],
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"]
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<sharing_object>\d+)/users/(?P<id>\d+)/send-email", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "actionSharingUserSendEmail"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ]
        ));
        register_rest_route(self::BASE, "/sharing/(?P<sharing_object>\d+)/users/(?P<id>\d+)/toggle-sharing", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "actionSharingUserToggleSharing"),
                "permission_callback" => ["Mfsp_REST_API", "allow_Admin_Employee"],
            ]
        ));


        // TAGS
        register_rest_route(self::BASE, "/tag", array(
            [
                "methods" => "POST",
                "callback" => ["Mfsp_REST_API", "createTag"],
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"]
            ],
        ));
        register_rest_route(self::BASE, "/tag/(?P<id>\d+)", array(
            [
                "methods" => "DELETE",
                "callback" => ["Mfsp_REST_API", "deleteTag"],
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"]
            ],
        ));
        register_rest_route(self::BASE, "/upload/(?P<id>\d+)/tags", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "getTagsForUpload"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));

        // NOTES
        register_rest_route(self::BASE, "/notes", array(
            [
                "methods" => "POST",
                "callback" => ["Mfsp_REST_API", "createNote"],
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"]
            ]
        ));

        register_rest_route(self::BASE, "/notes/(?P<id>\d+)", array(
            [
                "methods" => "DELETE",
                "callback" => ["Mfsp_REST_API", "deleteNote"],
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"]
            ]
        ));

        register_rest_route(self::BASE, "/upload/(?P<id>\d+)/notes", array(
            [
                "methods" => "GET",
                "callback" => ["Mfsp_REST_API", "listNotesForUpload"],
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"]
            ]
        ));

        // UPLOAD
        register_rest_route(self::BASE, "/upload", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "listUploadedFiles"),
                "permission_callback" => "__return_true",
            ],
            [
                "methods" => "POST",
                "callback" => ["Mfsp_REST_API", "uploadFile"],
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"]
            ]
        ));
        register_rest_route(self::BASE, "/upload/(?P<id>\d+)", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "getUploadedFile"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));

        // FOLDER
        register_rest_route(self::BASE, "/folder", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "createFolder"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
        register_rest_route(self::BASE, "/folder/(?P<id>\d+)", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "getFolder"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ],
            [
                "methods" => "DELETE",
                "callback" => array("Mfsp_REST_API", "deleteFolder"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));

        // OPERATIONS
        register_rest_route(self::BASE, "/rename/(?P<id>\d+)", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "renameObject"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
        register_rest_route(self::BASE, "/move", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "moveObjects"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
        register_rest_route(self::BASE, "/makeroot", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "makeRootObject"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
        register_rest_route(self::BASE, "/deleteBatch", array(
            [
                "methods" => "POST",
                "callback" => array("Mfsp_REST_API", "deleteBatch"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
        register_rest_route(self::BASE, "/downloadSelected/(?P<id>\d+)", array(
            [
                "methods" => "GET",
                "callback" => array("Mfsp_REST_API", "downloadSelected"),
                "permission_callback" => ["Mfsp_REST_API", "permissionAlwaysAllow"],
            ]
        ));
    }

    // public static function testEndpoint(WP_REST_Request $request)
    // {
    //     // global $WP_DEBUG;
    //     // $testtt = permSecretExplorer("05abcc7f-b700-4ff4-963f-e90cb8f66a70", 6);
    //     return rest_ensure_response(array(
    //         "success" => true,
    //         "msg" => "Test endpoint",
    //         "can_administrator" => current_user_can('administrator'),
    //         "can_mfsp_employee" => current_user_can('mfsp_employee'),
    //         "user" => wp_get_current_user(),
    //         "WP_DEBUG" => WP_DEBUG,
    //         // "testtt" => $testtt
    //     ));
    // }

    public static function shareController(WP_REST_Request $request)
    {
        global $wpdb;
        global $MFSP_PATH_UPLOAD_DIR_ROOT;
        $sharing_secret = $request->get_param("sharing_secret");
        // Get sharing user
        $sharing_user = dbGetSharingUserBySecret($sharing_secret);
        if (count($sharing_user) == 0) {
            wp_redirect("/404");
            exit();
        }
        $sharing_user = $sharing_user[0];
        // Get sharing object
        $sharing = dbGetSharing($sharing_user->sharing_object);
        if (count($sharing) == 0) {
            wp_redirect("/404");
            exit();
        }
        $sharing = $sharing[0];
        // Get fs object
        $fs_object = dbGetFs($sharing->id);
        if (count($fs_object) == 0) {
            wp_redirect("/404");
            exit();
        }
        $fs_object = $fs_object[0];
        // Check global sharing 
        if (!permCheckIfGlobalSharingTurnedOn($sharing)) {
            wp_redirect("/mfsp-sharing-off");
            exit();
        }
        // Check user sharing 
        if (!permCheckIfUserSharingTurnedOn($sharing_user)) {
            wp_redirect("/mfsp-sharing-off");
            exit();
        }
        // Check expired
        if (permCheckIfSharingExpired($sharing)) {
            wp_redirect("/mfsp-sharing-expired");
            exit();
        }
        // Check passphrase
        if ($sharing->passphrase != "") { // passphrase is set
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                wp_redirect("/mfsp-passphrase/$sharing_secret");
                exit();
            }
            $post_passphrase = $_POST["passphrase"];
            if ($post_passphrase == $sharing->passphrase) { // correct password
                // let pass
            } else { // wrong password
                wp_redirect("/mfsp-passphrase/$sharing_secret?wrong_passphrase=1");
                exit();
            }
        }
        // Check num of download only if file
        if ($fs_object->object_type == "F") {
            if ($sharing->max_num_of_downloads != 0) { // nod is set
                if ($sharing_user->num_of_downloads >= $sharing->max_num_of_downloads) {
                    wp_redirect("/mfsp-sharing-exhausted");
                    exit();
                } else {
                    dbIncNodForSharingUser($sharing_user);
                }
            }
        }
        if ($fs_object->object_type == "F") {
            $disk_path = "$MFSP_PATH_UPLOAD_DIR_ROOT/$fs_object->disk_name.$fs_object->extension";
            $fullname = $fs_object->object_text . "." . $fs_object->extension;
            header("Content-Type: " . $fs_object->mime_type);
            header('Content-Disposition: attachment; filename="' . $fullname . '"');
            $src = fopen($disk_path, "r") or die("Unable to open file!");
            $src_content = fread($src, filesize($disk_path));
            fclose($src);
            $src_content = msfpDecrypt($src_content);
            echo $src_content;
            exit();
        } else {
            wp_redirect("/mfsp-share-explorer?secret_explorer=$sharing_user->secret_explorer");
            exit();
        }
    }

    public static function actionSharingUserToggleSharing(WP_REST_Request $request)
    {
        global $wpdb;
        $sharing_object = $request->get_param("sharing_object");
        $id = $request->get_param("id");
        $sharing_user = dbGetSharingUser($sharing_object, $id);
        if (count($sharing_user) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $sharing_user = $sharing_user[0];
        dbSetSharingForSharingUser(
            $sharing_user,
            (boolval(intval($sharing_user->is_sharing))) ? 0 : 1
        );
        return rest_ensure_response(array(
            "success" => true,
            "id" => $sharing_user->id
        ));
    }

    public static function actionSetExpireSharing(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");
        $json = $request->get_json_params();
        $expires = $json["expires"];
        dbUpdateSharingExpiration($id, $expires);
        return rest_ensure_response(array(
            "success" => true
        ));
    }

    public static function actionSharingUserSendEmail(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $sharing_object = $request->get_param("sharing_object");
        $id = $request->get_param("id");
        // Query sharing
        $sharing = dbGetSharing($sharing_object);
        if (count($sharing) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $sharing = $sharing[0];
        // Query fs object
        $fs_object = dbGetFs($sharing->id);
        if (count($fs_object) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $fs_object = $fs_object[0];
        // Query sharing user
        $sharing_user = dbGetSharingUser($sharing->id, $id);
        if (count($sharing_user) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $sharing_user = $sharing_user[0];

        $result = sendSharingURLForSharingUser($fs_object, $sharing, $sharing_user);

        if ($result["success"]) {
            return rest_ensure_response(array(
                "success" => true,
                "msg" => "E-mail has been sent successfully",
                "sharing_user_id" => $sharing_user->id,
                "sharing_user_email" => $sharing_user->email,
            ));
        } else {
            $msg = $result["success"];
            return rest_ensure_response(array(
                "success" => false,
                "msg" => "PHPMailer Error: $msg",
            ));
        }
    }

    public static function actionUnsetExpireSharing(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");
        dbUpdateSharingExpiration($id, null);
        return rest_ensure_response(array(
            "success" => true
        ));
    }

    /**
     */
    public static function getSharing(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $id = $request->get_param("id");
        // Query object 
        $sharing_result = dbGetSharing($id);
        if (count($sharing_result) == 0) {
            dbCreateSharing($id);
            $sharing_result = dbGetSharing($id);
        }
        $fs_result = dbGetFs($id);
        if (count($fs_result) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $fs_result = $fs_result[0];
        $sharing_result = $sharing_result[0];
        return rest_ensure_response([
            "id" => $sharing_result->id,
            "text" => $sharing_result->text,
            "expires" => $sharing_result->expires,
            "max_num_of_downloads" => intval($sharing_result->max_num_of_downloads),
            "passphrase" => $sharing_result->passphrase,
            "is_sharing" => boolval(intval($sharing_result->is_sharing)),
            "fs_object" => buildFsResponse($fs_result)
        ]);
    }

    /**
     */
    public static function sharingSendBulkEmails(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $id = $request->get_param("id");

        $sharing = dbGetSharing($id);
        if (count($sharing) == 0) {
            dbCreateSharing($id);
            $sharing = dbGetSharing($id);
        }
        $fs_object = dbGetFs($id);
        if (count($fs_object) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $fs_object = $fs_object[0];
        $sharing = $sharing[0];

        $success = array();
        $failure = array();
        $sharing_users = dbListSharingUser($sharing->id);
        foreach ($sharing_users as $sharing_user) {
            $result = sendSharingURLForSharingUser($fs_object, $sharing, $sharing_user);
            if ($result["success"]) {
                array_push($success, [
                    "email" => $sharing_user->email
                ]);
            } else {
                $msg = $result["success"];
                array_push($failure, [
                    "email" => $sharing_user->email,
                    "msg" => $msg
                ]);
            }
        }

        return rest_ensure_response([
            "success" => $success,
            "failure" => $failure
        ]);
    }

    /**
     */
    public static function sharingDeleteProcedure(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $id = $request->get_param("id");
        // Query object 
        $sharing = dbGetSharing(intval($id));
        if (count($sharing) != 0) {
            $sharing = $sharing[0];
            dbSharingDeleteProcedure($sharing->id);
        }
        return rest_ensure_response([
            "success" => true
        ]);
    }

    public static function updateSharing(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $id = $request->get_param("id");
        $json = $request->get_json_params();
        $is_sharing = $json["is_sharing"];
        $max_num_of_downloads = $json["max_num_of_downloads"];
        $passphrase = $json["passphrase"];
        $email_text = $json["email_text"];
        dbUpdateSharing($id, $is_sharing, $max_num_of_downloads, $passphrase, $email_text);
        return rest_ensure_response(array(
            "success" => true
        ));
    }

    /**
     */
    public static function listSharingUsers(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $id = $request->get_param("id");
        // Query object
        $sharing_users = dbListSharingUser($id);
        $ret = array();
        foreach ($sharing_users as $sharing_user) {
            $json_element = array(
                "id" => intval($sharing_user->id),
                "email" => $sharing_user->email,
                "secret" => $sharing_user->secret,
                "num_of_downloads" => intval($sharing_user->num_of_downloads),
                "num_of_sended" => intval($sharing_user->num_of_sended),
                "is_sharing" => boolval(intval($sharing_user->is_sharing)),
            );
            array_push($ret, $json_element);
        }
        return rest_ensure_response($ret);
    }

    public static function getSharingUser(WP_REST_Request $request)
    {
        global $wpdb;
        // Get GET param
        $sharing_object = $request->get_param("sharing_object");
        $id = $request->get_param("id");
        // Query object
        $sharing_user = dbGetSharingUser($sharing_object, $id);
        if (count($sharing_user) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }
        $sharing_user = $sharing_user[0];
        return rest_ensure_response(array(
            "id" => intval($sharing_user->id),
            "text" => $sharing_user->email,
            "num_of_downloads" => intval($sharing_user->num_of_downloads),
            "num_of_sended" => intval($sharing_user->num_of_sended),
            "is_sharing" => boolval(intval($sharing_user->is_sharing)),
        ));
    }

    public static function createSharingUser(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");
        $json = $request->get_json_params();
        $email = $json["email"];
        $email = strtolower($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = dbCreateSharingUser($email, $id);
            return rest_ensure_response(array(
                "success" => true
            ));
        } else {
            return rest_ensure_response(array(
                "success" => false,
                "msg" => "Provided email is invalid",
            ));
        }
    }

    public static function deleteSharingUser(WP_REST_Request $request)
    {
        global $wpdb;
        $sharing_object = $request->get_param("sharing_object");
        $id = $request->get_param("id");
        dbDeleteSharingUser($sharing_object, $id);
        return rest_ensure_response(array(
            "success" => true
        ));
    }

    /**
     */
    public static function downloadSelected(WP_REST_Request $request)
    {
        global $wpdb;
        global $MFSP_PATH_UPLOAD_DIR_ROOT;
        // Get GET param
        $object_id = $request->get_param("id");

        // Query object 
        $query_result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                FROM {$wpdb->prefix}dbmfsp_logical_filesystem
                WHERE id=%d;",
                intval($object_id)
            )
        );
        // 404 if not found
        if (count($query_result) == 0) {
            return new WP_Error('no_object', 'No object found', array('status' => 404));
        }

        if (!hasIdPerm(intval($object_id)) and !isSrcId(intval($object_id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $record = $query_result[0];
        if ($record->object_type == "F") { // File
            $disk_path = "$MFSP_PATH_UPLOAD_DIR_ROOT/$record->disk_name.$record->extension";
            $fullname = $record->object_text . "." . $record->extension;
            header("Content-Type: " . $record->mime_type);
            header('Content-Disposition: attachment; filename="' . $fullname . '"');
            echo msfpDecrypt(file_get_contents($disk_path));
            exit();
        }
        if ($record->object_type == "D") { // Dir
            // $temp_filename = sha1(
            //     $record->object_text . "|" . time() . "|" . mt_rand() . "|" . sin(mt_rand())
            // );
            $temp_filename = generateDiskName($record->object_text);
            if (!is_dir("$MFSP_PATH_UPLOAD_DIR_ROOT")) {
                mkdir("$MFSP_PATH_UPLOAD_DIR_ROOT", 0755);
            }
            if (!is_dir("$MFSP_PATH_UPLOAD_DIR_ROOT/temp")) {
                mkdir("$MFSP_PATH_UPLOAD_DIR_ROOT/temp", 0755);
            }
            $temp_zip_filepath = "$MFSP_PATH_UPLOAD_DIR_ROOT/temp/$temp_filename.zip";
            $zipObj = new ZipArchive();
            $zipObj->open($temp_zip_filepath, ZIPARCHIVE::CREATE);
            fsZipDirectory("", $record, $zipObj);
            $zipObj->close();
            header("Content-Type: application/zip");
            header('Content-Disposition: attachment; filename="' . $record->object_text . '.zip"');
            echo file_get_contents($temp_zip_filepath);
            unlink($temp_zip_filepath);
            exit();
        }
    }

    /**
     */
    public static function createTag(WP_REST_Request $request)
    {
        global $wpdb;
        $json = $request->get_json_params();
        $fs_object = $json["fs_object"];
        $text = $json["text"];

        if (!hasIdPerm(intval($fs_object)) and !isSrcId(intval($fs_object))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        dbCreateTag($fs_object, $text);
        return rest_ensure_response([
            "success" => true,
            "text" => $text,
        ]);
    }

    /**
     */
    public static function deleteTag(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");

        $tag = dbGetFsObjectByTagId($id);
        if (count($tag) == 0) {
            return new WP_Error('no_folder', 'No folder found', array('status' => 404));
        }
        $tag = $tag[0];

        $fs_id = $tag->fs_object;

        if (!hasIdPerm(intval($fs_id)) and !isSrcId(intval($fs_id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        dbDeleteTag($id);
        return rest_ensure_response([
            "success" => true
        ]);
    }

    /**
     */
    public static function getTagsForUpload(WP_REST_Request $request)
    {
        global $wpdb;
        $object_id = $request->get_param("id");

        if (!hasIdPerm(intval($object_id)) and !hasSrcIdPerm(intval($object_id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $tags = dbListTag($object_id);
        $ret = array();
        foreach ($tags as $qr) {
            $json_element = array(
                "id" => intval($qr->id),
                "text" => $qr->text
            );
            array_push($ret, $json_element);
        }
        return rest_ensure_response($ret);
    }

    /**
     */
    public static function createNote(WP_REST_Request $request)
    {
        global $wpdb;
        global $current_user;
        $json = $request->get_json_params();
        $fs_object = $json["fs_object"];

        if (!hasIdPerm(intval($fs_object)) and !isSrcId(intval($fs_object))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $text = $json["text"];
        $fullname = $current_user->ID == 0 ? "Klient" : $current_user->data->user_nicename;
        dbCreateNote($fs_object, $text, $fullname);
        return rest_ensure_response([
            "success" => true
        ]);
    }

    /**
     */
    public static function listNotesForUpload(WP_REST_Request $request)
    {
        global $wpdb;
        $object_id = $request->get_param("id");

        if (!hasIdPerm(intval($object_id)) and !hasSrcIdPerm(intval($object_id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $notes = dbListNote($object_id);
        $ret = array();
        foreach ($notes as $qr) {
            $json_element = array(
                "id" => intval($qr->id),
                "text" => $qr->text,
                "fullname" => $qr->fullname,
            );
            array_push($ret, $json_element);
        }
        return rest_ensure_response($ret);
    }

    /**
     */
    public static function deleteNote(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");

        $note = dbGetFsObjectByNoteId($id);
        if (count($note) == 0) {
            return new WP_Error('no_folder', 'No folder found', array('status' => 404));
        }
        $note = $note[0];

        $fs_id = $note->fs_object;

        if (!hasIdPerm(intval($fs_id)) and !isSrcId(intval($fs_id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        dbDeleteNote($id);
        return rest_ensure_response([
            "success" => true
        ]);
    }

    /**
     */
    public static function deleteBatch(WP_REST_Request $request)
    {
        global $wpdb;
        $json = $request->get_json_params();
        $deletion_ids = $json["deletion_ids"];
        foreach ($deletion_ids as $did) {
            if (!hasIdPerm(intval($did))) {
                return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
            }
        }
        foreach ($deletion_ids as $did) {
            $query_result = $wpdb->get_results(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->prefix}dbmfsp_logical_filesystem WHERE id=%d;",
                    intval($did)
                )
            );
        }
        return rest_ensure_response([
            "success" => true
        ]);
    }


    /**
     */
    public static function makeRootObject(WP_REST_Request $request)
    {
        global $wpdb;
        $json = $request->get_json_params();
        $id = $json["id"];
        if (!is_int($id)) {
            return new WP_Error(
                'id_not_int',
                'ID is not integer',
                array('status' => 400)
            );
        }
        $id = intval($id);

        if (permCheckIsEmployeeOrAdmin()) {
            dbMakeRoot($id, null);
            return rest_ensure_response([
                "success" => true,
                "msg" => "Moved to fs(id=$id)",
            ]);
        }

        if (hasIdPerm($id)) {
            $src_id = maybeGetSrcId();
            if ($src_id != null) {
                dbMakeRoot($id, intval($src_id));
                return rest_ensure_response([
                    "success" => true,
                    "msg" => "Moved to fs(id=$src_id)",
                ]);
            }
        }

        return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
    }

    /**
     */
    public static function moveObjects(WP_REST_Request $request)
    {
        global $wpdb;
        $json = $request->get_json_params();
        $source_ids = $json["source_ids"];
        $destination_id = $json["destination_id"];

        // Check if ids are integers
        if (!is_int($destination_id)) {
            return new WP_Error(
                'destination_id_not_int',
                'Can\'t move objects here', // Destination ID is not integer
                array('status' => 400)
            );
        }
        if (array_filter($source_ids, 'is_int') === array()) {
            return new WP_Error(
                'source_ids_not_int',
                'Source IDs are not integers',
                array('status' => 400)
            );
        }

        // Check if there is no self-nested folder in source
        foreach ($source_ids as $sid) {
            if ($sid == $destination_id) {
                return new WP_Error(
                    'self_nested_folder',
                    'You can\'t move object to itself',
                    array('status' => 400)
                );
            }
        }

        // Check if destination is folder and not file
        $query_result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT
                    *
                FROM {$wpdb->prefix}dbmfsp_logical_filesystem
                WHERE id=%d AND object_type='D';",
                intval($destination_id)
            )
        );
        if (count($query_result) == 0) {
            return new WP_Error(
                'destination_is_not_folder',
                'Destination is not a folder',
                array('status' => 400)
            );
        }

        // Check perms for dst folder
        if (!hasIdPerm(intval($destination_id)) and !isSrcId(intval($destination_id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        // Check perms for moved files
        foreach ($source_ids as $sid) {
            if (!hasIdPerm(intval($sid))) {
                return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
            }
        }

        // Check if any parent is being moved to its child
        $children_ids = array();
        foreach ($source_ids as $sid) { // for each object being moved
            // get fs object and skip if is file
            $fs_object = dbGetFs($sid);
            if (count($fs_object) == 0) {
                continue;
            }
            $fs_object = $fs_object[0];
            if ($fs_object->object_type == "F") {
                continue;
            }
            getAllChildrenIdsByParentId($sid, $children_ids);
            if (in_array($destination_id, $children_ids)) {
                return new WP_Error(
                    'self_nested_folder',
                    'You can\'t move parent to its child',
                    array('status' => 400)
                );
            }
        }

        // Move objects to destination
        foreach ($source_ids as $sid) {
            $wpdb->update(
                "{$wpdb->prefix}dbmfsp_logical_filesystem",
                ["parent" => $destination_id],
                ["id" => $sid]
            );
        }

        return rest_ensure_response([
            "success" => true
        ]);
    }

    /**
     */
    public static function renameObject(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");
        $json = $request->get_json_params();

        if (!hasIdPerm(intval($id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $wpdb->update(
            "{$wpdb->prefix}dbmfsp_logical_filesystem",
            [
                "object_text" => $json["new_name"],
                "updated_at" => date("Y-m-d H:i:s"),
            ],
            ["id" => $id]
        );

        return rest_ensure_response([
            "success" => true
        ]);
    }

    /**
     */
    public static function getFolder(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");

        if (!hasIdPerm(intval($id)) and !isSrcId(intval($id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $query_result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                FROM {$wpdb->prefix}dbmfsp_logical_filesystem
                WHERE id=%d AND object_type='D';",
                intval($id)
            )
        );

        if (count($query_result) == 0) {
            return new WP_Error('no_folder', 'No folder found', array('status' => 404));
        }
        $record = $query_result[0];

        return rest_ensure_response(buildFsResponse($record));
    }

    /**
     */
    public static function deleteFolder(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");

        if (!hasIdPerm(intval($id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }

        $query_result = $wpdb->get_results(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}dbmfsp_logical_filesystem WHERE id=%d AND object_type='D';",
                intval($id)
            )
        );
        return rest_ensure_response([
            "success" => true
        ]);
    }

    /**
     */
    public static function createFolder(WP_REST_Request $request)
    {
        $json = $request->get_json_params();

        if (permCheckIsEmployeeOrAdmin()) {
            dbCreateFolder($json["name"], null);
            return rest_ensure_response([
                "name" => $json["name"]
            ]);
        }
        $src_id = maybeGetSrcId();
        if ($src_id != null) {
            dbCreateFolder($json["name"], intval($src_id));
            return rest_ensure_response([
                "name" => $json["name"]
            ]);
        }
        return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
    }

    /**
     */
    public static function getUploadedFile(WP_REST_Request $request)
    {
        global $wpdb;
        $id = $request->get_param("id");
        if (!hasIdPerm(intval($id)) and !isSrcId(intval($id))) {
            return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
        }
        $result = dbGetFs($id);

        if (count($result) == 0) {
            return new WP_Error('no_file', 'No file found', array('status' => 404));
        }
        $record = $result[0];
        $response = buildFsResponse($record);
        return rest_ensure_response($response);
    }


    /**
     */
    public static function listUploadedFiles(WP_REST_Request $request)
    {
        global $JSTREE_ICONS;
        global $wpdb;
        $query_result = dbListFs();
        $ret = array();
        foreach ($query_result as $qr) {
            $fsid = intval($qr->id);
            if (!hasIdPerm($fsid) and !isSrcId($fsid)) {
                continue;
            }
            $curr_type = ($qr->object_type == "F") ? "file" : "folder";
            if ($curr_type == "file") {
                $curr_type = $JSTREE_ICONS[$qr->mime_type] ?? "file";
            }

            $parent = intval($qr->parent);

            // unset parent for share root directory
            $src_id = isSrcId($fsid);
            if ($src_id  != null) {
                $parent = "#";
            }

            $json_element = array(
                "id" => $fsid,
                "type" => $curr_type,
                "parent" => ($qr->parent) ? $parent : "#",
                "text" => ($qr->object_type == "F") ? "{$qr->object_text}.{$qr->extension}" : $qr->object_text,
                "state" => array(
                    "opened" => ($qr->object_type == "F") ? false : true
                )
            );
            array_push($ret, $json_element);
        }
        return rest_ensure_response($ret);
    }

    /**
     */
    public static function uploadFile(WP_REST_Request $request)
    {
        global $EXTENSIONS_MAP;
        global $wpdb;
        global $MFSP_PATH_UPLOAD_DIR_ROOT;
        // $myParam = $request->get_param('my_param');

        $permitted_extensions = [];
        $permitted_types = [];

        // Build from extension map
        foreach ($EXTENSIONS_MAP as $ea_extension => $ea_mime_types) {
            array_push($permitted_extensions, $ea_extension);
            foreach ($ea_mime_types as $ea_mime_type) {
                array_push($permitted_types, $ea_mime_type);
            }
        }

        $files = $request->get_file_params();

        if (WP_DEBUG) {
            error_log(print_r($files, true));
        }
        // $headers = $request->get_headers();

        if (!empty($files) && !empty($files['file'])) {
            $file = $files['file'];
            if (WP_DEBUG) {
                error_log(realpath($file['tmp_name']));
            }
        } else {
            throw new Mfsp_REST_API_Exception('No file send');
        }


        // Tells whether the file was uploaded via HTTP POST
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new Mfsp_REST_API_Exception('File upload check with is_uploaded_file failed');
        }

        // Confirm that there is no file errors
        if (!$file['error'] === UPLOAD_ERR_OK) {
            throw new Mfsp_REST_API_Exception('Upload error: ' . $file['error']);
        }

        // // Confirm that extension is allowed
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = pathinfo($file['name'], PATHINFO_FILENAME);

        if (!in_array($ext, $permitted_extensions)) {
            return rest_ensure_response([
                'success' => false,
                "error" => array(
                    "pl" => "Rozszerzenie jest zabronione",
                    "en" => "Extension is not permitted"
                )
            ]);
        }
        // Check MIME type
        $mimeType = mime_content_type($file['tmp_name']);
        if (
            !in_array($file['type'], $permitted_types)
            ||
            !in_array($mimeType, $permitted_types)
        ) {
            return rest_ensure_response([
                'success' => false,
                "error" => array(
                    "pl" => "Typ MIME jest zabroniony",
                    "en" => "Mime type is not permitted"
                )
            ]);
        }

        // Create Upload folder
        if (!is_dir("$MFSP_PATH_UPLOAD_DIR_ROOT")) {
            mkdir("$MFSP_PATH_UPLOAD_DIR_ROOT", 0755);
        }

        // Protect from directory indexing
        $silence_path = "$MFSP_PATH_UPLOAD_DIR_ROOT/index.php";
        if (!is_file($silence_path)) {
            file_put_contents($silence_path, "<!-- Silence is golden -->");
        }

        // Move file in filesystem
        $disk_name = generateDiskName($filename . $ext);
        $disk_path = "$MFSP_PATH_UPLOAD_DIR_ROOT/{$disk_name}.{$ext}";


        // OLD
        // move_uploaded_file($file['tmp_name'], $disk_path);
        // NEW
        $src = fopen($file['tmp_name'], "r") or die("Unable to open tmp_name file!");
        $dst = fopen($disk_path, "w") or die("Unable to open disk_path file!");
        $src_content = fread($src, filesize($file['tmp_name']));
        fclose($src);
        fwrite($dst, msfpEncrypt($src_content));
        fclose($dst);
        // END

        $modtime = filemtime("$MFSP_PATH_UPLOAD_DIR_ROOT/" . $file['name']);

        if ($modtime) {
            $created_at = date("Y-m-d H:i:s", $modtime);
            $updated_at = date("Y-m-d H:i:s", $modtime);
        } else {
            $created_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
        }

        if (permCheckIsEmployeeOrAdmin()) {
            $parent_id = null;
        } else {
            $parent_id = maybeGetSrcId();
            if ($parent_id == null) {
                return new WP_Error('forbidden', 'Forbidden', array('status' => 403));
            }
        }

        $db_result = $wpdb->insert(
            "{$wpdb->prefix}dbmfsp_logical_filesystem",
            array(
                "parent" => $parent_id,
                "original_name" => $filename,
                "disk_name" => $disk_name,
                "extension" => $ext,
                "mime_type" => $mimeType,
                "object_text" => $filename,
                "object_type" => "F",
                "created_at" => $created_at,
                "updated_at" => $updated_at
            )
        );
        if (!$db_result) {
            return new WP_Error(
                'query_exception',
                'Exception: ' . $wpdb->last_error,
                array('status' => 404)
            );
        }

        return rest_ensure_response([
            'success' => true,
            "is_logged_in" => is_user_logged_in(),
            "mime_type" => $mimeType,
        ]);
    }
}
