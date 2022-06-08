<?php

class Admin_InwestycjeController extends kCMS_Admin
{
		public function preDispatch() {
			$this->view->controlname = "Inwestycje";
			
			$this->planszerokosc = $this->view->planszerokosc = 1430;
			$this->planwysokosc = $this->view->planwysokosc = 810;
			
			//Aktualnosci
			$this->duzawysokosc = '660';
			$this->duzaszerokosc = '1340';
			$this->malawysokosc = '370';
			$this->malaszerokosc = '670';
			
			function sekcja($numer){
				switch ($numer) {
					case '1':
						return "Atuty inwestycji";
					case '2':
						return "Dlaczego warto";
				}
			}
			
			function typ($numer){
				switch ($numer) {
					case '1':
						return "Inwestycja osiedlowa";
					case '2':
						return "Inwestycja budynkowa";
					case '3':
						return "Inwestycja z domami";
					case '4':
						return "Inna oferta";
				}
			}
			
			function szukaj($id){
				switch ($id) {
					case '1':
						return "Piętro";
					case '2':
						return "Pokoje";
					case '3':
						return "Powierzchnia";
					case '4':
						return "Cena";
				}
			}
			
			function statusInwestycji($numer){
				switch ($numer) {
					case '1':
						return "Inwestycja aktualna";
					case '2':
						return "Inwestycja zrealizowana";
					case '3':
						return "Inwestycja planowana";
				}
			}	
			
			function statusMieszkania($numer){
				switch ($numer) {
					case '1':
						return '<span class="mieszkanie-wolne">Na sprzedaż</span>';
					case '2':
						return '<span class="mieszkanie-sprzedane">Sprzedane</span>';
					case '3':
						return '<span class="mieszkanie-rezerwacja">Rezerwacja</span>';
					case '4':
						return '<span class="mieszkanie-wynajete">Wynajęte</span>';
				}
			}
		}
		
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
	
################################################### INWESTYCJA ###################################################

// Pokaz wszystkie inwestycji
		public function indexAction() {
			$db = Zend_Registry::get('db');
			
			$user = Zend_Auth::getInstance()->getIdentity();
			if($user->role == 'user') {
				$this->view->lista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC')->where('id IN('.$user->inwestycje.')'));
			} else {
				$this->view->lista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC'));
			}
		}
		
// Pokaz wybrana inwestycje
		public function pokazAction() {
			$db = Zend_Registry::get('db');
			
			$id = (int)$this->getRequest()->getParam('id');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			
			// Inwestycja osiedlowa
			if($inwestycja->typ == 1) {
				$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $id)->order('sort ASC'));
			}
			// Inwestycja budynkowa
			if($inwestycja->typ == 2) {
				$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $id)->order('numer DESC')->order('typ ASC'));
			}
			// Inwestycja domkowa
			if($inwestycja->typ == 3) {
				$lista = $this->view->lista = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest = ?', $id)->order('numer DESC')->order('numer ASC'));
			}
		}

// Dodaj plan do inwestycji
		public function dodajPlanAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->getRequest()->getParam('id');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id));	
		}

// Dodaj obrazek nagłówka inwestycji
		public function dodajTopAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->getRequest()->getParam('id');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		}
		
// Zmiana planu inwestycji
		public function uploadAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->layout()->disableLayout(); 
			$this->_helper->viewRenderer->setNoRender(true);
			
			$id = (int)$this->getRequest()->getParam('id');
			$inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			$plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id));
			
			if($plan->plik) {
				unlink(FILES_PATH."/inwestycje/plan/".$plan->plik);
				$db->delete('inwestycje_plan', 'id_inwest = '.$id);
			}

			$obrazek = $_FILES['qqfile']['name'];
			$plik = zmiana($inwestycja->nazwa).'.'.zmiennazwe($obrazek);
			
			if (move_uploaded_file($_FILES['qqfile']['tmp_name'], FILES_PATH.'/inwestycje/plan/'.$plik)) {
				$upfile = FILES_PATH.'/inwestycje/plan/'.$plik;
				chmod($upfile, 0755);
				
				$data = array('id_inwest' => $id, 'plik' => $plik);
				$db->insert('inwestycje_plan', $data);
				
				$response = array("success" => true);
				header("Content-Type: text/plain");
				echo Zend_Json::encode($response);
			} else {

			}
		}
		
// Zmiana obrazka naglowka
		public function topAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->layout()->disableLayout(); 
			$this->_helper->viewRenderer->setNoRender(true);
			
			$id = (int)$this->getRequest()->getParam('id');
			$inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			
			if($inwestycja->top) {
				unlink(FILES_PATH."/inwestycje/top/".$plan->plik);
			}

			$obrazek = $_FILES['qqfile']['name'];
			$plik = zmiana($inwestycja->nazwa).'.'.zmiennazwe($obrazek);
			
			if (move_uploaded_file($_FILES['qqfile']['tmp_name'], FILES_PATH.'/inwestycje/top/'.$plik)) {
				$upfile = FILES_PATH.'/inwestycje/top/'.$plik;
				chmod($upfile, 0755);
				
				$data = array('top' => $plik);
				$db->update('inwestycje', $data, 'id = '.$id); 
				
				$response = array("success" => true);
				header("Content-Type: text/plain");
				echo Zend_Json::encode($response);
			} else {

			}
		}

