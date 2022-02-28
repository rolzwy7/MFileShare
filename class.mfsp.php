<?php

class Mfsp
{
    private static $initialized = false;

    public static function init()
    {
        if (!self::$initialized) {
            self::init_hooks();
        }
    }

    /**
     * Init hooks
     */
    private static function init_hooks()
    {
        self::$initialized = true;

        add_role(
            'mfsp_employee',
            __('MFSP Employee'),
            array(
                'read' => true
            )
        );

        $role_admin = get_role('administrator');
        $role_admin->add_cap('manage_mfsp', true);

        $role_user = get_role('mfsp_employee');
        $role_user->add_cap('manage_mfsp', true);
    }

    /**
     * Activation hook
     */
    public static function plugin_activation()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Create Filesystem Table
        $fs_table_name = $wpdb->prefix . 'dbmfsp_logical_filesystem';

        $sql = "CREATE TABLE `$fs_table_name` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `parent` BIGINT(20) UNSIGNED NULL,
            `original_name` VARCHAR(255) NULL,
            `disk_name` VARCHAR(80) NULL,
            `extension` VARCHAR(20) NULL,
            `mime_type` VARCHAR(80) NULL,
            `object_text` VARCHAR(255) NOT NULL,
            `object_type` ENUM('F', 'D') NOT NULL,
            `created_at` DATETIME NULL DEFAULT NULL,
            `updated_at` DATETIME NULL DEFAULT NULL,
            `is_shared` BIT(1) NOT NULL DEFAULT b'0',

            PRIMARY KEY (`id`) USING BTREE,
            CONSTRAINT `msfp_parent_key` FOREIGN KEY (`parent`)
            REFERENCES `$fs_table_name` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
        )
        $charset_collate;
        ENGINE=InnoDB
        ;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$fs_table_name'") != $fs_table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        // Create Tags Table
        $tags_table_name = $wpdb->prefix . 'dbmfsp_tags';

        $sql = "CREATE TABLE `$tags_table_name` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `fs_object` BIGINT(20) UNSIGNED NOT NULL,
            `text` VARCHAR(64) NOT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            CONSTRAINT `msfp_tag_fs_object_key` FOREIGN KEY (`fs_object`)
            REFERENCES `$fs_table_name` (`id`) ON DELETE CASCADE
        )
        $charset_collate;
        ENGINE=InnoDB
        ;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$tags_table_name'") != $tags_table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        // Create Notes Table
        $tags_table_name = $wpdb->prefix . 'dbmfsp_notes';

        $sql = "CREATE TABLE `$tags_table_name` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `fs_object` BIGINT(20) UNSIGNED NOT NULL,
            `text` VARCHAR(512) NOT NULL,
            `fullname` VARCHAR(64) NOT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            CONSTRAINT `msfp_note_fs_object_key` FOREIGN KEY (`fs_object`)
            REFERENCES `$fs_table_name` (`id`) ON DELETE CASCADE
        )
        $charset_collate;
        ENGINE=InnoDB
        ;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$tags_table_name'") != $tags_table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        // Create Fs Sharing Table
        $sharing_table_name = $wpdb->prefix . 'dbmfsp_logical_sharing';

        $sql = "CREATE TABLE `$sharing_table_name` (
            `id` BIGINT(20) UNSIGNED NOT NULL,
            `text` TEXT NOT NULL DEFAULT '',
            `expires` DATETIME NULL, 
            `max_num_of_downloads` INT UNSIGNED NOT NULL DEFAULT 0,
            `passphrase` VARCHAR(32) NOT NULL DEFAULT '',
            `is_sharing` BIT(1) NOT NULL DEFAULT b'1',
            PRIMARY KEY (`id`) USING BTREE
        )
        $charset_collate;
        ENGINE=InnoDB
        ;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$sharing_table_name'") != $sharing_table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        // Create Fs Sharing Users Table
        $sharing_users_table_name = $wpdb->prefix . 'dbmfsp_logical_sharing_users';

        $sql = "CREATE TABLE `$sharing_users_table_name` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `sharing_object` BIGINT(20) UNSIGNED NOT NULL,
            `email` VARCHAR(80) NOT NULL,
            `secret` VARCHAR(80) NOT NULL,
            `secret_explorer` VARCHAR(80) NOT NULL,
            `num_of_downloads` INT UNSIGNED NOT NULL DEFAULT 0,
            `num_of_sended` INT UNSIGNED NOT NULL DEFAULT 0,
            `is_sharing` BIT(1) NOT NULL DEFAULT b'1',
            PRIMARY KEY (`id`) USING BTREE,

            UNIQUE KEY(sharing_object, email),

            CONSTRAINT `msfp_sharing_object_key` FOREIGN KEY (`sharing_object`)
            REFERENCES `$sharing_table_name` (`id`) ON DELETE CASCADE
        )
        $charset_collate;
        ENGINE=InnoDB
        ;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$sharing_users_table_name'") != $sharing_users_table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        // Update database version
        update_option("mfsp_db_version", MFSP__DATABASE_VERSION);
    }

    /**
     * Deactivation hook
     */
    public static function plugin_deactivation()
    {
    }
}
