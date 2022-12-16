<?php

class Default_InwestorController extends kCMS_Site
{
    private $page_id;
    private $db;

    public function preDispatch() {
        $this->page_id = 3;

        $this->db = Zend_Registry::get('db');
        $this->db->setFetchMode(Zend_Db::FETCH_OBJ);
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('page');

        $pageQuery = $this->db->select()
            ->from('strony')
            ->where('id = ?', $this->page_id);
        $page = $this->db->fetchRow($pageQuery);

        if(!$page) {
            $front = Zend_Controller_Front::getInstance();
            $request = $front->getRequest();

            $request->setModuleName('default');
            $request->setControllerName('error');
            $request->setActionName('error');
            $this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');

            $arrayError = array(
                'nofollow' => 1,
                'strona_nazwa' => 'Błąd 404',
                'seo_tytul' => 'Strona nie została znaleziona - błąd 404'
            );
            $this->view->assign($arrayError);

        } else {
            $inwest = $this->db->select()
                ->from('inwestycje', array('nazwa', 'inwestycja_plik', 'tag', 'status', 'id', 'lat', 'lng', 'addresspicker_map', 'flip'))
                ->where('status = ?', 2)
                ->orWhere('gotowe = ?', 1)
                ->order('sort ASC');

            $array = array(
                'pageclass' => ' about-page',
                'strona_nazwa' => $page->nazwa,
                'strona_h1' => $page->nazwa,
                'strona_id' => $this->page_id,
                'strona_tytul' => ' - '.$page->nazwa,
                'seo_tytul' => $page->meta_tytul,
                'seo_opis' => $page->meta_opis,
                'seo_slowa' => $page->meta_slowa,
                'strona' => $page,
                'inwestycje' => $this->db->fetchAll($inwest)
            );
            $this->view->assign($array);
        }
    }
}