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

class Procedures extends \System {


    /**
     * Returns the procedure map for setting up the server
     *
     * @return array
     */
    static public function getProcedureMap() {

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
        ,   "cto.getPosts" => array(
                "function" => "Procedures::getPosts"
            ,   "signature" => array(array(\PhpXmlRpc\Value::$xmlrpcString, \PhpXmlRpc\Value::$xmlrpcArray))
            ,   "docstring" => "Used to gather information about several posts."
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

        XMLRPC::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value("ok", \PhpXmlRpc\Value::$xmlrpcString));
    }


    /**
     * Queries which blogs can be reached over this integration.
     *
     * @param array $params
     */
    static public function getUsersBlogs($params) {

        XMLRPC::authenticateUser($params->getParam(0)->me['string'], $params->getParam(1)->me['string']);

        $archives = NULL;
        $archives = \NewsArchiveModel::findAll();

        $res = array();
        foreach( $archives as $key => $value ) {

            $entry = array(
                'isAdmin' => new \PhpXmlRpc\Value(true, \PhpXmlRpc\Value::$xmlrpcBoolean)
            ,   'url' => new \PhpXmlRpc\Value(\Environment::get('base'), \PhpXmlRpc\Value::$xmlrpcString)
            ,   'blogid' => new \PhpXmlRpc\Value($archives->id, \PhpXmlRpc\Value::$xmlrpcString)
            ,   'blogName' => new \PhpXmlRpc\Value($archives->title, \PhpXmlRpc\Value::$xmlrpcString)
            ,   'xmlrpc' => new \PhpXmlRpc\Value(\Environment::get('base').\Environment::get('request'), \PhpXmlRpc\Value::$xmlrpcString)
            );

            $res[] = new \PhpXmlRpc\Value($entry, \PhpXmlRpc\Value::$xmlrpcStruct);
        }

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, \PhpXmlRpc\Value::$xmlrpcArray));
    }


    /**
     * Used to gather information about several posts.
     *
     * @param array $params
     */
    static public function getPosts($params) {

        XMLRPC::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

        $blogID = $params->getParam(0)[0]->me['i4'];
        $search = $params->getParam(0)[3]->me['struct'];

        $offset = $search['offset']->me['i4'];
        $rows = $search['number']->me['i4'];
        $posts = \Database::getInstance()->prepare(
            "SELECT
                n.id,
                n.pid,
                n.alias,
                n.headline,
                n.author,
                n.date,
                n.time,
                n.published,
                n.start,
                n.stop,
                na.jumpTo,
                c.text
            FROM tl_news AS n
            JOIN tl_content AS c ON (c.pid= n.id)
            JOIN tl_news_archive AS na ON (na.id = n.pid)
            WHERE n.pid= ? AND c.ptable = 'tl_news'
            ORDER BY n.date DESC
            "
            )->limit($rows, $offset)->execute($blogID);
            // echo "<pre>".print_r($search['post_type']->me['string'],1)."</pre>\n";
            // echo "<pre>".print_r($search['orderby']->me['string'],1)."</pre>\n";
            // echo "<pre>".print_r($search['order']->me['string'],1)."</pre>\n";

        $res = array();
        if( $posts )
        while( $posts->next() || $posts->count() == 1 ){

            $page = \PageModel::findById($posts->jumpTo);

            $url = "";
            if( !empty($page->id) ){
                // TODO find better qay to get the right url
                $url = \Environment::get('base').\Controller::generateFrontendUrl( $page->row(), "/".$posts->alias );
            }

            $entry = array(
                    'post_id' => new \PhpXmlRpc\Value( $posts->id, \PhpXmlRpc\Value::$xmlrpcString)
                ,   'blog_id' => new \PhpXmlRpc\Value( $blogID, \PhpXmlRpc\Value::$xmlrpcString)
                ,   'link' => new \PhpXmlRpc\Value( $url, \PhpXmlRpc\Value::$xmlrpcString)
                ,   'terms' => new \PhpXmlRpc\Value( array(), \PhpXmlRpc\Value::$xmlrpcArray)
                ,   'custom_fields' => new \PhpXmlRpc\Value( array(), \PhpXmlRpc\Value::$xmlrpcArray)
            );
            $res[] = new \PhpXmlRpc\Value($entry, \PhpXmlRpc\Value::$xmlrpcStruct);
            if( $posts->count() == 1 ){
                break;
            }
        }

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, \PhpXmlRpc\Value::$xmlrpcStruct));
    }


    /**
     * Used to gather information about the post.
     *
     * @param array $params
     */
    static public function getPost($params) {

        XMLRPC::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

        $blogID = $params->getParam(0)[0]->me['i4'];
        $postID = $params->getParam(0)[3]->me['i4'];

        $post = \Database::getInstance()->prepare(
            "SELECT
                n.id,
                n.pid,
                n.alias,
                n.headline,
                n.author,
                n.date,
                n.time,
                n.published,
                n.start,
                n.stop,
                na.jumpTo,
                c.text
            FROM tl_news AS n
            JOIN tl_content AS c ON (c.pid= n.id)
            JOIN tl_news_archive AS na ON (na.id = n.pid)
            WHERE n.pid= ? AND n.id =? AND c.ptable = 'tl_news'
            "
            )->execute($blogID, $postID);

        $page = \PageModel::findById($post->jumpTo);

        $url = "";
        if( !empty($page->id) ){
            // TODO find better qay to get the right url
            $url = \Environment::get('base').\Controller::generateFrontendUrl( $page->row(), "/".$post->alias );
        }

        $res = array(
            'post_id' => new \PhpXmlRpc\Value( $postID, \PhpXmlRpc\Value::$xmlrpcString)
        ,   'blog_id' => new \PhpXmlRpc\Value( $blogID, \PhpXmlRpc\Value::$xmlrpcString)
        ,   'link' => new \PhpXmlRpc\Value( $url, \PhpXmlRpc\Value::$xmlrpcString)
        );

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, \PhpXmlRpc\Value::$xmlrpcStruct));
    }


    /**
     * Used to create a new post.
     *
     * @param array $params
     */
    static public function newPost($params) {

        XMLRPC::authenticateUser($params->getParam(0)[1]->me['string'], $params->getParam(0)[2]->me['string']);

        $blogID = $params->getParam(0)[0]->me['i4'];

        $post = $params->getParam(0)[3]->me['struct'];

        $news = new \NewsModel();
        $news->pid = $blogID;
        $news->tstamp = time();
        $news->headline = $post['post_title']->me['string'];

        $alias = \StringUtil::generateAlias($post['post_title']->me['string']);

        // Check whether the news alias exists
        $objAlias = \Database::getInstance()->prepare("SELECT id FROM tl_news WHERE alias=?")->execute($alias);

        // Add ID to alias
		if( $objAlias->numRows ){
			$alias .= '-' . time();
		}

        $news->alias = $alias;
        // TODO find a suitable userID
        $news->author = '1';
        $news->date = strtotime($post['post_date']->me['dateTime.iso8601']);
        $news->time = strtotime($post['post_date']->me['dateTime.iso8601']);
        $news->source = "default";
        // TODO determine different post_status, so far recieved: "draft"
        // TODO use post_status
        // $news->published = ?'1':'';
        // $news->start = strtotime($post['post_status']->me['dateTime.iso8601']);
        // $news->start = strtotime($post['post_date']->me['dateTime.iso8601']);

        $news->save();


        $content = new \ContentModel();
        $content->pid = $news->id;
        $content->ptable = 'tl_news';
        $content->sorting = '128';
        $content->tstamp = time();
        $content->type = 'text';
        $content->text = $post['post_content']->me['string'];

        $content->save();

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($news->id, \PhpXmlRpc\Value::$xmlrpcString));
    }
}