// Dodaj nową inwestycje
		public function nowaInwestycjaAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowa inwestycja";
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/">Wróć do listy inwestycji</a></div>';
			//$this->view->info = '<div class="info">Wymiary miniaturki: <b>500px</b> szerokości / <b>500px</b> wysokości</div>';

			$this->view->tinymce = "1";
			
			$form = new Form_InwestycjaForm();
			$this->view->form = $form;

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$formData['data'] = date("d.m.Y - H:i");
				$formData['tag'] = zmiana($formData['nazwa']);

				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);

				$obrazek2 = $_FILES['obrazek2']['name'];
				$uniqid = uniqid();
				$plik2 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek2);
				
				$obrazek3 = $_FILES['obrazek3']['name'];
				$uniqid = uniqid();
				$plik3 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek3);

				
				$lokalizacja_plik = $_FILES['lokalizacja_plik']['name'];
				$uniqid = uniqid();
				$pliklokalizacja_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($lokalizacja_plik);
				
				$inwestycja_plik = $_FILES['inwestycja_plik']['name'];
				$uniqid = uniqid();
				$plikinwestycja_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($inwestycja_plik);	
				
				$dlaczego_plik = $_FILES['dlaczego_plik']['name'];
				$uniqid = uniqid();
				$plikdlaczego_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($dlaczego_plik);
				
				$logo_plik = $_FILES['logo']['name'];
				$uniqid = uniqid();
				$pliklogo_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($logo_plik);

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$db->insert('inwestycje', $formData);
					$lastId = $db->lastInsertId();

					if ($logo_plik) {

						move_uploaded_file($_FILES['logo']['tmp_name'], FILES_PATH.'/inwestycje/logo/'.$pliklogo_plik);
						$upfile = FILES_PATH.'/inwestycje/logo/'.$pliklogo_plik;
						chmod($upfile, 0755);
						$dataImg = array('logo' => $pliklogo_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$lastId);

					}
					
					if ($obrazek) {

						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/miniaturka/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/miniaturka/'.$plik;
						$end = FILES_PATH.'/inwestycje/zakonczenie/'.$plik;
						chmod($upfile, 0755);
						chmod($end, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant(950, 530, 'T')->save($upfile);
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant(540, 300, 'T')->save($end);

						$dataImg = array('miniaturka' => $plik);
						$db->update('inwestycje', $dataImg, 'id = '.$lastId);

					}

					if ($obrazek3) {

						move_uploaded_file($_FILES['obrazek3']['tmp_name'], FILES_PATH.'/inwestycje/header/'.$plik3);
						$upfile3 = FILES_PATH.'/inwestycje/header/'.$plik3;
						chmod($upfile3, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile3, $options)->adaptiveResizeQuadrant(1920, 600, 'B')->save($upfile3);

						$dataImg = array('header' => $plik3);
						$db->update('inwestycje', $dataImg, 'id = '.$lastId);

					}
					
					if ($lokalizacja_plik) {

						move_uploaded_file($_FILES['lokalizacja_plik']['tmp_name'], FILES_PATH.'/inwestycje/tekst/'.$pliklokalizacja_plik);
						$upfile4 = FILES_PATH.'/inwestycje/tekst/'.$pliklokalizacja_plik;
						chmod($upfile4, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile4, $options)->adaptiveResizeQuadrant(755, 675, 'T')->save($upfile4);

						$dataImg = array('lokalizacja_plik' => $pliklokalizacja_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$lastId);

					}
					
					if ($inwestycja_plik) {

						move_uploaded_file($_FILES['inwestycja_plik']['tmp_name'], FILES_PATH.'/inwestycje/tekst/'.$plikinwestycja_plik);
						$upfile5 = FILES_PATH.'/inwestycje/tekst/'.$plikinwestycja_plik;
						chmod($upfile5, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile5, $options)->adaptiveResizeQuadrant(960, 630, 'T')->save($upfile5);

						$dataImg = array('inwestycja_plik' => $plikinwestycja_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$lastId);

					}
					
					if ($dlaczego_plik) {

						move_uploaded_file($_FILES['dlaczego_plik']['tmp_name'], FILES_PATH.'/inwestycje/tekst/'.$plikdlaczego_plik);
						$upfile6 = FILES_PATH.'/inwestycje/tekst/'.$plikdlaczego_plik;
						chmod($upfile6, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile6, $options)->adaptiveResizeQuadrant(755, 675, 'T')->save($upfile6);

						$dataImg = array('dlaczego_plik' => $plikdlaczego_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$lastId);

					}
					
					$this->_redirect('/admin/inwestycje/');

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
		
// Edytuj inwestycje
		public function edytujInwestycjeAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/">Wróć do listy inwestycji</a></div>';

			$form = new Form_InwestycjaForm();
			$this->view->form = $form;
			
			$this->view->tinymce = "1";

			// Odczytanie id i pobranie inwestycji
			$id = (int)$this->getRequest()->getParam('id');
			$wpis = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			$this->view->pagename = " - Edytuj inwestycję: ".$wpis->nazwa;

			// Załadowanie do forma
			$array = json_decode(json_encode($wpis), true);
			$form->populate($array);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$formData['data'] = date("d.m.Y - H:i");
				$formData['tag'] = zmiana($formData['nazwa']);

				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);

				$obrazek3 = $_FILES['obrazek3']['name'];
				$uniqid = uniqid();
				$plik3 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek3);
				
				$lokalizacja_plik = $_FILES['lokalizacja_plik']['name'];
				$uniqid = uniqid();
				$pliklokalizacja_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($lokalizacja_plik);
				
				$inwestycja_plik = $_FILES['inwestycja_plik']['name'];
				$uniqid = uniqid();
				$plikinwestycja_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($inwestycja_plik);	
				
				$dlaczego_plik = $_FILES['dlaczego_plik']['name'];
				$uniqid = uniqid();
				$plikdlaczego_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($dlaczego_plik);
				
				$logo_plik = $_FILES['logo']['name'];
				$uniqid = uniqid();
				$pliklogo_plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($logo_plik);

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$db->update('inwestycje', $formData, 'id = '.$id);

					if ($logo_plik) {
						unlink(FILES_PATH."/inwestycje/logo/".$wpis->logo);
						move_uploaded_file($_FILES['logo']['tmp_name'], FILES_PATH.'/inwestycje/logo/'.$pliklogo_plik);
						$upfile = FILES_PATH.'/inwestycje/logo/'.$pliklogo_plik;
						chmod($upfile, 0755);
						$dataImg = array('logo' => $pliklogo_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$id);

					}
					
					if ($obrazek) {
						unlink(FILES_PATH."/inwestycje/miniaturka/".$wpis->miniaturka);
						unlink(FILES_PATH."/inwestycje/zakonczenie/".$wpis->miniaturka);
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/miniaturka/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/miniaturka/'.$plik;
						$end = FILES_PATH.'/inwestycje/zakonczenie/'.$plik;
						chmod($upfile, 0755);
						chmod($end, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant(950, 530, 'T')->save($upfile);
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant(540, 300, 'T')->save($end);

						$dataImg = array('miniaturka' => $plik);
						$db->update('inwestycje', $dataImg, 'id = '.$id);

					}

					if ($obrazek3) {
						unlink(FILES_PATH."/inwestycje/header/".$wpis->header);
						move_uploaded_file($_FILES['obrazek3']['tmp_name'], FILES_PATH.'/inwestycje/header/'.$plik3);
						$upfile3 = FILES_PATH.'/inwestycje/header/'.$plik3;
						chmod($upfile3, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile3, $options)->adaptiveResizeQuadrant(1920, 600, 'B')->save($upfile3);

						$dataImg = array('header' => $plik3);
						$db->update('inwestycje', $dataImg, 'id = '.$id);

					}
					
					if ($lokalizacja_plik) {
						unlink(FILES_PATH."/inwestycje/tekst/".$wpis->lokalizacja_plik);
						move_uploaded_file($_FILES['lokalizacja_plik']['tmp_name'], FILES_PATH.'/inwestycje/tekst/'.$pliklokalizacja_plik);
						$upfile4 = FILES_PATH.'/inwestycje/tekst/'.$pliklokalizacja_plik;
						chmod($upfile4, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile4, $options)->adaptiveResizeQuadrant(755, 675, 'T')->save($upfile4);

						$dataImg = array('lokalizacja_plik' => $pliklokalizacja_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$id);

					}
					
					if ($inwestycja_plik) {
						unlink(FILES_PATH."/inwestycje/tekst/".$wpis->inwestycja_plik);
						move_uploaded_file($_FILES['inwestycja_plik']['tmp_name'], FILES_PATH.'/inwestycje/tekst/'.$plikinwestycja_plik);
						$upfile5 = FILES_PATH.'/inwestycje/tekst/'.$plikinwestycja_plik;
						chmod($upfile5, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile5, $options)->adaptiveResizeQuadrant(960, 630, 'T')->save($upfile5);

						$dataImg = array('inwestycja_plik' => $plikinwestycja_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$id);

					}
					if ($dlaczego_plik) {
						unlink(FILES_PATH."/inwestycje/tekst/".$wpis->dlaczego_plik);
						move_uploaded_file($_FILES['dlaczego_plik']['tmp_name'], FILES_PATH.'/inwestycje/tekst/'.$plikdlaczego_plik);
						$upfile6 = FILES_PATH.'/inwestycje/tekst/'.$plikdlaczego_plik;
						chmod($upfile6, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile6, $options)->adaptiveResizeQuadrant(755, 675, 'T')->save($upfile6);

						$dataImg = array('dlaczego_plik' => $plikdlaczego_plik);
						$db->update('inwestycje', $dataImg, 'id = '.$id);

					}
					
					$this->_redirect('/admin/inwestycje/');

				} else {

				//Wyswietl bledy	
				$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
				$form->populate($formData);

				}
			}
		}
// Usun inwestycje
		public function usunInwestycjeAction() {
			$db = Zend_Registry::get('db');
			$id_inwestycja = (int)$this->getRequest()->getParam('id');

			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			unlink(FILES_PATH."/inwestycje/header/".$inwest->header);
			unlink(FILES_PATH."/inwestycje/zakonczenie/".$inwest->miniaturka);
			unlink(FILES_PATH."/inwestycje/miniaturka/".$inwest->miniaturka);
						
			$inwest_plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwestycja));
			unlink(FILES_PATH."/inwestycje/plan/".$inwest_plan->plik);

			if($inwest->typ == 1) {
			
				$counthouse = $db->fetchAll($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $id_inwestycja));
				foreach($counthouse as $house) {
					try { unlink(FILES_PATH."/inwestycje/budynek/".$house->plik); }
					catch (Exception $e) { echo $e->message; }
					$where1 = $db->quoteInto('id = ?', $house->id);
					$db->delete('inwestycje_budynki', $where1);
				}
				
			}
			
			$countfloor = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $id_inwestycja));
			foreach($countfloor as $pietro) {
				try { unlink(FILES_PATH."/inwestycje/pietro/".$pietro->plik); }
				catch (Exception $e) { echo $e->message; }
				$where2 = $db->quoteInto('id = ?', $pietro->id);
				$db->delete('inwestycje_pietro', $where2);
			}

			$count = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest = ?', $id_inwestycja));
			foreach($count as $powierzchnia) {
				try { unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik); }
				catch (Exception $e) { echo $e->message; }
				try { unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik); }
				catch (Exception $f) { echo $f->message; }
				try { unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf); }
				catch (Exception $f) { echo $f->message; }
				
				$where3 = $db->quoteInto('id = ?', $powierzchnia->id);
				$db->delete('inwestycje_powierzchnia', $where3);
			}

			$wherePlan = $db->quoteInto('id_inwest = ?', $id_inwestycja);
			$db->delete('inwestycje_plan', $wherePlan);
			
			$whereStrony = $db->quoteInto('id_inwest = ?', $id_inwestycja);
			$db->delete('inwestycje_strony', $whereStrony);
			
			$whereAtuty = $db->quoteInto('id_inwest = ?', $id_inwestycja);
			$db->delete('inwestycje_atut', $whereAtuty);
			
			$whereMarkery = $db->quoteInto('id_inwest = ?', $id_inwestycja);
			$db->delete('inwestycje_markery', $whereMarkery);
			
			$whereNews = $db->quoteInto('id_inwest = ?', $id_inwestycja);
			$db->delete('inwestycje_news', $whereNews);
			
			$where = $db->quoteInto('id = ?', $id_inwestycja);
			$db->delete('inwestycje', $where);
			
			$this->_redirect('/admin/inwestycje/');
		}
		
