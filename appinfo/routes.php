<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\FilesOpds\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
//	'resources' => [
//		'global_storages' => ['url' => '/globalstorages'],
//		'user_storages' => ['url' => '/userstorages'],
//		'user_global_storages' => ['url' => '/userglobalstorages'],
//	],
	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET' ],
		//['name' => 'page#do_echo', 'url' => '/echo', 'verb' => 'POST' ],
		['name' => 'ajax#admin_preview', 'url' => '/ajax/adminpreview.php', 'verb' => 'POST' ],
		['name' => 'ajax#admin_param', 'url' => '/ajax/adminparam.php', 'verb' => 'POST' ],
		//['name' => 'ajax#admin_preview', 'url' => '/ajax/admin/preview', 'verb' => 'POST' ],
		//['name' => 'ajax#admin_param', 'url' => '/ajax/admin/param', 'verb' => 'POST' ],
		['name' => 'ajax#personal', 'url' => '/ajax/personal.php', 'verb' => 'POST' ],
		//['name' => 'ajax#personal', 'url' => '/ajax/personal', 'verb' => 'POST' ],
		['name' => 'ajax#clear_bookshelf', 'url' => '/ajax/clear_bookshelf.php', 'verb' => 'POST' ],
		//['name' => 'ajax#clear_bookshelf', 'url' => '/ajax/clear_bookshelf', 'verb' => 'POST' ],
		['name' => 'ajax#schedule_rescan', 'url' => '/ajax/schedule_rescan.php', 'verb' => 'POST' ],
		//['name' => 'ajax#schedule_rescan', 'url' => '/ajax/schedule_rescan', 'verb' => 'POST' ],
	],
];
