# XML-RPC for Contao

Provides an XML-RPC interface for contao similar to the one from wordpress.

### .htaccess
Add the following line to your `.htaccess` inside the `<IfModule mod_rewrite.c>` block to make the xmlrpc interface accessible from within your document root rather than just the modules subdirectory.
```
RewriteRule xmlrpc\.php system/modules/xmlrpc/xmlrpc\.php [L]
```
To prevent caching of responses add the following snippet inside your `<IfModule mod_expires.c>` block.
```
<FilesMatch "xmlrpc.php">
    Expires "access"
</FilesMatch>
```