################################################### BUDYNKI ###################################################
		
// Nowy budynek
		public function dodajBudynekAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('budynek', null, true);
			$this->view->pagename = "Nowy budynek";

			$id_inwest = (int)$this->getRequest()->getParam('inwestycja');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwest.'/">Wróć do listy budynków</a></div>';
			$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwest));

			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div><div class="info">Wymiary pliku z rzutem budynku: <b>1350px</b> szerokości / wysokość wg. wysokości obrazka</div>';
			
			$form = new Form_BudynekForm();
			$this->view->form = $form;

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

			//Odczytanie wartosci z inputów
			$formData = $this->_request->getPost();
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			unset($formData['submit']);
			$formData['id_inwest'] = $id_inwest;
			$formData['tag'] = zmiana($formData['nazwa']);

			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);

			//Sprawdzenie poprawnosci forma
			if ($form->isValid($formData)) {

					$data = array(
						'id_inwest' => $id_inwest,
						'nazwa' => $nazwa,
						'numer' => $numer,
						'zakres_powierzchnia' => $zakres_powierzchnia,
						'zakres_pokoje' => $zakres_pokoje,
						'zakres_cen' => $zakres_cen,
						'tag' => $tag,
						'meta_slowa' => $meta_slowa,
						'meta_opis' => $meta_opis,
						'meta_tytul' => $meta_tytul,
						'cords' => $cords,
						'html' => $html
					);
					$db->insert('inwestycje_budynki', $formData);
					$lastId = $db->lastInsertId();

					if ($obrazek) {
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/budynek/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/budynek/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize(1430, 1430)->save($upfile);

						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_budynki', $dataImg, 'id = '.$lastId);
					}

					$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwest.'/');

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
		
// Edytuj budynek
		public function edytujBudynekAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('budynek', null, true);
			
			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div><div class="info">Wymiary pliku z rzutem budynku: <b>1350px</b> szerokości / wysokość wg. wysokości obrazka</div>';
			
			// Odczytanie id
			$id_budynek = (int)$this->getRequest()->getParam('id');
			$id_inwest = (int)$this->getRequest()->getParam('inwestycja');

			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			$this->view->pietro = $budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek));
			$this->view->pagename = "Edytuj budynek: ".$budynek->nazwa;

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwest.'/">Wróć do listy budynków</a></div>';
			$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwest));
			
			$form = new Form_BudynekForm();
			$this->view->form = $form;

			// Załadowanie do forma
			$array = json_decode(json_encode($budynek), true);
			$form->populate($array);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$formData['id_inwest'] = $id_inwest;
					$formData['tag'] = zmiana($formData['nazwa']);

					$obrazek = $_FILES['obrazek']['name'];
					$uniqid = uniqid();
					$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$db->update('inwestycje_budynki', $formData, 'id = '.$id_budynek);
						
						if ($obrazek) {

							unlink(FILES_PATH."/inwestycje/budynek/".$budynek->plik);
							
							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/budynek/'.$plik);
							$upfile = FILES_PATH.'/inwestycje/budynek/'.$plik;
							chmod($upfile, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							$thumb = PhpThumbFactory::create($upfile)->resize(1430, 1430)->save($upfile);
	
							$dataImg = array('plik' => $plik);
							$db->update('inwestycje_budynki', $dataImg, 'id = '.$id_budynek);
						}

						$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwest.'/');

				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Usun budynek
		public function usunBudynekAction() {
			$db = Zend_Registry::get('db');
			
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			$id_budynek = (int)$this->getRequest()->getParam('id');
			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			
			if($inwest->typ == 1) {
			//Inwestycja osiedlowa

				$countfloor = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $id_inwestycja)->where('id_budynek = ?', $id_budynek));
				
				foreach($countfloor as $pietro) {
					try { unlink(FILES_PATH."/inwestycje/pietro/".$pietro->plik); }
					catch (Exception $e) { echo $e->message; }
					
					$where_pietro = $db->quoteInto('id = ?', $pietro->id);
					$db->delete('inwestycje_pietro', $where_pietro);
				}

				$count = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest = ?', $id_inwestycja)->where('id_budynek = ?', $id_budynek));
				foreach($count as $powierzchnia) {
					try { unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik); }
					catch (Exception $e) { echo $e->message; }
					try { unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik); }
					catch (Exception $f) { echo $f->message; }
					try { unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf); }
					catch (Exception $f) { echo $f->message; }
					
					$where2 = $db->quoteInto('id = ?', $powierzchnia->id);
					$db->delete('inwestycje_powierzchnia', $where2);
				}

				$budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $id_inwestycja)->where('id = ?', $id_budynek));
				unlink(FILES_PATH."/inwestycje/budynek/".$budynek->plik);
				
				$where = $db->quoteInto('id = ?', $id_budynek);
				$db->delete('inwestycje_budynki', $where);

				$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');
			}
		}	
		
################################################### PIĘTRA ###################################################

// Pokaz wybrany budynek
		public function pokazBudynekAction() {
			$db = Zend_Registry::get('db');
			
			$id = (int)$this->getRequest()->getParam('id');
			$inwestycja = (int)$this->getRequest()->getParam('inwestycja');

			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $inwestycja));
			$this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id));
			$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_budynek = ?', $id)->where('id_inwest = ?', $inwestycja)->order('numer DESC')->order('typ ASC'));
		}

// Pokaz pomieszczenia dla danego pietra
		public function pokazPietroAction() {
			$db = Zend_Registry::get('db');

			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			$id_budynek = (int)$this->getRequest()->getParam('budynek');
			$id_pietro = (int)$this->getRequest()->getParam('id');
			
			$this->view->inwestycja = $inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));

			if($inwest->typ == 1) {
				$this->view->budynek = $budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek));
			}
			
			$this->view->pietro = $pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id_pietro));

			$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_budynek = ?', $id_budynek)->where('id_inwest = ?', $id_inwestycja)->where('id_pietro = ?', $pietro->id)->where('typ = ?', $pietro->typ)->order('sort ASC'));
		}
 
// Nowe piętro
		public function dodajPietroAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('pietro', null, true);
			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0.</div><div class="info">Wymiary pliku z rzutem piętra: <b>'.$this->planszerokosc.'px</b> szerokości / wysokość wg. wysokości obrazka</div>';
			
			$this->view->tinymce = "1";

			$id_budynek = (int)$this->_request->getParam('budynek');
			$id_inwest = (int)$this->_request->getParam('inwestycja');

			if($id_inwest){
				$inwest = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwest));
			}

			if($id_budynek){
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek)->where('id_inwest = ?', $id_inwest));
			}

			if($inwest->typ == 1) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwest.'/">Wróć do listy pięter</a></div>';
			}

			if($inwest->typ == 2) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwest.'/">Wróć do listy pięter</a></div>';
			}

			$this->view->pagename = "Dodaj piętro - ".$inwest->nazwa;

			$form = new Form_PietroForm();
			$this->view->form = $form;
			
			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$formData['id_budynek'] = $id_budynek;
				$formData['numer_budynek'] = $budynek->numer;
				$formData['id_inwest'] = $id_inwest;
				$formData['tag'] = zmiana($formData['nazwa']);

				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$db->insert('inwestycje_pietro', $formData);
					$lastId = $db->lastInsertId();

					if ($obrazek) {

						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pietro/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/pietro/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize($this->planszerokosc, $this->planszerokosc)->save($upfile);

						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_pietro', $dataImg, 'id = '.$lastId);

					}

					if($inwest->typ == 1) {
						$this->_redirect('/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwest.'/');
					}

					if($inwest->typ == 2) {
						$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwest.'/');
					}

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
		
