# wpBackup
## Description
This is a simple backup tool for WordPress. It allows you to backup your WordPress database and wp-content folder.

## Installation
1. Upload the script template to your WordPress root directory.
2. !Important: Rename the script template to backupScript.sh and make it executable.
3. !Important: Ensure that the script is not accessible from the web. You can do this by adding the following to your .htaccess file:
```
RewriteRule ^backupScript.sh$ - [R=404,L]
```
4. Open the script for editing and change the configuration variables to match your environment.
5. Run the Script using bash.