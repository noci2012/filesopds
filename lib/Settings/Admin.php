<?php
/**
 * ownCloud - FilesOpds app
 *
 * Copyright (c) 2014 Frank de Lange
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\FilesOpds\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IL10N;
use OCP\IUserSession;
use OCP\IConfig;
use OCP\Defaults;
use OCP\Settings\ISettings;
use OCA\FilesOpds\AppInfo\Application;
use OCA\FilesOpds\Config;


class Admin implements ISettings {
    /** @var OCA\Config */
    protected $myconfig;

    /** @var IL10N */
    protected $l;

    /** @var Defaults */
    protected $defaults;

    /**
     * @param IConfig $myconfig
     */
    public function __construct( IConfig $config, IL10N $l, Defaults $defaults, IUserSession $usersession) {
        $this->myconfig = new \OCA\FilesOpds\Config($config, $usersession->getUser()) ;
	$this->l = $l;
	$this->defaults = $defaults;
    }

    /**
     * @return TemplateResponse returns the instance with all parameters set, ready to be rendered
     *
     * @since 9.1
     */
    public function getForm() {
	$formats = [
		["epub" => $this->myconfig->getPreview('OC\Preview\Epub') ? 1 : 0 ],
		["fb2" => $this->myconfig->getPreview('OC\Preview\FB2') ? 1 : 0 ],
		["pdf" => $this->myconfig->getPreview('OC\Preview\PDF') ? 1 : 0],
		["opendocument" => $this->myconfig->getPreview('OC\Preview\OpenDocument') ? 1 : 0],
		["msoffice" => $this->myconfig->getPreview('OC\Preview\MSOfficeDoc') ? 1 : 0]
	];

	$feedSubtitle = $this->myconfig->getApp('feed-subtitle', $this->l->t("%s OPDS catalog", $this->defaults->getName()));
	$isbndbKey = $this->myconfig->getApp('isbndb-key', '');
	$googleKey = $this->myconfig->getApp('google-key', '');
	$previewFormats = $this->myconfig->getApp('previewFormats', $formats);
	$coverx = $this->myconfig->getApp('cover-x', '200');
	$covery = $this->myconfig->getApp('cover-y', '200');
	$thumbx = $this->myconfig->getApp('thumb-x', '36');
	$thumby = $this->myconfig->getApp('thumb-y', '36');
	$params = [
		'feedSubtitle' => $feedSubtitle,
		'isbndbKey' => $isbndbKey,
		'googleKey' => $googleKey,
		'previewFormats' => $formats,
		'cover-x' => $coverx,
		'cover-y' => $covery,
		'thumb-x' => $thumbx,
		'thumb-y' => $thumby,
	];

        return new TemplateResponse(Application::APP_ID, 'settings/admin', $params, '');
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     *
     * @since 9.1
     */
    public function getSection() {
        return Application::APP_ID;
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     *             the admin section. The forms are arranged in ascending order of the
     *             priority values. It is required to return a value between 0 and 100.
     *
     * E.g.: 70
     *
     * @since 9.1
     */
    public function getPriority() {
        return 40;
    }
}
