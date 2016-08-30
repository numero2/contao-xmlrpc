<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   xmlrpc
 * @author    Benny Born <benny.born@numero2.de>
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   Commercial
 * @copyright 2016 numero2 - Agentur für Internetdienstleistungen
 */

/**
 * Namespace
 */
namespace numero2\xmlrpc;

class xmlrpc extends \System {


    /**
	 * Log file name
	 * @var string
	 */
    protected $strLogFile = 'xml_requests.log';

    /**
     * Instantiate the object
     */
    public function __construct() {

        $this->import('Config');
        $this->import('Input');
        parent::__construct();
    }

    static function foo ($params) {

        echo "<pre>".print_r($params->getParam(0)[0]->me['i4'],1)."</pre>\n";
        echo "<pre>".print_r($params->getParam(0)[1]->me['string'],1)."</pre>\n";
        echo "<pre>".print_r(\Config::get(xmlrpc_username),1)."</pre>\n";
        echo "<pre>".print_r($params->getParam(0)[2]->me['string'],1)."</pre>\n";
        echo "<pre>".print_r(\Config::get(xmlrpc_password),1)."</pre>\n";
        die();

        // echo "<pre>".print_r("done",1)."</pre>";
        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value("true", \PhpXmlRpc\Value::$xmlrpcString));
    }

    public function run(){

        $requestBody = file_get_contents('php://input');

        if( empty($requestBody) ) {
            header('HTTP/1.0 204 No Content');
            echo "No data was sent by the client.";
            $this->logRequest('HTTP/1.0 204 No Content');
            die();
        }
        $this->logRequest($requestBody);

        $map = array(
            'wp.help' => array(
                'function' => '\PhpXmlRpc\Server::_xmlrpcs_methodHelp',
                'signature' => array(array(\PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcString)),
                'docstring' => 'Returns help text if defined for the method passed, otherwise returns an empty string',
                'signature_docs' => array(array('method description', 'name of the method to be described'))
            )
        );

        $map = array_merge($map, Procedures::getProcedureMap());

        // add cto. prefix methods also as wp. methods
        foreach( $map as $key => $value) {

            if( strpos($key, "cto.") === 0 ){
                $map["wp.".substr($key, 4)] = $value;
            }
        }
        $server = new \PhpXmlRpc\Server($map);

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
    static public function authenticateUser($username=NULL, $password=NULL){

        if( $username != NULL && $password != NULL ){

            if( $username === \Config::get(xmlrpc_username) && $password === \Config::get(xmlrpc_password) ){
                return true;
            }
        }

        header('HTTP/1.0 401 Unauthorized');
        echo "The provided credentials are invalid.";
        $this->logRequest('HTTP/1.0 401 Unauthorized - '.$username.' : '.$password);
        die();

    }
}