// Edytuj piętro
		public function edytujPietroAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('pietro', null, true);
			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0.</div><div class="info">Wymiary pliku z rzutem piętra: <b>'.$this->pietroszerokosc.'px</b> szerokości / wysokość wg. wysokości obrazka</div>';
			
			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$id_budynek = (int)$this->_request->getParam('budynek');
			$id_inwest = (int)$this->_request->getParam('inwestycja');

			//Czy masz dostep
			$this->view->tinymce = "1";
			
			if($id_inwest){
				$inwest = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwest));
			}

			if($id_budynek){
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek)->where('id_inwest = ?', $id_inwest));
			}
			
			if($inwest->typ == 1) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwest.'/">Wróć do listy pięter</a></div>';
				$this->view->pietro = $pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id)->where('id_budynek = ?', $id_budynek)->where('id_inwest = ?', $id_inwest));
			}
			
			if($inwest->typ == 2) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwest.'/">Wróć do listy pięter</a></div>';
				$this->view->pietro = $pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id)->where('id_inwest = ?', $id_inwest));
			}

			$this->view->pagename = "Edytuj piętro - ".$pietro->nazwa." - ".$inwest->nazwa;
			$this->view->thumb = $this->view->baseUrl.'/files/inwestycje/pietro/'.$pietro->plik;
			
			$form = new Form_PietroForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Załadowanie do forma
			$array = json_decode(json_encode($pietro), true);
			$form->populate($array);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$formData['id_budynek'] = $id_budynek;
				$formData['numer_budynek'] = $budynek->numer;
				$formData['id_inwest'] = $id_inwest;
				$formData['tag'] = zmiana($formData['nazwa']);

				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$db->update('inwestycje_pietro', $formData, 'id = '.$id);
				
					if ($obrazek) {

						unlink(FILES_PATH."/inwestycja/pietro/".$pietro->plik);
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pietro/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/pietro/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize($this->planszerokosc, $this->planszerokosc)->save($upfile);

						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_pietro', $dataImg, 'id = '.$id);
						
					}

					if($inwest->typ == 1) {
						$this->_redirect('/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwest.'/');
					}

					if($inwest->typ == 2) {
						$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwest.'/');
					}

				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Usun pietro
		public function usunPietroAction() {
			$db = Zend_Registry::get('db');
			
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');

			$id_budynek = (int)$this->getRequest()->getParam('budynek');
			$id_pietro = (int)$this->getRequest()->getParam('id');
			$id_etap = (int)$this->getRequest()->getParam('etap');

			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			
			if($inwest->typ == 1) {
			//Inwestycja osiedlowa
			
				$pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id_pietro)->where('id_inwest = ?', $id_inwestycja)->where('id_budynek = ?', $id_budynek));
				unlink(FILES_PATH."/inwestycje/pietro/".$pietro->plik);

				$count = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_pietro = ?', $id_pietro)->where('id_inwest = ?', $id_inwestycja)->where('id_budynek = ?', $id_budynek));
				foreach($count as $powierzchnia) {
					try { unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik); }
					catch (Exception $e) { echo $e->message; }
					try { unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik); }
					catch (Exception $f) { echo $f->message; }
					try { unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf); }
					catch (Exception $f) { echo $f->message; }
					
					$where2 = $db->quoteInto('id = ?', $powierzchnia->id);
					$db->delete('inwestycje_powierzchnia', $where2);
				}
			
				$where = $db->quoteInto('id = ?', $id_pietro);
				$db->delete('inwestycje_pietro', $where);
				$this->_redirect('/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/');
			}
			
			if($inwest->typ == 2) {
			//Inwestycja budynkowa

				$pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id_pietro)->where('id_inwest = ?', $id_inwestycja));
				unlink(FILES_PATH."/inwestycje/pietro/".$pietro->plik);

				$count = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_pietro = ?', $id_pietro)->where('id_inwest = ?', $id_inwestycja));
				foreach($count as $powierzchnia) {
					try { unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik); }
					catch (Exception $e) { echo $e->message; }
					try { unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik); }
					catch (Exception $f) { echo $f->message; }
					try { unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf); }
					catch (Exception $f) { echo $f->message; }
					
					$where2 = $db->quoteInto('id = ?', $powierzchnia->id);
					$db->delete('inwestycje_powierzchnia', $where2);
				}
			
				$where = $db->quoteInto('id = ?', $id_pietro);
				$db->delete('inwestycje_pietro', $where);

				$this->_redirect('admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');
				
			}
		}

// Kopiuj pietro
		public function kopiujPietroAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');

			$id_budynek = (int)$this->getRequest()->getParam('budynek');
			$id_pietro = (int)$this->getRequest()->getParam('id');

			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			if($inwest->typ == 1) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/">Wróć do listy pięter</a></div>';
			}
			
			if($inwest->typ == 2) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/">Wróć do listy pięter</a></div>';
			}
			
			$form = new Form_PietroCopyForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			
			if ($this->_request->getPost()) {
				
				//Odczytanie wartosci z inputów
				$pietro = (int)$this->_request->getPost('pietro');
				$formData = $this->_request->getPost();

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {
					
					$pietroSQL = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $pietro));
				
				if($inwest->typ == 1) {
				//Inwestycja osiedlowa
				
					$mieszkania = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_budynek = ?', $id_budynek)->where('id_inwest = ?', $id_inwestycja)->where('id_pietro = ?', $id_pietro));
					
					foreach ($mieszkania as $mieszkanie) {
						
						$array = json_decode(json_encode($mieszkanie), true);
						unset($array['id']);
						unset($array['plik']);
						unset($array['plik2']);
						unset($array['pdf']);
						$array['id_pietro'] = $pietroSQL->id;
						$array['numer_pietro'] = $pietroSQL->numer;
						$array['typ'] = $pietroSQL->typ;
					
						$db->insert('inwestycje_powierzchnia', $array);
					}
					$this->_redirect('/admin/inwestycje/pokaz-budynek/id/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/');
				}
				
				if($inwest->typ == 2) {
				//Inwestycja budynkowa
				
					$mieszkania = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest = ?', $id_inwestycja)->where('id_pietro = ?', $id_pietro));
					
					foreach ($mieszkania as $mieszkanie) {
						
						$array = json_decode(json_encode($mieszkanie), true);
						unset($array['id']);
						unset($array['plik']);
						unset($array['plik2']);
						unset($array['pdf']);
						$array['id_pietro'] = $pietroSQL->id;
						$array['numer_pietro'] = $pietroSQL->numer;
						$array['typ'] = $pietroSQL->typ;
					
						$db->insert('inwestycje_powierzchnia', $array);
					}
				
					$this->_redirect('admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');
					
				}
			
				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			
			}
		}

################################################### POMIESZCZENIA ###################################################

