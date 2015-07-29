<?php

/**
 * ownCloud - Updater plugin
 *
 * @author Victor Dubiniuk
 * @copyright 2015 Victor Dubiniuk victor.dubiniuk@gmail.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */


namespace OCA\Updater;

class Channel {
	const CHANNEL_DAILY = 'daily';
	const CHANNEL_BETA = 'beta';
	const CHANNEL_STABLE = 'stable';
	const CHANNEL_PRODUCTION ='production';
	const CHANNEL_NONE ='none';
	
	/**
	 * All available values
	 * @return array
	 */
	public static function getChannels(){
		$l10n = \OC::$server->getL10N('updater');
		return [
			self::CHANNEL_PRODUCTION => $l10n->t('Production'),
			self::CHANNEL_STABLE => $l10n->t('Stable'),
			self::CHANNEL_BETA => $l10n->t('Beta'),
			self::CHANNEL_DAILY => $l10n->t('Daily'),
		];
	}
	
	/**
	 * Get current value
	 * @return string
	 */
	public static function getCurrentChannel(){
		return \OCP\Util::getChannel();
	}

	public static function setCurrentChannel($newChannel){
		$cleanValue = preg_replace('/[^A-Za-z0-9]/', '', $newChannel);
		\OCP\Util::setChannel($cleanValue);
		return $cleanValue;
	}
	
	public static function getLastCheckedAt(){
		return \OC::$server->getConfig()->getAppValue('core', 'lastupdatedat');
	}
	
	public static function flushCache(){
		\OC::$server->getConfig()->setAppValue('core', 'lastupdatedat', 0);
	}
	
	public static function getFeed($helper = null, $config = null){
		$helper = is_null($helper) ? \OC::$server->getHTTPHelper() : $helper;
		$config = is_null($config) ? \OC::$server->getConfig() : $config;
		$updater = new \OC\Updater($helper, $config);
		
		$data = $updater->check('https://updates.owncloud.com/server/');
		if (!is_array($data)){
			$data = [];
		}
		return $data;
	}
}
