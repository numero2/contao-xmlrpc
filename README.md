# XML-RPC for Contao

Provides an XML-RPC interface for Contao similar to the one from Wordpress.

### .htaccess
Add the following line to your `.htaccess` inside the `<IfModule mod_rewrite.c>` block to make the XML-RPC interface accessible from within your document root rather than just the modules subdirectory.
```
RewriteRule xmlrpc\.php system/modules/xmlrpc/xmlrpc\.php [L]
```
To prevent caching of responses add the following snippet inside your `<IfModule mod_expires.c>` block.
```
<FilesMatch "xmlrpc.php">
    Expires "access"
</FilesMatch>
```
Because tools like Scompler might like to link directly into the Wordpress backend
we need an extra rewrite to handle those to correctly redirect to Contao.
```
RewriteCond %{REQUEST_URI} wp-admin/post\.php [NC]
RewriteCond %{QUERY_STRING} ^action=edit&post=(.*)
RewriteRule (.*) contao/main\.php?do=news&table=tl_content&id=%1 [R=301,L]
```