// Dodaj pomieszczenie
		public function dodajPowierzchnieAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('pomieszczenie', null, true);
			
			$typ = (int)$this->getRequest()->getParam('typ');
			if($typ == 1) {
				$this->view->pagename = "Dodaj mieszkanie";
			}
			if($typ == 2) {
				$this->view->pagename = "Dodaj lokal usługowy";
			}
			if($typ == 3 || $typ == 4) {
				$this->view->pagename = "Dodaj miejsce parkingowe";
			}
			
			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div>';
			
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			$id_budynek = (int)$this->getRequest()->getParam('budynek');
			$id_pietro = (int)$this->getRequest()->getParam('pietro');
			
			if($id_inwestycja) {
				$this->view->inwestycja = $inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			}

			if($id_budynek) {
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek));
			}
			
			if($id_pietro){
				$pietro = $this->view->pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id_pietro));
			}
			
			if($inwest->typ == 1) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/budynek/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/">Wróć do listy lokali</a></div>';
			}
			
			if($inwest->typ == 2) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/inwestycja/'.$id_inwestycja.'/">Wróć do listy lokali</a></div>';
			}
			
			if($inwest->typ == 3) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/budynek/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/etap/'.$id_etap.'/">Wróć do listy lokali</a></div>';
			}
			
			$this->view->tinymce = "1";
			$form = new Form_PowierzchniaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);
			
			if($typ == 2 || $typ == 3 || $typ == 4) {
				$form->removeElement('pokoje');
				$form->removeElement('okno');
				$form->removeElement('typ_okno');
				$form->removeElement('ogrzewanie');
				//$form->removeElement('stan');
			}

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				
				$formData['id_budynek'] = $id_budynek;
				$formData['numer_budynek'] = $budynek->numer;
				$formData['id_inwest'] = $id_inwestycja;
				$formData['tag'] = zmiana($formData['nazwa']);
				$formData['id_pietro'] = $id_pietro;
				$formData['numer_pietro'] = $pietro->numer;
				
				$formData['media'] = implode(',', $this->_request->getPost('media'));
				$formData['zabezpieczenia'] = implode(',', $this->_request->getPost('zabezpieczenia'));
				$formData['info_dodatkowe'] = implode(',', $this->_request->getPost('info_dodatkowe'));
				$formData['okno'] = implode(',', $this->_request->getPost('okno'));

				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);
				
				$obrazek2 = $_FILES['obrazek2']['name'];
				$uniqid = uniqid();
				$plik2 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek2);
				
				$pdfile = $_FILES['pdf']['name'];
				$uniqid = uniqid();
				$pdf = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($pdfile);

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					//Pomyslnie
					$db->insert('inwestycje_powierzchnia', $formData);
					$lastId = $db->lastInsertId();

					if($obrazek) {
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik;
						$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
							
						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_powierzchnia', $dataImg, 'id = '.$lastId);
					}
					
					if($obrazek2) {
						move_uploaded_file($_FILES['obrazek2']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2);
						$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2;
						$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik2;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
							
						$dataImg2 = array('plik2' => $plik2);
						$db->update('inwestycje_powierzchnia', $dataImg2, 'id = '.$lastId);
					}

					if($pdfile) {
						move_uploaded_file($_FILES['pdf']['tmp_name'], FILES_PATH.'/inwestycje/pdf/'.$pdf);
						$upfile = FILES_PATH.'/inwestycje/pdf/'.$pdf;
						chmod($upfile, 0755);
								
						$dataPdf = array('pdf' => $pdf);
						$db->update('inwestycje_powierzchnia', $dataPdf, 'id = '.$lastId);
					}

					if($inwest->typ == 1) {
						$this->_redirect('/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/budynek/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/');
					}
					
					if($inwest->typ == 2) {
						$this->_redirect('/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/inwestycja/'.$id_inwestycja.'/');
					}

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
		
// Edytuj
		public function edytujPowierzchnieAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('pomieszczenie', null, true);
			$this->view->tinymce = "1";
			
			$typ = (int)$this->getRequest()->getParam('typ');
			if($typ == 1) {
				$this->view->pagename = "Edytuj mieszkanie";
			}
			if($typ == 2) {
				$this->view->pagename = "Edytuj lokal usługowy";
			}
			if($typ == 3 || $typ == 4) {
				$this->view->pagename = "Edytuj miejsce parkingowe";
			}

			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div>';
			
			$id = (int)$this->_request->getParam('id');
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			$id_budynek = (int)$this->getRequest()->getParam('budynek');
			$id_pietro = (int)$this->getRequest()->getParam('pietro');
			
			if($id_inwestycja){
				$inwest = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			}

			if($id_budynek) {
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek));
			}
			
			if($id_pietro) {
				$pietro = $this->view->pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id = ?', $id_pietro));
			}
			
			if($id) {
				$this->view->powierzchnia = $powierzchnia = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('id = ?', $id));
			}
			
			if($inwest->typ == 1) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/budynek/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/">Wróć do listy lokali</a></div>';
			}
			
			if($inwest->typ == 2) {
				$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/inwestycja/'.$id_inwestycja.'/">Wróć do listy lokali</a></div>';
			}

			$this->view->tinymce = "1";
			$form = new Form_PowierzchniaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			if($typ == 2 || $typ == 3 || $typ == 4) {
				$form->removeElement('pokoje');
				$form->removeElement('okno');
				$form->removeElement('typ_okno');
				$form->removeElement('ogrzewanie');
				//$form->removeElement('stan');
			}

			// Załadowanie do forma 
			$form->nazwa->setvalue($powierzchnia->nazwa);
			$form->numer->setvalue($powierzchnia->numer);
			if($typ == 1){
				$form->pokoje->setvalue($powierzchnia->pokoje);
				$oknoArray = explode(',',$powierzchnia->okno);
				$form->okno->setvalue($oknoArray);
				$form->typ_okno->setvalue($powierzchnia->typ_okno);
				//$form->stan->setvalue($powierzchnia->stan);
				$form->ogrzewanie->setvalue($powierzchnia->ogrzewanie);
			}
			$form->cords->setvalue($powierzchnia->cords);
			$form->html->setvalue($powierzchnia->html);
			$form->status->setvalue($powierzchnia->status);
			$form->metry->setvalue($powierzchnia->metry);
			$form->balkon->setvalue($powierzchnia->balkon);
			$form->ogrodek->setvalue($powierzchnia->ogrodek);
			$form->promocja->setvalue($powierzchnia->promocja);
			$form->kuchnia->setvalue($powierzchnia->kuchnia);
			
			$info_dodatkoweArray = explode(',',$powierzchnia->info_dodatkowe);
			$form->info_dodatkowe->setvalue($info_dodatkoweArray);

			$zabezpieczeniaArray = explode(',',$powierzchnia->zabezpieczenia);
			$form->zabezpieczenia->setvalue($zabezpieczeniaArray);
			
			$mediaArray = explode(',',$powierzchnia->media);
			$form->media->setvalue($mediaArray);

			$form->material->setvalue($powierzchnia->material);
			$form->szukaj_metry->setvalue($powierzchnia->szukaj_metry);
			$form->cena->setvalue($powierzchnia->cena);
			$form->szukaj_cena->setvalue($powierzchnia->szukaj_cena);
			$form->obrazek->setvalue($powierzchnia->plik);
			$form->obrazek2->setvalue($powierzchnia->plik2);
			$form->pdf->setvalue($powierzchnia->pdf);
			//$form->tekst->setvalue($powierzchnia->tekst);
			$form->meta_slowa->setvalue($powierzchnia->meta_slowa);
			$form->meta_opis->setvalue($powierzchnia->meta_opis);
			$form->meta_tytul->setvalue($powierzchnia->meta_tytul);
			$form->cena_promocja->setvalue($powierzchnia->cena_promocja);
			$form->cena_m->setvalue($powierzchnia->cena_m);
			$form->cena_m_promocja->setvalue($powierzchnia->cena_m_promocja);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					
					$formData['id_budynek'] = $id_budynek;
					$formData['numer_budynek'] = $budynek->numer;
					$formData['id_inwest'] = $id_inwestycja;
					$formData['tag'] = zmiana($formData['nazwa']);
					$formData['id_pietro'] = $id_pietro;
					$formData['numer_pietro'] = $pietro->numer;
					
					$formData['media'] = implode(',', $this->_request->getPost('media'));
					$formData['zabezpieczenia'] = implode(',', $this->_request->getPost('zabezpieczenia'));
					$formData['info_dodatkowe'] = implode(',', $this->_request->getPost('info_dodatkowe'));
					$formData['okno'] = implode(',', $this->_request->getPost('okno'));

					$obrazek = $_FILES['obrazek']['name'];
					$uniqid = uniqid();
					$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);
					
					$obrazek2 = $_FILES['obrazek2']['name'];
					$uniqid = uniqid();
					$plik2 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek2);
					
					$pdfile = $_FILES['pdf']['name'];
					$uniqid = uniqid();
					$pdf = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($pdfile);
					
						//Sprawdzenie poprawnosci forma
						if ($form->isValid($formData)) {
							
							// print_r($formData);

							$db->update('inwestycje_powierzchnia', $formData, 'id = '.$id);
								
							if($obrazek) {
								unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik);
								unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik);
								
								move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik);
								$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik;
								$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik;
								chmod($upfile, 0755);
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';
								$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
								
								$dataImg = array('plik' => $plik);
								$db->update('inwestycje_powierzchnia', $dataImg, 'id = '.$id);
							}
					
							if($obrazek2) {
								unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik2);
								unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik2);
								
								move_uploaded_file($_FILES['obrazek2']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2);
								$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2;
								$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik2;
								chmod($upfile, 0755);
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';
								$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
									
								$dataImg2 = array('plik2' => $plik2);
								$db->update('inwestycje_powierzchnia', $dataImg2, 'id = '.$id);
							}
								
							if($pdfile) {
								unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf);
								move_uploaded_file($_FILES['pdf']['tmp_name'], FILES_PATH.'/inwestycje/pdf/'.$pdf);
								$upfile = FILES_PATH.'/inwestycje/pdf/'.$pdf;
								chmod($upfile, 0755);
									
								$dataPdf = array('pdf' => $pdf);
								$db->update('inwestycje_powierzchnia', $dataPdf, 'id = '.$id);
							}

							
							if($inwest->typ == 1) {
								$this->_redirect('/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/budynek/'.$id_budynek.'/inwestycja/'.$id_inwestycja.'/');
							}
							
							if($inwest->typ == 2) {
								$this->_redirect('/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/inwestycja/'.$id_inwestycja.'/');
							}

						} else {

							//Wyswietl bledy	
							$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
							$form->populate($formData);

						}

				}
		}

