# XML-RPC for Contao

Provides an XML-RPC interface for contao similar to the one from wordpress.

### .htaccess
Add the following line to your `.htaccess` to make the xmlrpc interface accessible
from within your document root
```
RewriteRule xmlrpc\.php system/modules/xmlrpc/xmlrpc\.php [L]
```