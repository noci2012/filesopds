<?php

/**
 * NextCloud - Files_Opds App
 *
 * original @author Frank de Lange
 * original @copyright 2014 Frank de Lange
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

namespace OCA\FilesOpds\Controller;

use OCP\IRequest;
use OCP\IDefaults;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\FilesOpds\Config;
use OCP\FilesOpds\Bookshelf;
use OCA\FilesOpds\AppInfo\Application;


class AjaxController extends Controller {
	private $userId;
	private $l;
	private $defaults;
	private $userSession;

	public function __construct($AppName, IRequest $request, $UserId, IL10N $l, IDefault $defaults, IUserSession $userSession){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->l = $l;
		$this->defaults = $defaults;
		$this->userSession = $userSession;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *	  required and no CSRF check. If you don't know what CSRF is, read
	 *	  it up in the docs or you might create a security hole. This is
	 *	  basically the only required method to add this exemption, don't
	 *	  add it to any other method if you don't exactly know what it does
	 *
	 * @AdminRequired
	 * @param int opdsCoverX
	 * @param int opdsCoverY
	 * @param int opdsThumbX
	 * @param int opdsThumbX
	 *
	 */
	public function adminParam(int $opdsCoverX=200, int $opdsCoverY=200, int $opdsThumbX=36, int $opdsThumbY=36, $opdsFeedSubtitle="", $opdsIsbndbKey="", $opdsGoogleKey="" ) {
#		return new TemplateResponse(Application::APP_ID, 'index', [] );  // templates/index.php

		#\OC_JSON::callCheck();
		#\OC_JSON::checkLoggedIn();
		if ( $opdsFeedSubtitle === '' ) {
			$opdsFeedSubtitle = $this->l->t("%s OPDS catalog", $defaults->getName());
		}
		\OCA\FilesOpds\Config::setApp('cover-x', $opdsCoverX);
		\OCA\FilesOpds\Config::setApp('cover-y', $opdsCoverY);
		\OCA\FilesOpds\Config::setApp('thumb-x', $opdsThumbX);
		\OCA\FilesOpds\Config::setApp('thumb-y', $opdsThumbX);
		\OCA\FilesOpds\Config::setApp('feed_subtitle', $opdsFeedSubtitle);
		\OCA\FilesOpds\Config::setApp('isbndb-key', $opdsIsbndbKey);
		\OCA\FilesOpds\Config::setApp('google-key', $opdsGoogleKey);
		return new JSONResponse( array( 'data' => array('message'=> $this->l->t('Settings updated successfully.'), 'status' => 'success')));
	}

	/**
	 *
	 * @NoAdminRequired
	 */
	public function adminPreview($opdsPreviewEpub, $opdsPreviewFb2, $opdsPreviewPdf, $opdsPreviewOpenDocument, $opdsMsOffice ) {
		\OCA\FilesOpds\Config::setPreview('OC\Preview\Epub',$opdsPreviewEpub);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\FB2',$opdsPreviewFb2);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\PDF',$opdsPreviewPdf);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\OpenDocument',$opdsPreviewOpenDocument);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\StarOffice',$opdsPreviewOpenDocument);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\MSOfficeDoc',$opdsPreviewMsOffice);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\MSOffice2003',$opdsPreviewMsOffice);
		\OCA\FilesOpds\Config::setPreview('OC\Preview\MSOffice2007',$opdsPreviewMsOffice);

		return new JSONResponse( array( 'data' => array('message'=> $this->l->t('Settings updated successfully.'), 'status' => 'success')));
	}

	/**
	 *
	 * @NoAdminRequired
	 */
	public function personal($opdsEnable ='false', $rootPath="/Library", $fileTypes="", $skipList="metadata.opf,cover.jpg", $feedTitle="") {
		#\OC_JSON::callCheck();
		#\OC_JSON::checkLoggedIn();

		if ($feedTitle === '') {
			$this->l->t("%s's Library", \OC\User::getDisplayName());
		}

		if (!strlen($rootPath) ||
					\OC\Files\Filesystem::isValidPath($rootPath) === false || 
					\OC\Files\Filesystem::file_exists($rootPath) === false ) {
			return new JSONResult( array( 'data' => array('message'=> $this->l->t('Directory does not exist!'), 'status' => 'failure')));
		} else {
			\OCA\FilesOpds\Config::set('root_path', $rootPath);
			\OCA\FilesOpds\Config::set('enable', $opdsEnable);
			\OCA\FilesOpds\Config::set('file_types', $fileTypes);
			\OCA\FilesOpds\Config::set('skip_list', $skipList);
			\OCA\FilesOpds\Config::set('feed_title', $feedTitle);
			\OCA\FilesOpds\Config::set('id', Util::genUuid());

			return new JSONResult( array( 'data' => array('message'=> $l->t('Settings updated successfully.'), 'status' => 'success')));
		}

	}

	/**
	 * @NoAdminRequired
	 */
	public function bookshelfClear() {
		// \OC_JSON::checkLoggedIn();
		// \OC_JSON::callCheck();

		//Bookshelf::clear();
		return new JSONResult( array( 'data' => array( "message" => $this->l->t("Bookshelf cleared"), 'status' => 'success')));
	}

	/**
	 * @NoAdminRequired
	 */
	public function scheduleRescan() {
		// \OC_JSON::checkLoggedIn();
		// \OC_JSON::callCheck();

		//Meta::rescan();
		return new JSONResult( array( 'data' => array( "message" => $l->t("Rescan scheduled"), 'status' => 'success'))); }
}