// Usun pomieszczenie 
		public function usunPowierzchnieAction() {
			$db = Zend_Registry::get('db');
			
			$id = (int)$this->_request->getParam('id');
			$id_pietro = (int)$this->_request->getParam('pietro');
			$id_inwestycja = (int)$this->_request->getParam('inwestycja');
			$id_budynek = (int)$this->_request->getParam('budynek');

			$powierzchnia = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('id = ?', $id));
			
			unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik);
			unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik);
			unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf);

			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
			
			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inwestycje_powierzchnia', $where);

			if($inwest->typ == 1) {
				//Inwestycja osiedlowa
				$this->_redirect('/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/inwestycja/'.$id_inwestycja.'/budynek/'.$id_budynek.'/');
			}
			
			if($inwest->typ == 2) {
				//Inwestycja budynkowa
				$this->_redirect('/admin/inwestycje/pokaz-pietro/id/'.$id_pietro.'/inwestycja/'.$id_inwestycja.'/');
			}
		}


################################################### DOMKI ###################################################

// Pokaz pomieszczenia dla danego pietra
		public function pokazDomAction() {
			$db = Zend_Registry::get('db');

		}
 
// Dodaj domek
		public function dodajDomAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('dom', null, true);

			$this->view->pagename = "Dodaj domek";
			
			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div>';
			
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			if($id_inwestycja) {
				$this->view->inwestycja = $inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwestycja));
			}
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/">Wróć do planu</a></div>';

			$this->view->tinymce = "1";
			
			$form = new Form_PowierzchniaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				
				$formData['id_budynek'] = 0;
				$formData['numer_budynek'] = 0;
				$formData['id_inwest'] = $id_inwestycja;
				$formData['tag'] = zmiana($formData['nazwa']);
				$formData['id_pietro'] = 0;
				$formData['numer_pietro'] = 0;
				
				$formData['media'] = implode(',', $this->_request->getPost('media'));
				$formData['zabezpieczenia'] = implode(',', $this->_request->getPost('zabezpieczenia'));
				$formData['info_dodatkowe'] = implode(',', $this->_request->getPost('info_dodatkowe'));
				$formData['okno'] = implode(',', $this->_request->getPost('okno'));
				$formData['typ'] = 5;

				$obrazek = $_FILES['obrazek']['name'];
				$uniqid = uniqid();
				$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);
				
				$obrazek2 = $_FILES['obrazek2']['name'];
				$uniqid = uniqid();
				$plik2 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek2);
				
				$pdfile = $_FILES['pdf']['name'];
				$uniqid = uniqid();
				$pdf = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($pdfile);

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					//Pomyslnie
					$db->insert('inwestycje_powierzchnia', $formData);
					$lastId = $db->lastInsertId();

					if($obrazek) {
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik;
						$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
							
						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_powierzchnia', $dataImg, 'id = '.$lastId);
					}
					
					if($obrazek2) {
						move_uploaded_file($_FILES['obrazek2']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2);
						$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2;
						$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik2;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
							
						$dataImg2 = array('plik2' => $plik2);
						$db->update('inwestycje_powierzchnia', $dataImg2, 'id = '.$lastId);
					}

					if($pdfile) {
						move_uploaded_file($_FILES['pdf']['tmp_name'], FILES_PATH.'/inwestycje/pdf/'.$pdf);
						$upfile = FILES_PATH.'/inwestycje/pdf/'.$pdf;
						chmod($upfile, 0755);
								
						$dataPdf = array('pdf' => $pdf);
						$db->update('inwestycje_powierzchnia', $dataPdf, 'id = '.$lastId);
					}

					$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
		
// Edytuj domek
		public function edytujDomAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('dom', null, true);
			$this->view->tinymce = "1";

			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div>';
			
			$id = (int)$this->_request->getParam('id');
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			if($id_inwestycja) {
				$this->view->inwestycja = $inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwestycja));
			}
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/">Wróć do planu</a></div>';

			$this->view->tinymce = "1";
			
			if($id) {
				$this->view->powierzchnia = $powierzchnia = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('id = ?', $id));
			}

			$form = new Form_PowierzchniaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Załadowanie do forma 
			$form->nazwa->setvalue($powierzchnia->nazwa);
			$form->numer->setvalue($powierzchnia->numer);
			$form->pokoje->setvalue($powierzchnia->pokoje);
			$oknoArray = explode(',',$powierzchnia->okno);
			$form->okno->setvalue($oknoArray);
			$form->typ_okno->setvalue($powierzchnia->typ_okno);
			$form->ogrzewanie->setvalue($powierzchnia->ogrzewanie);
			$form->cords->setvalue($powierzchnia->cords);
			$form->html->setvalue($powierzchnia->html);
			$form->status->setvalue($powierzchnia->status);
			$form->metry->setvalue($powierzchnia->metry);
			$form->balkon->setvalue($powierzchnia->balkon);
			$form->ogrodek->setvalue($powierzchnia->ogrodek);
			$form->promocja->setvalue($powierzchnia->promocja);
			$form->kuchnia->setvalue($powierzchnia->kuchnia);
			
			$info_dodatkoweArray = explode(',',$powierzchnia->info_dodatkowe);
			$form->info_dodatkowe->setvalue($info_dodatkoweArray);

			$zabezpieczeniaArray = explode(',',$powierzchnia->zabezpieczenia);
			$form->zabezpieczenia->setvalue($zabezpieczeniaArray);
			
			$mediaArray = explode(',',$powierzchnia->media);
			$form->media->setvalue($mediaArray);

			$form->material->setvalue($powierzchnia->material);
			$form->szukaj_metry->setvalue($powierzchnia->szukaj_metry);
			$form->cena->setvalue($powierzchnia->cena);
			$form->szukaj_cena->setvalue($powierzchnia->szukaj_cena);
			$form->obrazek->setvalue($powierzchnia->plik);
			$form->obrazek2->setvalue($powierzchnia->plik2);
			$form->pdf->setvalue($powierzchnia->pdf);
			//$form->tekst->setvalue($powierzchnia->tekst);
			$form->meta_slowa->setvalue($powierzchnia->meta_slowa);
			$form->meta_opis->setvalue($powierzchnia->meta_opis);
			$form->meta_tytul->setvalue($powierzchnia->meta_tytul);
			$form->cena_promocja->setvalue($powierzchnia->cena_promocja);
			$form->cena_m->setvalue($powierzchnia->cena_m);
			$form->cena_m_promocja->setvalue($powierzchnia->cena_m_promocja);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					
					$formData['id_budynek'] = 0;
					$formData['numer_budynek'] = 0;
					$formData['id_inwest'] = $id_inwestycja;
					$formData['tag'] = zmiana($formData['nazwa']);
					$formData['id_pietro'] = 0;
					$formData['numer_pietro'] = 0;
					$formData['typ'] = 5;
				
					$formData['media'] = implode(',', $this->_request->getPost('media'));
					$formData['zabezpieczenia'] = implode(',', $this->_request->getPost('zabezpieczenia'));
					$formData['info_dodatkowe'] = implode(',', $this->_request->getPost('info_dodatkowe'));
					$formData['okno'] = implode(',', $this->_request->getPost('okno'));

					$obrazek = $_FILES['obrazek']['name'];
					$uniqid = uniqid();
					$plik = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek);
					
					$obrazek2 = $_FILES['obrazek2']['name'];
					$uniqid = uniqid();
					$plik2 = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($obrazek2);
					
					$pdfile = $_FILES['pdf']['name'];
					$uniqid = uniqid();
					$pdf = $formData['tag'].'_'.$uniqid.'.'.zmiennazwe($pdfile);
					
						//Sprawdzenie poprawnosci forma
						if ($form->isValid($formData)) {
							
							// print_r($formData);

							$db->update('inwestycje_powierzchnia', $formData, 'id = '.$id);
								
							if($obrazek) {
								unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik);
								unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik);
								
								move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik);
								$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik;
								$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik;
								chmod($upfile, 0755);
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';
								$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
								
								$dataImg = array('plik' => $plik);
								$db->update('inwestycje_powierzchnia', $dataImg, 'id = '.$id);
							}
					
							if($obrazek2) {
								unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik2);
								unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik2);
								
								move_uploaded_file($_FILES['obrazek2']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2);
								$upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2;
								$thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik2;
								chmod($upfile, 0755);
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';
								$thumb = PhpThumbFactory::create($upfile)->resize(960, 960)->save($upfile)->resize(780, 1500)->save($thumbs);
									
								$dataImg2 = array('plik2' => $plik2);
								$db->update('inwestycje_powierzchnia', $dataImg2, 'id = '.$id);
							}
								
							if($pdfile) {
								unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf);
								move_uploaded_file($_FILES['pdf']['tmp_name'], FILES_PATH.'/inwestycje/pdf/'.$pdf);
								$upfile = FILES_PATH.'/inwestycje/pdf/'.$pdf;
								chmod($upfile, 0755);
									
								$dataPdf = array('pdf' => $pdf);
								$db->update('inwestycje_powierzchnia', $dataPdf, 'id = '.$id);
							}

							
							$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');

						} else {

							//Wyswietl bledy	
							$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
							$form->populate($formData);

						}

				}
		}

