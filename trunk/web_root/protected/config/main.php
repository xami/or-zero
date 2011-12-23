<?php
function pr($data=array(), $end='', $stop=false)
{
	print_r($data);
    echo $end;
	if($stop) die;
}
function pd($data=array(), $end='', $stop=true)
{
	print_r($data);
    echo $end;
	if($stop) die;
}


// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'M天涯',
    'language'=>'zh_cn',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'pwd',
//		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>array('127.0.0.1','::1'),
//		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
                '<controller:(article)>/<id:\d+>/<C_page:\d+>'=>array('site/article', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
				'<controller:(article)>/<id:\d+>/index'=>array('site/article', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
				'<controller:(article)>/<id:\d+>/?'=>array('site/article', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                
                '<controller:(article)>-<id:\d+>-<C_page:\d+>'=>array('site/article', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                '<controller:(article)>-<id:\d+>-1'=>array('site/article', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                '<controller:(article)>-<id:\d+>'=>array('site/article', 'urlSuffix'=>'.html', 'caseSensitive'=>false),

                '<controller:(channel)>/<cid:\d+>/<Article_page:\d+>'=>array('site/item', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                '<controller:(channel)>/<cid:\d+>/1'=>array('site/item', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
				'<controller:(channel)>/<cid:\d+>/?'=>array('site/item', 'urlSuffix'=>'.html', 'caseSensitive'=>false),

                '<controller:(more)>/<id:\d+>/'=>array('site/channel', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                '<controller:(more)>/<id:\d+>'=>array('site/channel', 'urlSuffix'=>'.html', 'caseSensitive'=>false),

                '<controller:(item)>/<tid:\d+>/<Article_page:\d+>'=>array('site/item', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                '<controller:(item)>/<tid:\d+>/1'=>array('site/item', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
                '<controller:(item)>/<tid:\d+>/?'=>array('site/item', 'urlSuffix'=>'.html', 'caseSensitive'=>false),

                //List
                '<controller:list>-<A_sort:\w+>-<id:\d+>\.html'=>array('api/maps', 'caseSensitive'=>false),
                '<controller:list>-<id:\d+>\.html'=>array('api/maps', 'caseSensitive'=>false),

                //sitemap
                '<controller:(orzero)>-author.html'=>array('api/ulink', 'urlSuffix'=>'/', 'caseSensitive'=>false),
                '<controller:(link)>-<id:\d+>.html'=>array('api/alink', 'urlSuffix'=>'/', 'caseSensitive'=>false),
                '<controller:(sitemap)>/<id:\d+>.xml'=>array('api/sitemap', 'urlSuffix'=>'/', 'caseSensitive'=>false),
                '<controller:(sitemap)>'=>array('api/sitemap', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
                '<controller:(sitemaps)>'=>array('api/sitemaps', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),

                //查找
                '<controller:(search)>/*'=>array('api/searchs', 'urlSuffix'=>'/', 'caseSensitive'=>false),

                //tag
				'<controller:(tag)>/<Searchs_page:\d+>/'=>array('api/tag', 'urlSuffix'=>'/', 'caseSensitive'=>false),
				'<controller:(tag)>/'=>array('api/tag', 'urlSuffix'=>'/', 'caseSensitive'=>false),

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

                //feed:rss,atom
                '<controller:(rss|atom|RSS|ATOM)>'=>array('api/feed', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
			),
            'showScriptName'=>false,
		),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=tianya',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
            'tablePrefix' => 'tbl_',	
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
        'cache'=>array(
                'class'=>'CMemCache',
                        'servers'=>array(
                                array(
                                        'host'=>'127.0.0.1',
                                        'port'=>11211,
                                        'weight'=>100,
                                ),
                ),
        ),
        'CURL' =>array(
			'class' => 'application.extensions.Curl',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
        'mcpass'=>'Pj8VArQ85XxJKw2t',
        'domain'=>$_SERVER['SERVER_NAME'],
        'google_search_ad'=>'partner-pub-4726192443658314:4873446973',
        'key'=>'{"idwriter":"60257436","key":"866084533","chk":"e3f484000ae7ec99e2249b076fb09001"}',
	),
);