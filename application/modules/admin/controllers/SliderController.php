<?php

class Admin_SliderController extends kCMS_Admin
{
		public function preDispatch() {
			$this->view->controlname = "Slider";
			$this->sliderszerokosc = 1920;
			$this->sliderwysokosc = 780;
		}
		
// Pokaz wszystkie panele
		public function indexAction() {
			$db = Zend_Registry::get('db');
			$this->view->lista = $db->fetchAll($db->select()->from('slider')->order('sort ASC'));
		}
// Dodaj nowy panel
		function nowyAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Dodaj panel";
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/slider/">Wróć do listy paneli</a></div>';
			$this->view->info = '<div class="info">Obrazek o wymiarach: szerokość <b>'.$this->sliderszerokosc.'</b>px / wysokość <b>'.$this->sliderwysokosc.'</b>px</div>';

			$form = new Form_SliderForm();
			$this->view->form = $form;

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów

					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$obrazek = $_FILES['obrazek']['name'];
					$uniqid = uniqid();
					$plik = zmiana($formData['tytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
					
					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$db->insert('slider', $formData);
						$lastId = $db->lastInsertId();
					
						if ($obrazek) {
							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/slider/'.$plik);
							$pc = FILES_PATH.'/slider/'.$plik;
							$desktop = FILES_PATH.'/slider/desktop/'.$plik;
							$tablet = FILES_PATH.'/slider/tablet/'.$plik;
							$mobile = FILES_PATH.'/slider/mobile/'.$plik;
							$thumbs = FILES_PATH.'/slider/thumbs/'.$plik;
							chmod($pc, 0755);
							chmod($desktop, 0755);
							chmod($tablet, 0755);
							chmod($mobile, 0755);
							chmod($thumbs, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							
							$options = array('jpegQuality' => 90);
							$options2 = array('jpegQuality' => 90);
							$thumb = PhpThumbFactory::create($pc, $options)->resize(159, 159)->save($thumbs);
							$thumb = PhpThumbFactory::create($pc, $options2)->adaptiveResizeQuadrant($this->sliderszerokosc, $this->sliderwysokosc)->save($desktop);
							$thumb = PhpThumbFactory::create($pc, $options)->adaptiveResizeQuadrant(1024, 450)->save($tablet);
							$thumb = PhpThumbFactory::create($pc, $options)->adaptiveResizeQuadrant(580, 500)->save($mobile);
							
							$dataImg = array('plik' => $plik);
							$db->update('slider', $dataImg, 'id = ' . $lastId);
						}

					$this->_redirect('/admin/slider/');
				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
// Edytuj panel
		function edytujAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);

			// Odczytanie id
			$id = (int)$this->_request->getParam('id');
			$slider = $db->fetchRow($db->select()->from('slider')->where('id = ?', $id));
			
			$this->view->pagename = " - Edytuj panel: ".$slider->tytul;
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/slider/">Wróć do listy paneli</a></div>';
			$this->view->info = '<div class="info">Obrazek o wymiarach: szerokość <b>'.$this->sliderszerokosc.'</b>px / wysokość <b>'.$this->sliderwysokosc.'</b>px</div>';
			
			$form = new Form_SliderForm();
			$this->view->form = $form;

			// Załadowanie do forma
			$array = json_decode(json_encode($slider), true);
			$form->populate($array);

			if ($this->_request->isPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = zmiana($formData['tytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
				
				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$db->update('slider', $formData, 'id = '.$id);

					if ($obrazek) {
							unlink(FILES_PATH."/slider/".$slider->plik);
							unlink(FILES_PATH."/slider/thumbs/".$slider->plik);
							unlink(FILES_PATH."/slider/mobile/".$slider->plik);
							unlink(FILES_PATH."/slider/tablet/".$slider->plik);
							
							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/slider/'.$plik);
							$pc = FILES_PATH.'/slider/'.$plik;
							$desktop = FILES_PATH.'/slider/desktop/'.$plik;
							$tablet = FILES_PATH.'/slider/tablet/'.$plik;
							$mobile = FILES_PATH.'/slider/mobile/'.$plik;
							$thumbs = FILES_PATH.'/slider/thumbs/'.$plik;
							chmod($pc, 0755);
							chmod($desktop, 0755);
							chmod($tablet, 0755);
							chmod($mobile, 0755);
							chmod($thumbs, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							
							$options = array('jpegQuality' => 90);
							$options2 = array('jpegQuality' => 90);
							$thumb = PhpThumbFactory::create($pc, $options)->resize(159, 159)->save($thumbs);
							$thumb = PhpThumbFactory::create($pc, $options2)->adaptiveResizeQuadrant($this->sliderszerokosc, $this->sliderwysokosc)->save($desktop);
							$thumb = PhpThumbFactory::create($pc, $options)->adaptiveResizeQuadrant(1024, 450)->save($tablet);
							$thumb = PhpThumbFactory::create($pc, $options)->adaptiveResizeQuadrant(580, 500)->save($mobile);
							
							$dataImg = array('plik' => $plik);
							$db->update('slider', $dataImg, 'id = ' . $id);
						}

						$this->_redirect('/admin/slider/');
				} else {

					//Wyswietl bledy    
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Usuń panel
		function usunAction() {
			$db = Zend_Registry::get('db');
			// Odczytanie id obrazka
			$id = (int)$this->_request->getParam('id');
			$slider = $db->fetchRow($db->select()->from('slider')->where('id = ?', $id));
							
			unlink(FILES_PATH."/slider/".$slider->plik);
			unlink(FILES_PATH."/slider/thumbs/".$slider->plik);
			unlink(FILES_PATH."/slider/mobile/".$slider->plik);
			unlink(FILES_PATH."/slider/tablet/".$slider->plik);

			$where = $db->quoteInto('id = ?', $id);
			$db->delete('slider', $where);

			$this->_redirect('/admin/slider/');
		}
// Ustaw kolejność
		public function ustawAction() {
			$db = Zend_Registry::get('db');
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {
				$data = array('sort' => $listingCounter);
				$db->update('slider', $data, 'id = '.$recordIDValue);
				$listingCounter = $listingCounter + 1;
				}
		}
// Usun kilka paneli
		public function kilkaAction() {
			$db = Zend_Registry::get('db');
			$checkbox = $_POST[checkbox];
			for($i=0;$i<count($_POST[checkbox]);$i++){
				$id = $checkbox[$i];
				$where = $db->quoteInto('id = ?', $id);
				$slider = $db->fetchRow($db->select()->from('slider')->where('id = ?', $id));
				
				unlink(FILES_PATH."/slider/".$slider->plik);
				unlink(FILES_PATH."/slider/thumbs/".$slider->plik);
							
				$db->delete('slider', $where);
			}
			$this->_redirect('/admin/slider/');
	}
	
// Ustawienia slidera
	public function ustawieniaAction() {
			$db = Zend_Registry::get('db');

			$form = new Form_SliderUstawieniaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);
			
			$form->getElement('speed')->getDecorator('label')->setOption('escape', false);
			$form->getElement('timeout')->getDecorator('label')->setOption('escape', false);
			
			$ustawienia = $db->fetchRow($db->select()->from('ustawienia'));
			
			$form->auto->setvalue($ustawienia->slider_auto);
			$form->pause->setvalue($ustawienia->slider_pause);
			$form->nav->setvalue($ustawienia->slider_nav);
			$form->pager->setvalue($ustawienia->slider_pager);
			$form->speed->setvalue($ustawienia->slider_speed);
			$form->timeout->setvalue($ustawienia->slider_timeout);
			//$form->efekt->setvalue($ustawienia->slider_efekt);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów $auto, $pause, $nav, $pager, $speed, $timeout
				$auto = $this->_request->getPost('auto');
				$pause = $this->_request->getPost('pause');
				$nav = $this->_request->getPost('nav');
				$pager = $this->_request->getPost('pager');
				$speed = $this->_request->getPost('speed');
				$timeout = $this->_request->getPost('timeout');
				//$efekt = $this->_request->getPost('efekt');
				$formData = $this->_request->getPost();

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$data = array(
						'slider_auto' => $auto,
						'slider_pause' => $pause,
						'slider_nav' => $nav,
						'slider_pager' => $pager,
						'slider_speed' => $speed,
						'slider_timeout' => $timeout,
						//'slider_efekt' => '1',
					);

				}

				$db->update('ustawienia', $data);
				$this->_redirect('/admin/slider/ustawienia/');
			} else {
					
				//Wyswietl bledy	
				$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
			}
		}
}