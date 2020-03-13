<?php
/**
 * @copyright Copyright (c) 2014 Frank de Lange
 *
 * @author Copyright (c) 2014 Frank de Lange
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\FilesOpds\Settings;

use OCP\Util;
use OCP\IL10N;
use OCP\IConfig;
use OCP\IUserSession;
use OCP\Settings\ISettings;
use OCP\AppFramework\Http\TemplateResponse;
use OCA\FilesOpds\AppInfo\Application;
use OCA\FilesOpds\Config;
//use OCA\FilesOpds\Bookshelf;

class Personal implements ISettings {
	/** @var OCA\Config */
	protected $myconfig;

	/** @var IL10N */
	protected $l;

	/** @var IUserSession */
	protected $userSession;

	#/** @var Bookshelf */
	protected $bookshelf;

	protected $user;

	public function __construct( IConfig $config, IL10N $l, IUserSession $userSession) {
		$this->userSession = $userSession;
		$this->user = $this->userSession->getUser();
		$this->myconfig = new \OCA\FilesOpds\Config($config, $this->user);
		$this->l = $l;
		//$this->bookshelf = new \OCA\FilesOpds\Bookshelf($this->myconfig);
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm() {
		$opdsEnable = $this->myconfig->get('enable', false);
		$opdsChecked = $opdsEnable === 'true' ? 'opdsEnable-checked': '';
		$opdsValue = $opdsEnable === 'true' ? 1: 0;
		$rootPath = $this->myconfig->get('root_path', '/Library');
		$fileTypes = $this->myconfig->get('file_types', '');
		$skipList = $this->myconfig->get('skip_list', 'metadata.opf,cover.jpg');
		$feedTitle = $this->myconfig->get('feed_title',  $this->l->t("%s's Library", $this->userSession->getUser()->getDisplayName()));
		//$bookshelfCount = $this->bookshelf->count();
		$bookshelfCount = 0;
		$feedUrl = Util::linkToAbsolute('', 'index.php') . '/apps/'. Application::APP_ID. '/';
		$parameters = [
			'opdsEnable-checked' => $opdsChecked,
			'opdsEnable-value' => $opdsValue,
			'rootPath' => $rootPath,
			'fileTypes' => $fileTypes,
			'skipList' => $skipList,
			'feedTitle' => $feedTitle,
			'bookshelf-count' => $bookshelfCount,
			'feedUrl' => $feedUrl,
		];

		return new TemplateResponse(Application::APP_ID, 'settings/personal', $parameters, '');
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection() {
		return Application::APP_ID;
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority() {
		return 40;
	}
}
