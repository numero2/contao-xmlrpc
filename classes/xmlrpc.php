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
 * Namespace
 */
namespace numero2\xmlrpc;

class XMLRPC extends \System {


    /**
	 * Log file name
	 * @var string
	 */
    protected $strLogFile = 'xmlrpc.log';


    /**
     * Instantiate the object
     */
    public function __construct() {

        $this->import('Config');
        $this->import('Input');
        parent::__construct();
    }


    /**
     * Initialize XML-RPC interface
     *
     * @return none
     */
    public function run() {

        $requestBody = file_get_contents('php://input');

        if( empty($requestBody) ) {
            header('HTTP/1.0 204 No Content');
            echo "No data was sent by the client.";
            $this->logRequest('HTTP/1.0 204 No Content');
            die();
        }

        $this->logRequest($requestBody);

        $map = array();
        $map = array_merge($map, Procedures::getProcedureMap());

        // copy all methods with "wp." prefix to make it wordpress compatible
        foreach( $map as $key => $value ) {

            if( strpos($key, "cto.") === 0 ) {
                $map["wp.".substr($key, 4)] = $value;
            }
        }

        new \PhpXmlRpc\Server($map);
    }


    /**
     * Logs the given message into our logfile
     *
     * @param string $message
     *
     * @return bool
     */
    private function logRequest( $message=NULL ) {

        if( empty($message) )
            return false;

        $clientID = NULL;
        $clientID = \Environment::get('ip');

        $msg = sprintf("[%s] %s",$clientID,$message);

        log_message($msg, $this->strLogFile);
    }


    /**
     * Authenticates an user
     *
     * @param  string $username
     * @param  string $password
     *
     * @return boolean
     */
    static public function authenticateUser($username=NULL, $password=NULL) {

        if( $username != NULL && $password != NULL ){

            if( $username === \Config::get('xmlrpc_username') && $password === \Config::get('xmlrpc_password') ) {
                return true;
            }
        }

        header('HTTP/1.0 401 Unauthorized');
        $this->logRequest('HTTP/1.0 401 Unauthorized - '.$username.' : '.$password);
        die();
    }
}