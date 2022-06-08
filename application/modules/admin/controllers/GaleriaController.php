<?php

class Admin_GaleriaController extends kCMS_Admin
{
		public function preDispatch() {
			$this->view->controlname = "Galeria";
			
			$user = Zend_Auth::getInstance()->getIdentity();
			if($user->role == 'user') { return $this->_redirect('/admin/inwestycje/'); }
			
		}
################################################ ZDJĘCIA PRODUKTÓW ################################################

// Pokaz wszystkie galerie
		public function indexAction() {
			$db = Zend_Registry::get('db');
			$this->view->katalog = $db->fetchAll($db->select()->from('galeria'));
		}

// Pokaz wszystkie zdjecia wybranej galerii
		public function pokazAction() {
			$db = Zend_Registry::get('db');
			$this->view->kat = $id = (int)$this->getRequest()->getParam('id');
			$this->view->katalog = $db->fetchRow($db->select()->from('galeria')->where('id =?', $id));
			$this->view->zdjecia = $db->fetchAll($db->select()->from('galeria_zdjecia')->order('sort ASC')->where('id_gal =?', $id));
		}

// Dodaj galerie
		public function nowaAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowa galeria";
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/galeria/">Wróć do listy galerii</a></div>';
			
			$form = new Form_GaleriaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$status = $this->_request->getPost('status');
					$datadodania = date("dmYHs");
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'_'.$datadodania.'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$data = array('nazwa' => $nazwa);

						$db->insert('galeria', $data);
						$lastId = $db->lastInsertId();
					
						if($obrazek) {
							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/galeria/katalog/'.$plik);
							$upload = FILES_PATH.'/galeria/katalog/'.$plik;
							chmod($upload, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							$thumb = PhpThumbFactory::create($upload)->adaptiveResizeQuadrant(288, 215, 'B')->save($upload);
							$dataImg = array('plik' => $plik);
							$db->update('galeria', $dataImg, 'id = '.$lastId);
						}
						
						$this->_redirect('/admin/galeria/');


				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Edytuj galerie
		public function edytujAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Edytuj galerię";
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/galeria/">Wróć do listy galerii</a></div>';
			
			$form = new Form_GaleriaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$katalog = $db->fetchRow($db->select()->from('galeria')->where('id = ?',$id));

			// Załadowanie do forma
			$form->nazwa->setvalue($katalog->nazwa);
			//$form->status->setvalue($katalog->status);
			//$form->obrazek->setvalue($katalog->plik);


				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$status = $this->_request->getPost('status');
					$datadodania = date("dmYHs");
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'_'.$datadodania.'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
						$data = array(
							'nazwa' => $nazwa,
							//'status' => $status,
						);
						$where['id = ?'] = $id;
						$db->update('galeria', $data, $where);
					
						if($obrazek) {
							unlink(FILES_PATH."/galeria/katalog/".$katalog->plik);
							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/galeria/katalog/'.$plik);
							$upload = FILES_PATH.'/galeria/katalog/'.$plik;
							chmod($upload, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							$thumb = PhpThumbFactory::create($upload)->adaptiveResizeQuadrant(288, 215, 'B')->save($upload);
							$dataImg = array('plik' => $plik);
							$db->update('galeria', $dataImg, 'id = '.$id);
						}
						
						$this->_redirect('/admin/galeria/');
						
				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Usun galerie
		public function usunKatalogAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$where = $db->quoteInto('id = ?', $id);
			$count = $db->fetchAll($db->select()->from('galeria_zdjecia')->where('id_gal = ?',$id));
			foreach($count as $element) {
                unlink(FILES_PATH."/galeria/".$element->plik);
                unlink(FILES_PATH."/galeria/thumbs/".$element->plik);
				$where2 = $db->quoteInto('id = ?', $element->id);
				$db->delete('galeria_zdjecia', $where2);
			}
			
			$katalog = $db->fetchRow($db->select()->from('galeria')->where('id = ?',$id));
			unlink(FILES_PATH."/galeria/katalog/".$katalog->plik);
			
			$db->delete('galeria', $where);
			$this->_redirect('/admin/galeria/');
		}

// Ustaw kolejność
		public function ustawAction() {
			$db = Zend_Registry::get('db');
			$tabela = $this->_request->getParam('co');
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {
				$data = array('sort' => $listingCounter);
				$db->update($tabela, $data, 'id = '.$recordIDValue);
				$listingCounter = $listingCounter + 1;
				}
		}
	
################################################ PRODUKTY/ZDJĘCIA ################################################
// Upload obrazka
		public function uploadAction() {
			$this->_helper->layout()->disableLayout(); 
			$this->_helper->viewRenderer->setNoRender();
			$id = (int)$this->getRequest()->getParam('id');
			$db = Zend_Registry::get('db');

			$obrazek = $_FILES['qqfile']['name'];
			$zmienobrazek = zmienobrazek($obrazek);
			$date = date('His');
			
			if (move_uploaded_file($_FILES['qqfile']['tmp_name'], FILES_PATH.'/galeria/big/'.$date.'_'.$zmienobrazek)) {
				$upfile = FILES_PATH.'/galeria/big/'.$date.'_'.$zmienobrazek;
				$thumbs = FILES_PATH.'/galeria/thumbs/'.$date.'_'.$zmienobrazek;
				chmod($upfile, 0755);

				$data = array('plik' => $date.'_'.$zmienobrazek, 'id_gal' => $id, 'nazwa' => $obrazek);

				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				PhpThumbFactory::create($upfile)->resize(920, 920)->save($upfile);

				$options = array('jpegQuality' => 80);
				PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant(480, 480)->save($thumbs);
				$db->insert('galeria_zdjecia', $data);

				$response = array("success" => true);
				header("Content-Type: text/plain");
				echo Zend_Json::encode($response);
			}
		}
// Usun zdjecie
		public function usunObrazekAction() {
			$db = Zend_Registry::get('db');

			// Odczytanie id obrazka
			$id = (int)$this->getRequest()->getParam('id');
			$pic = $db->fetchRow($db->select()->from('galeria_zdjecia')->where('id = ?',$id));
			
			unlink(FILES_PATH."/galeria/".$pic->plik);
			unlink(FILES_PATH."/galeria/thumbs/".$pic->plik);

			$where = $db->quoteInto('id = ?', $id);
			$db->delete('galeria_zdjecia', $where);
			$this->_redirect('/admin/galeria/pokaz/id/'.$pic->id_gal.'/');
		}
// Usun kilka zdjęć
		public function kilkaAction() {
			$db = Zend_Registry::get('db');
			$checkbox = $_POST[checkbox];
			for($i=0;$i<count($_POST[checkbox]);$i++){
				$id = $checkbox[$i];
				$pic = $db->fetchRow($db->select()->from('galeria_zdjecia')->where('id = ?',$id));

				unlink(FILES_PATH."/galeria/".$pic->plik);
				unlink(FILES_PATH."/galeria/thumbs/".$pic->plik);

				$where = $db->quoteInto('id = ?', $id);
				$db->delete('galeria_zdjecia', $where);
			}
			$this->_redirect('/admin/galeria/pokaz/id/'.$pic->id_gal.'/');
	}
}