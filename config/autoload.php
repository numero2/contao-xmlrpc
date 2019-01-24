<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2019 Leo Feyer
 *
 * @package   Contao XML-RPC
 * @author    Benny Born <benny.born@numero2.de>
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   LGPL-3.0+
 * @copyright 2019 numero2 - Agentur für digitales Marketing GbR
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
    'numero2\xmlrpc\XMLRPC'                     => 'system/modules/xmlrpc/classes/xmlrpc.php',
    'numero2\xmlrpc\Procedures'                 => 'system/modules/xmlrpc/classes/Procedures.php',
));
