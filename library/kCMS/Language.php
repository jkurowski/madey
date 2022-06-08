<?php

class kCMS_Language extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {

		$tagu = explode("/", $request->getParam('language'));
        $lang = $tagu[0];
		$locale = new Zend_Locale($lang);
        Zend_Registry::set('Zend_Locale', $locale);
		$db = Zend_Registry::get('db');

        $requestParams = $this->getRequest()->getParams();
        $language = (isset($requestParams['language'])) ? $requestParams['language'] : false;
		
		$jezyk = $db->fetchRow($db->select()->from('tlumaczenie')->where('kod =?', $lang));
		if(!$jezyk) {
			$request->setModuleName('default');
			$request->setControllerName('error');
			$request->setActionName('error');
		}

		if($lang) {
			$query = $db->select()->from('tlumaczenie_slownik', array('keyword', 'word'))->where('lang =?', $lang);  
			$translate = $db->fetchPairs($query);
			$tr = new Zend_Translate('array', $translate);

			if ($language == false) {
				$language = ($tr->isAvailable($locale->getLanguage())) ? $locale->getLanguage() : 'pl';
			}
	 
	 		$language = explode("/", $language);
			$language = $language[0];
	 
			$locale->setLocale($language);
			$tr->setLocale($locale);
			Zend_Form::setDefaultTranslator($tr);
			Zend_Registry::set('Zend_Translate', $tr);
		}
    }
}