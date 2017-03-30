<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha;



use InvisibleReCaptcha\Admin\Pages\SettingsAdminPage;
use InvisibleReCaptcha\MchLib\Plugin\MchBaseAdminPlugin;

final class AdminEngine extends MchBaseAdminPlugin
{
	protected function __construct()
	{
		parent::__construct();

		$this->registerAdminPage(new SettingsAdminPage(__('Settings', 'invisible-recaptcha')));
	}

	public function initializeAdminPlugin()
	{
		parent::initializeAdminPlugin();
	}


	public function enqueueAdminScriptsAndStyles()
	{
		if(!$this->getActivePage()){
			return;
		}
		
		wp_enqueue_style (self::$PLUGIN_SLUG . '-admin-style', self::$PLUGIN_URL . '/assets/admin/styles/invisible-recaptcha.css', array(), self::$PLUGIN_VERSION);
		
//		wp_localize_script(self::$PLUGIN_SLUG . '-admin-script', 'UltraCommAdmin', array(
//			'ajaxUrl' => MchWpUtils::getAjaxUrl(),
//			'ajaxRequestNonce' => AjaxHandler::getAjaxNonce(),
//			'generalErrorMessage' => __('An error has occurred while processing your request', \InvisibleReCaptcha::PLUGIN_SLUG),
//			//'generalSuccessMessage' => __('An error has occurred while processing your request', \InvisibleReCaptcha::PLUGIN_SLUG),
//
//		));

	}

	public function getMenuPosition()
	{
		return '46.1213';
	}

}