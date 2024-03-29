<?php
// $username='';$psw='';
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'CSChedule',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),
	
	'defaultController'=>'User/Login',

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		// 'gii'=>array(
			// 'class'=>'system.gii.GiiModule',
			// 'password'=>'1',
			//If removed, Gii defaults to localhost only. Edit carefully to taste.
			// 'ipFilters'=>array('127.0.0.1','::1'),
		// ),
		
		// 'cal' => array(
            // 'debug' => true // For first run only!
        // ),
		
		 'wdcalendar'    => array('embed'=>true),
	),

	// application components
	'components'=>array( 
		// 'cache'=>array(
              // 'class'=>'CMemCache',
              // 'servers'=>array(
                  // array(
                      // 'host'=>'127.0.0.1',
                      // 'port'=>11211,
                      // 'weight'=>60,
                  // ),
              // ),
          // ),
		 
		 'session'=>array(
			 'timeout'=>30,
			 'cookieparams'=>array(
				 'lifetime'=>10
			 )
		 ),
		
		'cache'=>array(
			//cache files are in the runtime folder
			'class'=>'system.caching.CFileCache',    
			// depth of the directory about cache files
			'directoryLevel'=>'2',   
		),
	
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			 'loginUrl'=>array('User/Login')
		),
		
		'timepicker'=>array(
			'class'=>'application.extensions.timepicker.timepicker',
		),
		
		// 'phpMailer'=>array(
			// 'class'=>'application.extensions.phpmailer.CPhpMailer',
			// 'host' => 'smtp.qq.com',
			// 'port' => 25,
			// 'from' => $username,
			// 'fromName' => 'CSchedule',
			// 'username' => $username,
			// 'password' => $psw,
		// ),
		
		'RestClient' => array(
            'class' => 'application.extensions.RestClient',
        ),
		
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		// 'db'=>array(
			// 'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		// ),
		// uncomment the following to use a MySQL database
		
		// 'db'=>array(
			// 'connectionString' => 'mysql:host=localhost;dbname=schedule',
			// 'emulatePrepare' => true,
			// 'username' => 'root',
			// 'password' => '',
			// 'charset' => 'utf8',
		// ),
		
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'sessionTimeoutSeconds'=>1800,
	),
);