// Usun domek 
		public function usunDomAction() {
			$db = Zend_Registry::get('db');
			
			$id = (int)$this->_request->getParam('id');
			$id_inwestycja = (int)$this->_request->getParam('inwestycja');
			$powierzchnia = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('id = ?', $id));
			
			unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik);
			unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik);
			unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf);

			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inwestycje_powierzchnia', $where);
			$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');

		}
	
################################################### WYSZUKIWARKA MIESZKAN ###################################################

// Wyszukiwarka mieszkan
		public function szukajMieszkanieAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			
			$this->view->numer = $numer = $this->_request->getParam('numer');
			$this->view->areaFrom = $areaFrom = $this->_request->getParam('metraz_min');
			$this->view->areaTo = $areaTo = $this->getRequest()->getParam('metraz_max');
			$this->view->status = $status = $this->_request->getParam('status');
			$this->view->rooms = $rooms = $this->_request->getParam('pokoje');
			$this->view->floor = $floor = $this->_request->getParam('pietro');

			$select_pomieszczenia = $db->select()
			->from('inwestycje_powierzchnia')
			->where('id_inwest = ?', $id)
			->order('sort ASC');
			
			if($numer) {$select_pomieszczenia->where('numer LIKE ?', '%'.$numer.'%');}
			if($status) {$select_pomieszczenia->where('status =?', $status);}
			if($rooms) {$select_pomieszczenia->where('pokoje =?', $rooms);}
			if($floor || $floor == '0') {$select_pomieszczenia->where('numer_pietro =?', $floor);}
			if($areaFrom) {$select_pomieszczenia->where('szukaj_metry >=?', $areaFrom);}
			if($areaTo) {$select_pomieszczenia->where('szukaj_metry <=?', $areaTo);}
			
			$this->view->lista = $result = $db->fetchAll($select_pomieszczenia);
		}

################################################### STRONY OPISOWE ###################################################

// Pokaz strony dla danej inwestycji
		public function stronyAction() {
			$db = Zend_Registry::get('db');

			$id = (int)$this->getRequest()->getParam('id');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			$this->view->strony = $db->fetchAll($db->select()->from('inwestycje_strony')->where('id_inwest =?', $id)->order('sort ASC'));
		}
		
// Dodaj nową stronę
		public function nowaStronaAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowa strona";
			$this->view->tinymce = "1";
			
			$inwestycja = (int)$this->getRequest()->getParam('inwestycja');
			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $inwestycja));
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/strony/id/'.$inwestycja.'/">Wróć do listy stron</a></div>';

			$form = new Form_StronaRealizForm();
			$this->view->form = $form;

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$tag = zmiana($nazwa);
					$formData = $this->_request->getPost();
					unset($formData['submit']);
					$formData['tag'] = zmiana($formData['nazwa']);
					$formData['tag_inwest'] = $inwest->tag;
					$formData['id_inwest'] = $inwestycja;
					$formData['typ'] = 0;

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
					//Pomyslnie
					$db->insert('inwestycje_strony', $formData);
					$this->_redirect('/admin/inwestycje/strony/id/'.$inwestycja.'/');

				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Edytuj stronę
		public function edytujStroneAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->tinymce = "1";

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$strony = $db->fetchRow($db->select()->from('inwestycje_strony')->where('id = ?', $id));
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/strony/id/'.$strony->id_inwest.'/">Wróć do listy stron</a></div>';
			$this->view->pagename = " - Edytuj stronę: ".$strony->nazwa;

			$form = new Form_StronaRealizForm();
			$this->view->form = $form;

			// Załadowanie do forma
			$array = json_decode(json_encode($strony), true);
			$form->populate($array);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $strony->id_inwest));
					$tag = zmiana($nazwa);
					$formData = $this->_request->getPost();
					unset($formData['submit']);
					$formData['tag'] = zmiana($formData['nazwa']);
					$formData['tag_inwest'] = $inwest->tag;
					$formData['id_inwest'] = $strony->id_inwest;
					$formData['typ'] = 0;

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					//Pomyslnie
					$db->update('inwestycje_strony', $formData, 'id = '.$id);
					$this->_redirect('/admin/inwestycje/strony/id/'.$strony->id_inwest.'/');

				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Usun wpis do stron
		public function usunStroneAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$strony = $db->fetchRow($db->select()->from('inwestycje_strony')->where('id = ?', $id));
			
			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inwestycje_strony', $where);	

			$this->_redirect('/admin/inwestycje/strony/id/'.$strony->id_inwest.'/');
		}

// Ustaw kolejność
		public function ustawStronyAction() {
			$db = Zend_Registry::get('db');
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {
				$data = array('sort' => $listingCounter);
				$db->update('inwestycje_strony', $data, 'id = '.$recordIDValue);
				$listingCounter = $listingCounter + 1;
				}
		}

################################################### AKTUALNOSCI ###################################################
	
// Pokaz wszystkie wpisy
		public function newsAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->getRequest()->getParam('id');
			$this->view->news = $db->fetchAll($db->select()->from('inwestycje_news')->order('data DESC')->where('id_inwest = ?', $id));
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		}

