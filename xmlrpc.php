<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   SonntagScout
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   Commercial
 * @copyright 2015 numero2 - Agentur für Internetdienstleistungen
 */

// Set the script name
define('TL_SCRIPT', 'import.php');

// Initialize the system
define('TL_MODE', 'FE');
define('BYPASS_TOKEN_CHECK',true);
require '../../../system/initialize.php';
// Run the importer
$controller = new \numero2\xmlrpc\xmlrpc;
$controller->run();
