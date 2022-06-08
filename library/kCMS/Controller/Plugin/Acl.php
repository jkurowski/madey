<?php
class kCMS_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$acl = Zend_Registry::get('acl');
		$auth = Zend_Auth::getInstance();
		$role = $auth->getIdentity()->role;
		
		if($role == '') {$roleName = 'guest';} else {$roleName = $role;}
		
		$module = $request->getModuleName();
		$controller = $request->getControllerName();
		
		if(!$acl->isAllowed($roleName, $module)){  
			$request->setModuleName('logowanie');
			$request->setControllerName('index');
			$request->setActionName('index');
		}
	}
}
