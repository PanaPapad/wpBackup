#!/bin/bash
# WordPress Backup Script
# Author: Panagiotis Papadopoulos (ppapad03@ucy.ac.cy)

# This is a template for the backup script.
# Copy this file to the root of your WordPress installation and rename it to backupScript.sh
# Then edit the variables below to suit your needs.

# !!! MAKE SURE THAT THIS FILE IS NOT ACCESSIBLE FROM THE WEB !!!
# !!! MAKE SURE THAT THIS FILE IS NOT ACCESSIBLE FROM THE WEB !!!
# !!! MAKE SURE THAT THIS FILE IS NOT ACCESSIBLE FROM THE WEB !!!
# Permissions should be 700 (rwx------) or 750 if you want to allow exec access to the group
# Ideally, make an exception for this file in the .htaccess file of your WordPress installation
# Example: "RewriteRule ^backupScript.sh$ - [R=404,L]"

# DB variables
DB_USER="root"
DB_PASS="password"
DB_NAME="wordpress"
DB_HOST="localhost"
# Backup directory
BACKUP_DIR="wp-content/backup"
# Date and time
DATE_NOW=$(date +%Y-%m-%d-%H-%M-%S)
# Backup file name scheme
DB_BACKUP_FILE_NAME="wordpress-$DATE_NOW.sql"
WP_BACKUP_FILE_NAME="wordpress-$DATE_NOW.tar.gz"

# Safety check
# If .htaaccess exists, make sure it contains the exception for this file
if [ -f ".htaccess" ]; then
    if ! grep -q "backupScript.sh" ".htaccess"; then
        echo "WARNING: .htaccess exists but does not contain the exception for this file."
        echo "Please add the following line to your .htaccess file:"
        echo "RewriteRule ^backupScript.sh$ - [R=404,L]"
        echo "Aborting."
        exit 1
    fi
fi
# Check if mysqldump is installed
if ! command -v mysqldump >/dev/null 2>&1; then
    echo "ERROR: mysqldump is not installed. Please install it and try again."
    exit 1
fi
# Check if credentials are correct
mysql -u $DB_USER -p$DB_PASS -h $DB_HOST -e "use $DB_NAME" 2>/dev/null
if [ $? -ne 0 ]; then
    echo "ERROR: Could not connect to database. Please check your credentials."
    exit 1
fi

# Ensure backup directory exists, if not create it
# if create fails, exit
mkdir -p $BACKUP_DIR
if [ $? -ne 0 ]; then
    echo "ERROR: Could not create backup directory. Please check your permissions."
    exit 1
fi

# MariaDB backup
mysqldump -u $DB_USER -p$DB_PASS -h $DB_HOST $DB_NAME > $BACKUP_DIR/$DB_BACKUP_FILE_NAME
# If mysqldump fails, exit
if [ $? -ne 0 ]; then
    echo "ERROR: mysqldump failed. Please check your credentials or permissions."
    exit 1
fi

# WordPress backup
tar -czf $BACKUP_DIR/$WP_BACKUP_FILE_NAME wp-content
# If tar fails, exit
if [ $? -ne 0 ]; then
    echo "ERROR: tar failed. Please check your permissions."
    exit 1
fi