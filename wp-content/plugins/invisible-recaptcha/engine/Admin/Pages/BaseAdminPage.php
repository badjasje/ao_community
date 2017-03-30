<?php
namespace InvisibleReCaptcha\Admin\Pages;

use InvisibleReCaptcha\MchLib\Plugin\MchBaseAdminPage;

abstract class BaseAdminPage extends MchBaseAdminPage
{

	public function __construct($pageMenuTitle, $renderModulesInSubTabs = true)
	{
		parent::__construct($pageMenuTitle, \InvisibleReCaptcha::PLUGIN_SLUG, $renderModulesInSubTabs);
		$this->setPageLayoutColumns(1);
	}

	public function renderPageContent()
	{
		parent::renderPageContent();
	}

	public function getAdminUrl($appendAddNewQueryString = false)
	{
		if(!$appendAddNewQueryString) {
			return parent::getAdminUrl();
		}

		return esc_url(add_query_arg(array('add-new' => 1), parent::getAdminUrl()));
	}

}