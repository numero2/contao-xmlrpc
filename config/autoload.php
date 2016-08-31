<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   Contao XML-RPC
 * @author    Benny Born <benny.born@numero2.de>
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   Commercial
 * @copyright 2016 numero2 - Agentur für Internetdienstleistungen
 */



/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
    'numero2\xmlrpc',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
    'numero2\xmlrpc\XMLRPC'                     => 'system/modules/xmlrpc/classes/XMLRPC.php',
    'numero2\xmlrpc\Procedures'                 => 'system/modules/xmlrpc/classes/Procedures.php',

    // Vendor
    'PhpXmlRpc\Client'                          => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Client.php',
    'PhpXmlRpc\Encoder'                         => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Encoder.php',
    'PhpXmlRpc\PhpXmlRpc'                       => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/PhpXmlRpc.php',
    'PhpXmlRpc\Request'                         => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Request.php',
    'PhpXmlRpc\Response'                        => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Response.php',
    'PhpXmlRpc\Server'                          => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Server.php',
    'PhpXmlRpc\Value'                           => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Value.php',
    'PhpXmlRpc\Wrapper'                         => 'system/modules/xmlrpc/vendor/phpxmlrpc/src/Wrapper.php',
    'PhpXmlRpc\Helper\Charset'                  => 'system\modules\xmlrpc\vendor\phpxmlrpc\src\Helper\Charset.php',
    'PhpXmlRpc\Helper\Date'                     => 'system\modules\xmlrpc\vendor\phpxmlrpc\src\Helper\Date.php',
    'PhpXmlRpc\Helper\Http'                     => 'system\modules\xmlrpc\vendor\phpxmlrpc\src\Helper\Http.php',
    'PhpXmlRpc\Helper\Logger'                   => 'system\modules\xmlrpc\vendor\phpxmlrpc\src\Helper\Logger.php',
    'PhpXmlRpc\Helper\XMLParser'                => 'system\modules\xmlrpc\vendor\phpxmlrpc\src\Helper\XMLParser.php',
));
