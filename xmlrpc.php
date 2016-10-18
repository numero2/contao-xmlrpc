<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   Contao XML-RPC
 * @author    Benny Born <benny.born@numero2.de>
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   LGPL-3.0+
 * @copyright 2016 numero2 - Agentur für Internetdienstleistungen
 */


// Set the script name
define('TL_SCRIPT', 'import.php');

// Initialize the system
define('TL_MODE', 'FE');
define('BYPASS_TOKEN_CHECK',true);
require '../../../system/initialize.php';

// Run the importer
$controller = new \numero2\xmlrpc\XMLRPC;
$controller->run();