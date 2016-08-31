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

class Procedures extends \System {

    /**
     * Returns the procedure map for setting up the server
     *
     * @return array
     */
    static public function getProcedureMap(){
        return array(
            "cto.getOptions" => array(
                "function" => "Procedures::getOptions"
            ,   "signature" => array(array(\PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcArray))
            ,   "docstring" => "Used to perform a connection check"
            )
        ,   "cto.getUsersBlogs" => array(
                "function" => "Procedures::getUsersBlogs"
            ,   "signature" => array(array(\PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcString))
            ,   "docstring" => "Queries which blogs can be reached over this integration."
            )
        ,   "cto.getPost" => array(
                "function" => "Procedures::getPost"
            ,   "signature" => array(array(\PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcArray))
            ,   "docstring" => "Used to gather information about the post."
            )
        ,   "cto.newPost" => array(
                "function" => "Procedures::newPost"
            ,   "signature" => array(array(\PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcArray))
            ,   "docstring" => "Used to create a new post."
            )
        );
    }


    /**
     * Scompler is using the wp.getOptions as the connection check endpoint. It provides basic
     * infos about the blog which enables an easy validation. The result of the call isn't used,
     * but it has to be successful (Code 200).
     *
     * @param array $params
     */
    static public function getOptions($params) {

        xmlrpc::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value("ok", \PhpXmlRpc\Value::$xmlrpcString));
    }


    /**
     * Queries which blogs can be reached over this integration.
     *
     * @param array $params
     */
    static public function getUsersBlogs($params) {

        xmlrpc::authenticateUser($params->getParam(0)->me['string'], $params->getParam(1)->me['string']);

        $archives = NULL;
        $archives = \NewsArchiveModel::findAll();

        $res = array();
        foreach ($archives as $key => $value) {

            $entry = array(
                isAdmin => new \PhpXmlRpc\Value(true, \PhpXmlRpc\Value::$xmlrpcBoolean)
            ,   url => new \PhpXmlRpc\Value(\Environment::get('base'), \PhpXmlRpc\Value::$xmlrpcString)
            ,   blogid => new \PhpXmlRpc\Value($archives->id, \PhpXmlRpc\Value::$xmlrpcString)
            ,   blogName => new \PhpXmlRpc\Value($archives->title, \PhpXmlRpc\Value::$xmlrpcString)
            ,   xmlrpc => new \PhpXmlRpc\Value(\Environment::get('base').\Environment::get('request'), \PhpXmlRpc\Value::$xmlrpcString)
            );

            $res[] = new \PhpXmlRpc\Value($entry, \PhpXmlRpc\Value::$xmlrpcStruct);
        }

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, \PhpXmlRpc\Value::$xmlrpcArray));
    }


    /**
     * Used to gather information about the post.
     *
     * @param array $params
     */
    static public function getPost($params) {
/* <?xml version="1.0" ?><methodCall>
  <methodName>wp.getPost</methodName>
  <params><param><value><array><data>
    <value><i4>the-blog-id</i4></value>
    <value><string>the-username</string></value>
    <value><string>the-password</string></value>
    <value><string>the-post-id</string></value>
    <value><array><data>
      <value><string>post</string></value>
      <value><string>terms</string></value>
      <value><string>custom_fields</string></value>
    </data></array></value>
  </data></array></value></param></params>
</methodCall>
*/
        // xmlrpc::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

/*      <?xml version="1.0" encoding="UTF-8"?>
        <methodResponse>
          <params><param><value><struct>
            <member><name>post_id</name><value><string>the-id</string></value></member>
            <member><name>link</name><value><string>http://example.com/path/to/?p=343</string></value></member>
            <!-- ...and so on -->
          </struct></value></param></params>
        </methodResponse>
        */
        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value("true", \PhpXmlRpc\Value::$xmlrpcString));
    }


    /**
     * Used to create a new post.
     *
     * @param array $params
     */
    static public function newPost($params) {

        xmlrpc::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

/*      <?xml version="1.0" encoding="UTF-8"?>
        <methodResponse>
          <params><param>
            <value><string>the-post-id</string></value>
          </param></params>
        </methodResponse>
        */
        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value("true", \PhpXmlRpc\Value::$xmlrpcString));
    }

}
