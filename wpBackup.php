<?php
/*
Deprecated: Replaced by SCRIPT Template
Plugin Name: WpBackup
Description: This plugin creates backups of the WordPress database.
Version: 0.9
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
    $filename = plugin_dir_path(__FILE__) . 'backup.sh';
    $handle = fopen($filename, 'w+');
    $data = "#!/bin/bash\n";
    #Ensure that the directory exists
    $data .= "mkdir -p $BACKUP_DIR\n";
    #Setup current date and time for filename
    $data .= "DATE=`date +%Y-%m-%d` # Date format\n";
    #Write DB backup command
    $data .= "mysqldump --user=$DB_USER --password=$DB_PASSWORD --host=$DB_HOST $DB_NAME > $BACKUP_DIR$DB_NAME-$" ."DATE.sql\n";
    #Write command to Backup wp-content folder
    $data .= "tar -zcvf $BACKUP_DIR'wp-content.tar.gz' $WP_ROOT'wp-content'\n";

    #In case we are on a Windows machine replace backslashes with forward slashes in our backup script
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $data = str_replace('\\', '/', $data);
    }
    fwrite($handle, $data);
    fclose($handle);
    //Give exec rights to the user
    chmod($filename, 0755);
}

// Create Admin Menu
function wpBackup_admin_menu() {
    add_menu_page('WpBackup', 'WpBackup', 'administrator', 'wpBackup', 'wpBackup_admin_page', 'dashicons-backup', 6);
}
function wpBackup_admin_page(){
    echo '<h1>WpBackup</h1>';
    echo '<p>Create Backup Script</p>';
    echo '<p>The script will be created under the plugin directory</p>';
    echo '<form action="" method="post">';
    echo '<input type="submit" name="submit" value="Create Script">';
    echo '</form>';
    if(isset($_POST['submit'])){
        generate_backup_script();
    }
}