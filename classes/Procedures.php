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
            ,   "signature" => array(array("string", "array"))
            ,   "docstring" => "Used to perform a connection check"
            )
        ,   "cto.getUsersBlogs" => array(
                "function" => "Procedures::getUsersBlogs"
            ,   "signature" => array(array("string", "string", "string"))
            ,   "docstring" => "Queries which blogs can be reached over this integration."
            )
        ,   "cto.getPosts" => array(
                "function" => "Procedures::getPosts"
            ,   "signature" => array(array("string", "array"))
            ,   "docstring" => "Used to gather information about several posts."
            )
        ,   "cto.getPost" => array(
                "function" => "Procedures::getPost"
            ,   "signature" => array(array("string", "array"))
            ,   "docstring" => "Used to gather information about the post."
            )
        ,   "cto.newPost" => array(
                "function" => "Procedures::newPost"
            ,   "signature" => array(array("string", "array"))
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

        \System::loadLanguageFile('tl_settings');

        $result = array(
            'software_name' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value('Softwarename', "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value('Contao', "string")
            ), "struct")
        ,   'software_version' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value('Softwareversion', "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(VERSION.'.'.BUILD, "string")
            ), "struct")
        ,   'blog_url' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value('WordPress-Adresse (URL)', "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(\Environment::get('base'), "string")
            ), "struct")
        ,   'home_url' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value('Website-Adresse (URL)', "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(\Environment::get('base'), "string")
            ), "struct")
        ,   'admin_url' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value('Die URL zum Adminbereich', "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(\Environment::get('base')."contao/", "string")
            ), "struct")
        ,   'blog_title' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value($GLOBALS['TL_LANG']['tl_settings']['websiteTitle'][0], "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(\Config::get('websiteTitle'), "string")
            ), "struct")
        ,   'date_format' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value($GLOBALS['TL_LANG']['tl_settings']['dateFormat'][0], "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(\Config::get('dateFormat'), "string")
            ), "struct")
        ,   'time_format' => new \PhpXmlRpc\Value(array(
                    'desc' => new \PhpXmlRpc\Value($GLOBALS['TL_LANG']['tl_settings']['timeFormat'][0], "string")
                ,   'readonly' => new \PhpXmlRpc\Value(true, "boolean")
                ,   'value' => new \PhpXmlRpc\Value(\Config::get('timeFormat'), "string")
            ), "struct")
        );

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($result, "struct"));
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
                'isAdmin' => new \PhpXmlRpc\Value(true, "boolean")
            ,   'url' => new \PhpXmlRpc\Value(\Environment::get('base'), "string")
            ,   'blogid' => new \PhpXmlRpc\Value($archives->id, "string")
            ,   'blogName' => new \PhpXmlRpc\Value($archives->title, "string")
            ,   'xmlrpc' => new \PhpXmlRpc\Value(\Environment::get('base').\Environment::get('request'), "string")
            );

            $res[] = new \PhpXmlRpc\Value($entry, "struct");
        }

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, "array"));
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

        $blogs = \Database::getInstance()->prepare(
            "SELECT id FROM tl_news_archive
            "
            )->execute();

        $blogIDs = $blogs->fetchAllAssoc();

        $blogFound = false;
        foreach( $blogIDs as $key => $value ){
            if( $value['id'] == $blogID ){
                $blogFound = true;
                break;
            }
        }
        if( !$blogFound ){
            $blogID = $blogIDs[0]['id'];
        }

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
                n.featured,
                n.tstamp,
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
                    'post_id' => new \PhpXmlRpc\Value( $posts->id, "string")
                ,   'post_title' => new \PhpXmlRpc\Value( $posts->headline, "string")
                ,   'post_date' => new \PhpXmlRpc\Value( date("Ymd\Th:m:s",$posts->date), "dateTime.iso8601")
                ,   'post_date_gmt' => new \PhpXmlRpc\Value( date("Ymd\Th:m:s",$posts->date), "dateTime.iso8601") //////////////
                ,   'post_modified' => new \PhpXmlRpc\Value( date("Ymd\Th:m:s",$posts->tstamp), "dateTime.iso8601")
                ,   'post_modified_gmt' => new \PhpXmlRpc\Value( date("Ymd\Th:m:s",$posts->tstamp), "dateTime.iso8601") //////////////
                ,   'post_status' => new \PhpXmlRpc\Value( $posts->published?"published":'draft', "string")
                ,   'post_type' => new \PhpXmlRpc\Value( "post", "string")
                ,   'post_name' => new \PhpXmlRpc\Value( $posts->headline, "string")
                ,   'post_author' => new \PhpXmlRpc\Value( "", "string") ///////////////
                ,   'post_password' => new \PhpXmlRpc\Value( "", "string") ///////////////
                ,   'post_excerpt' => new \PhpXmlRpc\Value( "", "string") ///////////////
                ,   'post_content' => new \PhpXmlRpc\Value( htmlentities($posts->text), "string")
                ,   'post_parent' => new \PhpXmlRpc\Value( $blogID, "string")
                ,   'post_mime_type' => new \PhpXmlRpc\Value( "", "string")
                ,   'link' => new \PhpXmlRpc\Value( $url, "string")
                ,   'guid' => new \PhpXmlRpc\Value( $url, "string") //////////////////
                ,   'menu_order' => new \PhpXmlRpc\Value( "0", "int")
                ,   'comment_status' => new \PhpXmlRpc\Value( "open", "string") //////////////////
                ,   'ping_status' => new \PhpXmlRpc\Value( "open", "string") //////////////////
                ,   'sticky' => new \PhpXmlRpc\Value( $posts->featured, "boolean")
                ,   'post_thumbnail' => new \PhpXmlRpc\Value( "", "string") //////////////////
                ,   'post_format' => new \PhpXmlRpc\Value( "standard", "string")
                ,   'terms' => new \PhpXmlRpc\Value( array(), "array")
                ,   'custom_fields' => new \PhpXmlRpc\Value( array(), "array")
                //,   'blog_id' => new \PhpXmlRpc\Value( $blogID, "string")
            );
            $res[] = new \PhpXmlRpc\Value($entry, "struct");
            if( $posts->count() == 1 ){
                break;
            }
        }

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, "array"));
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
            'post_id' => new \PhpXmlRpc\Value( $postID, "string")
        ,   'blog_id' => new \PhpXmlRpc\Value( $blogID, "string")
        ,   'link' => new \PhpXmlRpc\Value( $url, "string")
        );

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($res, "struct"));
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

        return new \PhpXmlRpc\Response(new \PhpXmlRpc\Value($news->id, "string"));
    }
}
