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


/* PALETTES */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    ';{modules_legend'
,   ';{xmlrpc_legend:hide},xmlrpc_username,xmlrpc_password,xmlrpc_path,xmlrpc_author,xmlrpc_http_auth,xmlrpc_filepath;{modules_legend'
,   $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);


/* FIELDS */
$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_username'] = array(
    'label'             => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_username']
,   'inputType'         => 'text'
,   'eval'              => array('mandatory'=>false, 'tl_class'=>'w50', 'readonly'=> false)
,   'load_callback'     => array(array('tl_xmlrpc_settings','loadRandomWhenEmpty'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_password'] = array(
    'label'             => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_password']
,   'inputType'         => 'text'
,   'eval'              => array('mandatory'=>false, 'tl_class'=>'w50', 'readonly'=> false)
,   'load_callback'     => array(array('tl_xmlrpc_settings','loadRandomWhenEmpty'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_path'] = array(
    'label'             => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_path']
,   'inputType'         => 'text'
,   'eval'              => array('mandatory'=>false, 'tl_class'=>'w50')
,   'load_callback'     => array(array('tl_xmlrpc_settings','getModulePath'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_http_auth'] = array(
    'label'             => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_http_auth']
,   'inputType'         => 'checkbox'
,   'eval'              => array('tl_class'=>'w50')
,   'sql'               => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_author'] = array(
    'label'             => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_author']
,   'inputType'         => 'select'
,   'flag'              => 11
,   'foreignKey'        => 'tl_user.name'
,   'eval'              => array('mandatory'=>false, 'tl_class'=>'w50', 'chosen'=>true, 'includeBlankOption' => true)
,   'sql'               => "int(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_filepath'] = array(
    'label'             => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_filepath']
,   'inputType'         => 'fileTree'
,   'eval'              => array('mandatory'=>false, 'fieldType'=>'radio', 'foldersOnly'=>true, 'tl_class'=>'w50')
,   'sql'               => "binary(16) NULL"
);


class tl_xmlrpc_settings extends \Backend {


    /**
     * Setting random UUID as username and password
     *
     * @param DataContainer $dc
     * @param string $value
     *
     * @return string
     */
    public function loadRandomWhenEmpty( $value, DataContainer $dc ){

        if( empty($value) ) {
            $value = StringUtil::binToUuid(md5(rand().time.rand()));
        }

        return $value;
    }


    /**
     * Return path for the XMLRPC module
     *
     * @param DataContainer $dc
     * @param string $value
     *
     * @return string
     */
    public function getModulePath($value, DataContainer $dc) {

        return Environment::get('base').'system/modules/xmlrpc/';
    }
}