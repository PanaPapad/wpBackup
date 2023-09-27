<?php
/*
Plugin Name: WpBackup
Description: This plugin creates backups of the WordPress database.
Version: 0.5
Author: Panagiotis Papadopoulos
*/

add_action('admin_menu', 'wpBackup_admin_menu');
function generate_backup_script() {
    $DB_NAME = DB_NAME;
    $DB_USER = DB_USER;
    $DB_PASSWORD = DB_PASSWORD;
    $DB_HOST = DB_HOST;
    $BACKUP_DIR = plugin_dir_path(__FILE__) . 'Backups/';
    $WP_ROOT = ABSPATH;

    //Create bash file for user to execute
    $filename = $BACKUP_DIR . 'backup.sh';
    $handle = fopen($filename, 'w+');
    $data = "#!/bin/bash\n";
    #Write DB backup command
    $data .= "mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR$DB_NAME.sql\n";
    #Write command to Backup wp-content folder
    $data .= "tar -zcvf $BACKUP_DIR'wp-content.tar.gz' $WP_ROOT'wp-content'\n";
    fwrite($handle, $data);
    fclose($handle);

}

// Create Admin Menu
function wpBackup_admin_menu() {
    add_menu_page('WpBackup', 'WpBackup', 'administrator', 'wpBackup', 'wpBackup_admin_page', 'dashicons-backup', 6);
}
function wpBackup_admin_page(){
    echo '<h1>WpBackup</h1>';
    echo '<p>Backup your database</p>';
    echo '<form action="" method="post">';
    echo '<input type="submit" name="submit" value="Backup">';
    echo '</form>';
    if(isset($_POST['submit'])){        
        generate_backup_script();
    }
}