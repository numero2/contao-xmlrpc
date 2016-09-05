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


/* PALETTES */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    ';{modules_legend'
,   ';{xmlrpc_legend:hide},xmlrpc_username,xmlrpc_password,xmlrpc_author,xmlrpc_filepath;{modules_legend'
,   $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);


/* FIELDS */
$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_username'] = array(
    'label'       => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_username']
,   'inputType'   => 'text'
,   'eval'        => array('mandatory'=>false, 'tl_class'=>'w50', 'readonly'=> false)
,   'load_callback' => array(array('tl_xmlrpc_settings','loadRandomWhenEmpty'))
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_password'] = array(
    'label'       => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_password']
,   'inputType'   => 'text'
,   'eval'        => array('mandatory'=>false, 'tl_class'=>'w50', 'readonly'=> false)
,   'load_callback' => array(array('tl_xmlrpc_settings','loadRandomWhenEmpty'))
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_author'] = array(
    'label'       => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_author']
,   'inputType'   => 'select'
,   'flag'        => 11
,   'foreignKey'  => 'tl_user.name'
,   'eval'        => array('mandatory'=>false, 'tl_class'=>'w50', 'chosen'=>true, 'includeBlankOption' => true)
,   'sql'         => "int(10) unsigned NOT NULL default '0'",
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['xmlrpc_filepath'] = array(
    'label'       => &$GLOBALS['TL_LANG']['tl_settings']['xmlrpc_filepath']
,   'inputType'   => 'fileTree'
,   'eval'        => array('mandatory'=>false, 'fieldType'=>'radio', 'foldersOnly'=>true, 'tl_class'=>'w50')
,   'sql'         => "binary(16) NULL"
);



class tl_xmlrpc_settings extends \Backend {


    /**
     * Setting random UUID for username and password
     * @param DataContainer
     */
    public function loadRandomWhenEmpty($value,DataContainer $dc){

        if( empty($value) ) {
            $value = StringUtil::binToUuid(md5(rand().time.rand()));
        }

        return $value;
    }
}
