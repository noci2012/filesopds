<?php

/**
 * ownCloud - Files_Opds App
 *
 * @author Frank de Lange
 * @copyright 2014 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

namespace OCA\FilesOpds;
use OCP\IConfig;
use OCP\IUser;
use OCA\FilesOpds\AppInfo\Application;

/**
 * Config class for publishing as OPDS
 */
class Config
{
	/** @var: IConfig $config */
	protected $config;

	/** @var: IConfig $user */
	protected $user;

	/**
	 * @brief contruct this class
	 * @param IConfig reference to system config
	 */
	public function __construct (IConfig $config, IUser $user) {
		$this->config = $config;
		$this->user = $user;
	}

	/**
	 * @brief get user config value
	 *
	 * @param string $key value to retrieve
	 * @param string $default default value to use
	 * @return string retrieved value or default
	 */
	public function get($key, $default) {
		return $this->config->getUserValue($this->user->getUser(), Application::APP_ID, $key, $default);
		#return \OC::$server->getConfig()->getUserValue(\OC_User::getUser(), Application::APP_ID, $key, $default);
	}

	/**
	 * @brief set user config value
	 *
	 * @param string $key key for value to change
	 * @param string $value value to use
	 * @return bool success
	 */
	public function set($key, $value) {
		return \OC::$server->getConfig()->setUserValue($this->User->getUser(), Application::APP_ID, $key, $value);
	}

	/**
	 * @brief get app config value
	 *
	 * @param string $key value to retrieve
	 * @param string $default default value to use
	 * @return string retrieved value or default
	 */
	public function getApp($key, $default) {
		return \OC::$server->getConfig()->getAppValue(Application::APP_ID, $key, $default);
	}

	/**
	 * @brief set app config value
	 *
	 * @param string $key key for value to change
	 * @param string $value value to use
	 * @return bool success
	 */
	public function setApp($key, $value) {
		return \OC::$server->getConfig()->setAppValue(Application::APP_ID, $key, $value);
	}
	
	/**
	 * @brief get preview status
	 * 
	 * @param string format
	 * @return bool (true = enabled, false = disabled)
	 */
	public function getPreview($format) {
		$enablePreviewProviders = \OC::$server->getConfig()->getSystemValue('enabledPreviewProviders', null);
		if (!($enablePreviewProviders === null)) {
			return in_array($format, $enablePreviewProviders);
		}
		return false;
	}

	/**
	 * @brief enable/disable preview for selected format
	 *
	 * @param string format
	 * @param bool enable (true = enable, false = disable, default = false)
	 * @return bool
	 */
	public function setPreview($format, $enable = 'false') {
		$enablePreviewProviders = \OC::$server->getConfig()->getSystemValue('enabledPreviewProviders', null);
		if ($enable === 'true') {
			if ($enablePreviewProviders === null) {
				// set up default providers
				$enablePreviewProviders = array();
				array_push($enablePreviewProviders,
					'OC\Preview\Image',
					'OC\Preview\MP3',
					'OC\Preview\TXT',
					'OC\Preview\MarkDown');
			}
			if (!(in_array($format,$enablePreviewProviders))) {
				array_push($enablePreviewProviders, $format);
			}
		} else {
			if (!($enablePreviewProviders === null)) {
				$enablePreviewProviders = array_diff($enablePreviewProviders, array($format));
			}
		}

		if (!(\OC::$server->getConfig()->setSystemValue('enabledPreviewProviders', $enablePreviewProviders))) {
			Util::logWarn("Failed to enable " . $format . " preview provider (config.php readonly?)");
			return true;
		}
	}
}
