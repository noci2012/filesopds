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
use OCP\Files\Filesystem;
use OCA\FilesOpds\Config;

/**
 * Bookshelf class for publishing as OPDS
 *
 * This implements a 'personal bookshelf', listing books
 * which have been downloaded from the OPDS feed.
 */
class Bookshelf
{
	/** @var myconfig */
	private $myconfig;

	/*
         * Util constructor.
         *
         * @param Config $myconfig
         */
        public function __construct(Config $myconfig) { 
		$this->myconfig = $myconfig;
        }

	/**
	 * @brief add book to personal bookshelf
	 *
	 * @param int $id book to add to bookshelf
	 */
	public function add($id) {
		$bookshelf = json_decode($this->myconfig->get('bookshelf', ''), true);
		if(!isset($bookshelf[$id])) {
			$bookshelf[$id]=time();
		}
		$this->myconfig->set('bookshelf', json_encode($bookshelf));
	}

	/**
	 * @brief remove book from personal bookshelf
	 *
	 * @param int $id book to remove from bookshelf
	 */
	public function remove($id) {
		$bookshelf = json_decode($this->myconfig->get('bookshelf', ''), true);
		if(isset($bookshelf[$id])) {
			unset($bookshelf[$id]);
			$this->myconfig->set('bookshelf', json_encode($bookshelf));
		}
	}

	/**
	 * @brief clear personal bookshelf
	 */
	public function clear() {
		$this->myconfig->set('bookshelf', '');
	}

	/**
	 * @brief return number of books on personal bookshelf
	 * @return int number of books
	 */
	public function count() {
		return substr_count($this->myconfig->get('bookshelf', ''), ':');
	}

	/**
	 * @brief list bookshelf contents
	 *
	 * @return array of FileInfo[], sorted by time added
	 */
	public function get() {
		$files = array();
		if($bookshelf = json_decode($this->myconfig->get('bookshelf', ''), true)) {
			arsort($bookshelf);
			while (list($id, $time) = each($bookshelf)) {
				try {
					array_push($files, \OC\Files\Filesystem::getFileInfo(\OC\Files\Filesystem::normalizePath(\OC\Files\Filesystem::getPath($id))));
				} catch(\OCP\Files\NotFoundException $e) {
					self::remove($id);
					Meta::remove($id);
				}
			}
		}
		return $files;
	}
}