// Dodaj nowy wpis
		public function nowyWpisAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowy wpis";

			$id_inwest = (int)$this->getRequest()->getParam('inwest');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/news/id/'.$id_inwest.'/">Wróć do listy wpisów</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';

			$form = new Form_NewsForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$this->view->tinymce = "1";

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów 
					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($formData['tytul']).'.'.zmiennazwe($obrazek);
					$formData['id_inwest'] = $id_inwest;

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$db->insert('inwestycje_news', $formData);
						$lastId = $db->lastInsertId();
			
					//Pomyslnie
					if ($obrazek) {

						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/news/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/news/'.$plik;
						$thumbs = FILES_PATH.'/inwestycje/news/thumbs/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($this->malaszerokosc, $this->malawysokosc)->save($thumbs);
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($this->duzaszerokosc, $this->duzawysokosc)->save($upfile);
	
						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_news', $dataImg, 'id = '.$lastId);
						
					}

					
					$this->_redirect('/admin/inwestycje/news/id/'.$id_inwest.'/');

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Edytuj wpis
		public function edytujWpisAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);

			$id_inwest = (int)$this->getRequest()->getParam('inwest');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/news/id/'.$id_inwest.'/">Wróć do listy wpisów</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';
			
			$form = new Form_NewsForm();
			$this->view->form = $form;
			$this->view->tinymce = "1";

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$wpis = $db->fetchRow($db->select()->from('inwestycje_news')->where('id = ?', $id));
			$this->view->pagename = " - Edytuj wpis: ".$wpis->tytul;

			// Załadowanie do forma
			$array = json_decode(json_encode($wpis), true);
			$form->populate($array);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($formData['tytul']).'.'.zmiennazwe($obrazek);
					$formData['id_inwest'] = $id_inwest;

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
						$db->update('inwestycje_news', $formData, 'id = '.$id);

						if ($obrazek) {
							// Usuwanie starych zdjęć
							unlink(FILES_PATH."/inwestycje/news/".$wpis->plik);
							unlink(FILES_PATH."/inwestycje/news/thumbs/".$wpis->plik);

							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/news/'.$plik);
							$upfile = FILES_PATH.'/inwestycje/news/'.$plik;
							$thumbs = FILES_PATH.'/inwestycje/news/thumbs/'.$plik;
							chmod($upfile, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($this->malaszerokosc, $this->malawysokosc)->save($thumbs);
							$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($this->duzaszerokosc, $this->duzawysokosc)->save($upfile);
	
							$dataImg = array('plik' => $plik);
							$db->update('inwestycje_news', $dataImg, 'id = '.$id);
							
						}
						$this->_redirect('/admin/inwestycje/news/id/'.$id_inwest.'/');

				} else {
									
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Usun wpis
		public function usunWpisAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$id_inwest = (int)$this->getRequest()->getParam('inwest');
			
			$wpis = $db->fetchRow($db->select()->from('inwestycje_news')->where('id = ?', $id));
			
			unlink(FILES_PATH."/inwestycje/news/".$wpis->plik);
			unlink(FILES_PATH."/inwestycje/news/thumbs/".$wpis->plik);
			
			$db->delete('inwestycje_news', 'id = '.$id);
			$this->_redirect('/admin/inwestycje/news/id/'.$id_inwest.'/');
		}
		
################################################### LOKALIZACJE ###################################################
		
// Pokaz punkty na mapie
		public function lokalizacjaAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');

			$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_markery')->order('nazwa ASC')->where('id_inwest =?', $id));
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		}
		
// Usuń punkt
		public function usunPunktAction() {
			$db = Zend_Registry::get('db');
			// Odczytanie id obrazka
			$id = (int)$this->_request->getParam('id');
			$id_inwest = (int)$this->_request->getParam('inwestycja');

			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inwestycje_markery', $where);

			$this->_redirect('/admin/lokalizacja/mapa/');
		}
		
// Dodaj punkt na mapie
		public function nowyPunktAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('mapa', null, true);
			$this->view->pagename = " - Nowa lokalizacja";

			$id_inwest = (int)$this->_request->getParam('inwestycja');
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/">Wróć do listy</a></div>';

			// Wyswietl formularz
			$form = new Form_LokalizacjaForm();
			$this->view->form = $form;

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów 
					$formData = $this->_request->getPost();
					unset($formData['submit']);
					$formData['id_inwest'] = $id_inwest;

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$db->insert('inwestycje_markery', $formData);
						$this->_redirect('/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/');

					} else {
						
						//Wyswietl bledy	
						$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
						$form->populate($formData);

				}
			}
		}

// Edytuj punkt na mapie
		public function edytujPunktAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('mapa', null, true);

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$id_inwest = (int)$this->_request->getParam('inwestycja');
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/">Wróć do listy</a></div>';
			
			$wpis = $this->view->mapa = $db->fetchRow($db->select()->from('inwestycje_markery')->where('id = ?', $id));
			$this->view->pagename = " - Edytuj lokalizacje: ".$lokalizacja->nazwa;

			$form = new Form_LokalizacjaForm();
			$this->view->form = $form;

			// Załadowanie do forma
			$array = json_decode(json_encode($wpis), true);
			$form->populate($array);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$formData = $this->_request->getPost();
					unset($formData['submit']);
					$formData['id_inwest'] = $id_inwest;

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

					$db->update('inwestycje_markery', $formData, 'id = '.$id);
					$this->_redirect('/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/');

				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
		
################################################### ATUTY ###################################################
	
// Pokaz wszystkie wpisy
		public function atutyAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->getRequest()->getParam('id');

			$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_atut')->order('sort ASC')->where('id_inwest = ?', $id));
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		}

// Dodaj nowy wpis
		public function nowyAtutAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowy wpis";

			$id_inwest = (int)$this->getRequest()->getParam('inwest');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/atuty/id/'.$id_inwest.'/">Wróć do listy wpisów</a></div>';
			$this->view->info = '<div class="info">Obrazek o wymiarach: szerokość <b>80</b>px / wysokość <b>80</b>px</div>';

			$form = new Form_NazwaPlikForm();
			$this->view->form = $form;

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów 
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$obrazek = $_FILES['obrazek']['name'];
				$plik = zmiana($formData['nazwa']).'.'.zmiennazwe($obrazek);
				$formData['id_inwest'] = $id_inwest;

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					$db->insert('inwestycje_atut', $formData);
					$lastId = $db->lastInsertId();

					//Pomyslnie
					if ($obrazek) {
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/atuty/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/atuty/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant(80, 80)->save($upfile);
						$dataImg = array('ikona' => $plik);
						$db->update('inwestycje_atut', $dataImg, 'id = '.$lastId);
					}

					$this->_redirect('/admin/inwestycje/atuty/id/'.$id_inwest.'/');

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}

// Edytuj wpis
		public function edytujAtutAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);

			$id_inwest = (int)$this->getRequest()->getParam('inwest');
			$id = (int)$this->getRequest()->getParam('id');
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			$wpis = $db->fetchRow($db->select()->from('inwestycje_atut')->where('id = ?', $id));

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/atuty/id/'.$id_inwest.'/">Wróć do listy wpisów</a></div>';
			$this->view->info = '<div class="info">Obrazek o wymiarach: szerokość <b>80</b>px / wysokość <b>80</b>px</div>';

			$form = new Form_NazwaPlikForm();
			$this->view->form = $form;

			// Odczytanie id
			$this->view->pagename = " - Edytuj wpis: ".$wpis->nazwa;

			// Załadowanie do forma
			$array = json_decode(json_encode($wpis), true);
			$form->populate($array);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów 
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);
				$obrazek = $_FILES['obrazek']['name'];
				$plik = zmiana($formData['nazwa']).'.'.zmiennazwe($obrazek);
				$formData['id_inwest'] = $id_inwest;

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {
					$db->update('inwestycje_atut', $formData, 'id = '.$id);
					
					if ($obrazek) {
						//Usuwanie starych zdjęć
						unlink(FILES_PATH."/inwestycje/atuty/".$wpis->ikona);
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/atuty/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/atuty/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant(80, 80)->save($upfile);
						$dataImg = array('ikona' => $plik);
						$db->update('inwestycje_atut', $dataImg, 'id = '.$id);
					}
					
					$this->_redirect('/admin/inwestycje/atuty/id/'.$id_inwest.'/');
				} else {
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);
				}
			}
		}

// Usun wpis
		public function usunAtutAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$id_inwest = (int)$this->getRequest()->getParam('inwest');
			$db->delete('inwestycje_atut', 'id = '.$id);
			$this->_redirect('/admin/inwestycje/atuty/id/'.$id_inwest.'/');
		}
		
################################################### WYSZUKIWARKA ###################################################

// Wyszukiwarka
		public function wyszukiwarkaAction() {
			$db = Zend_Registry::get('db');
			

			//Ustawienia główne strony
			if ($this->_request->getPost('submitSearch', false)) {

				$mieszkania_pokoje = $this->_request->getPost('mieszkania_pokoje');
				$mieszkania_metry = $this->_request->getPost('mieszkania_metry');

				$data = array(
					'mieszkania_pokoje' => $mieszkania_pokoje,
					'mieszkania_metry' => $mieszkania_metry,
				);
				
				$db->update('ustawienia', $data);
				$this->_redirect('/admin/inwestycje/wyszukiwarka/');
			}
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
}
