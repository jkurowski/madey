<?php

class Admin_InwestycjeController extends kCMS_Admin
{
		public function preDispatch() {
			$this->view->controlname = "Inwestycje";
			
			$this->planszerokosc = $this->view->planszerokosc = 995;
			$this->planwysokosc = $this->view->planwysokosc = 650;
			
			$this->pietroszerokosc = 995;
			$this->pietrowysokosc = 650;
			
			//Aktualnosci
			$this->duzawysokosc = '400';
			$this->duzaszerokosc = '995';
			$this->malawysokosc = '241';
			$this->malaszerokosc = '600';
			
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
						return "Na sprzedaż";
					case '2':
						return "Sprzedane";
					case '3':
						return "Rezerwacja";
					case '4':
						return "Wynajęte";
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

		
		public function bazaAction() {
			$db = Zend_Registry::get('db');
			
			// $this->view->budynki = $db->fetchAll($db->select()->from('inwestycje_budynki'));
			// $pietra = $this->view->pietra = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest =?', 29)->where('id_budynek =?', 18));
			// $mieszkania = $this->view->mieszkania = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest =?', 29)->where('id_budynek =?', 18));
			// $zdjecia = $db->fetchAll($db->select()->from('galeria_zdjecia'));
			
			echo count($zdjecia).'<hr>';
			
			// foreach($zdjecia as $zdjecie){

				// $upfile = FILES_PATH.'/galeria/big/'.$zdjecie->plik;
				// $thumbs = FILES_PATH.'/galeria/thumbs/'.$zdjecie->plik;
			
				// if (file_exists($upfile)) {
					// require_once 'kCMS/Thumbs/ThumbLib.inc.php';
					// $options = array('jpegQuality' => 80);
					// $thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant(288, 215, 'B');
					// $thumb->save($thumbs);	
				// } else {
					// echo "Plik: ".$upfile." nie istnieje<br>";
				// }
			// }
			
			// foreach($mieszkania as $mieszkanie){
				
				// $data = array(
					// 'numer_pietro' => $mieszkanie->id_pietro,
				// );
				// $db->update('inwestycje_powierzchnia', $data, 'id = '.$mieszkanie->id);
	
			// }
			
			// foreach($mieszkania as $mieszkanie){
				
				// foreach($pietra as $pietro){
					
					// if($mieszkanie->numer_pietro == $pietro->numer){
						
						// $data = array(
							// 'id_pietro' => $pietro->id
						// );
						// $db->update('inwestycje_powierzchnia', $data, 'id = '.$mieszkanie->id);

					// }
					
				// }

			// }
			
			
		}


/*
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
*/	

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
			$this->view->pagename = "Nowa strona";
			$this->view->tinymce = "1";
			
			$inwestycja = (int)$this->getRequest()->getParam('inwestycja');

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/strony/id/'.$inwestycja.'/">Wróć do listy stron</a></div>';

			$form = new Form_StronaRealizForm();
			$this->view->form = $form;
			
			$form->getElement('meta_slowa')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_tytul')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_opis')->getDecorator('label')->setOption('escape', false);
			
			$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $inwestycja));
			
			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$status = $this->_request->getPost('status');
					$tekst = $this->_request->getPost('tekst');
					$meta_tytul = $this->_request->getPost('meta_tytul');
					$meta_opis = $this->_request->getPost('meta_opis');
					$meta_slowa = $this->_request->getPost('meta_slowa');
					$tag = zmiana($nazwa);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
					//Pomyslnie
					$data = array(
						'id_inwest' => $inwestycja,
						'nazwa' => $nazwa,
						'status' => $status,
						'typ' => 0,
						'tag_inwest' => $inwest->tag,
						'tekst' => $tekst,
						'meta_slowa' => $meta_slowa,
						'meta_opis' => $meta_opis,
						'meta_tytul' => $meta_tytul,
						'tag' => $tag);
					$db->insert('inwestycje_strony', $data);
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
			$this->view->pagename = "Edytuj stronę: ".$strony->nazwa;

			$form = new Form_StronaRealizForm();
			$this->view->form = $form;
			
			$form->getElement('meta_slowa')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_tytul')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_opis')->getDecorator('label')->setOption('escape', false);
			
			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Załadowanie do forma
			$form->nazwa->setvalue($strony->nazwa);
			$form->status->setvalue($strony->status);
			$form->tekst->setvalue($strony->tekst);
			$form->meta_slowa->setvalue($strony->meta_slowa);
			$form->meta_opis->setvalue($strony->meta_opis);
			$form->meta_tytul->setvalue($strony->meta_tytul);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$status = $this->_request->getPost('status');
					$tekst = $this->_request->getPost('tekst');
					$meta_tytul = $this->_request->getPost('meta_tytul');
					$meta_opis = $this->_request->getPost('meta_opis');
					$meta_slowa = $this->_request->getPost('meta_slowa');
					$tag = zmiana($nazwa);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
					$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $strony->id_inwest));
					
					//Pomyslnie
					$data = array(
						'id_inwest' => $strony->id_inwest,
						'nazwa' => $nazwa,
						'status' => $status,
						'tekst' => $tekst,
						'meta_slowa' => $meta_slowa,
						'meta_opis' => $meta_opis,
						'meta_tytul' => $meta_tytul,
						'typ' => 0,
						'tag_inwest' => $inwest->tag,
						'tag' => $tag);
					$db->update('inwestycje_strony', $data, 'id = '.$id);
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
		
################################################### LOKALIZACJE ###################################################
		
// Pokaz punkty na mapie
		public function lokalizacjaAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');

			$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_lokalizacja')->order('sort ASC')->where('id_inwest =?', $id));
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		}
		
// Usuń punkt
		public function usunPunktAction() {
			$db = Zend_Registry::get('db');
			// Odczytanie id obrazka
			$id = (int)$this->_request->getParam('id');
			$id_inwest = (int)$this->_request->getParam('inwestycja');
			
			$lokalizacja = $db->fetchRow($db->select()->from('inwestycje_lokalizacja')->where('id = ?', $id));
			unlink(FILES_PATH."/inwestycje/lokalizacja/".$lokalizacja->plik);

			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inwestycje_lokalizacja', $where);
			
			$this->_redirect('/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/');
		}
		
// Dodaj punkt na mapie
		public function nowyPunktAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowa lokalizacja";
			$this->view->tinymce = "1";

			$id_inwest = (int)$this->_request->getParam('inwestycja');
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/">Wróć do listy</a></div>';

			// Wyswietl formularz
			$form = new Form_LokalizacjaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów $kategoria, $nazwa, $obrazek
					$nazwa = $this->_request->getPost('nazwa');
					$tekst = $this->_request->getPost('tekst');
					$datadodania = $this->_request->getPost('data');
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$data = array(
							'id_inwest' => $id_inwest,
							'nazwa' => $nazwa,
							'tekst' => $tekst,
						);
						
						$db->insert('inwestycje_lokalizacja', $data);
						$lastId = $db->lastInsertId();
						//Pomyslnie
						if ($obrazek) {

							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/lokalizacja/'.$plik);
							$upfile = FILES_PATH.'/inwestycje/lokalizacja/'.$plik;
							chmod($upfile, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';

							$options = array('jpegQuality' => 80);
							$thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant(370, 250)->save($upfile);

							$dataImg = array('plik' => $plik);
							$db->update('inwestycje_lokalizacja', $dataImg, 'id = '.$lastId);
							
						}
						
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
			$this->_helper->viewRenderer('form', null, true);

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$id_inwest = (int)$this->_request->getParam('inwestycja');
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/">Wróć do listy</a></div>';
			
			$lokalizacja = $this->view->mapa = $db->fetchRow($db->select()->from('inwestycje_lokalizacja')->where('id = ?', $id));
			$this->view->pagename = " - Edytuj lokalizacje: ".$lokalizacja->nazwa;
			$this->view->tinymce = "1";

			$form = new Form_LokalizacjaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Załadowanie do forma
			$form->nazwa->setvalue($lokalizacja->nazwa);
			$form->tekst->setvalue($lokalizacja->tekst);
			$form->obrazek->setvalue($lokalizacja->plik);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$tekst = $this->_request->getPost('tekst');
					$datadodania = $this->_request->getPost('data');
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
						$data = array(
							'id_inwest' => $id_inwest,
							'nazwa' => $nazwa,
							'tekst' => $tekst,
						);
						
					$db->update('inwestycje_lokalizacja', $data, 'id = '.$id);
					
					if ($obrazek) {
						unlink(FILES_PATH."/inwestycje/lokalizacja/".$lokalizacja->plik);
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/lokalizacja/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/lokalizacja/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';

						$options = array('jpegQuality' => 80);
						$thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant(370, 250)->save($upfile);

						$dataImg = array('plik' => $plik);
						$db->update('inwestycje_lokalizacja', $dataImg, 'id = '.$id);
						
					}
						
					
					
					
					$this->_redirect('/admin/inwestycje/lokalizacja/id/'.$id_inwest.'/');

				} else {
						
					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
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

			//Czy masz dostep
			
			
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/news/id/'.$id_inwest.'/">Wróć do listy wpisów</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';

			$form = new Form_NewsForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);
			$this->view->tinymce = "1";
			
			$form->getElement('meta_opis')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_slowa')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_tytul')->getDecorator('label')->setOption('escape', false);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów 
					$status = $this->_request->getPost('status');
					$tytul = $this->_request->getPost('tytul');
					$tekst = $this->_request->getPost('tekst');
					$wprowadzenie = $this->_request->getPost('wprowadzenie');
					$meta_slowa = $this->_request->getPost('meta_slowa');
					$meta_opis = $this->_request->getPost('meta_opis');
					$meta_tytul = $this->_request->getPost('meta_tytul');
					$datadodania = $this->_request->getPost('data');
					$dataimg = date("dmYHs");
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($tytul).'_'.$dataimg.'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();
					
					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
			
						$data = array(
							'id_inwest' => $id_inwest,
							'data' => $datadodania,
							'tytul' => $tytul,
							'wprowadzenie' => $wprowadzenie,
							'tekst' => $tekst,
							'meta_slowa' => $meta_slowa,
							'meta_opis' => $meta_opis,
							'meta_tytul' => $meta_tytul,
							'status' => $status
						);
						
						$db->insert('inwestycje_news', $data);
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

			//Czy masz dostep
			
			
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			
			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/news/id/'.$id_inwest.'/">Wróć do listy wpisów</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';
			
			$form = new Form_NewsForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);
			$this->view->tinymce = "1";
			
			$form->getElement('meta_opis')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_slowa')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_tytul')->getDecorator('label')->setOption('escape', false);

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$wpis = $db->fetchRow($db->select()->from('inwestycje_news')->where('id = ?', $id));
			$this->view->pagename = " - Edytuj wpis: ".$wpis->tytul;

			// Załadowanie do forma
			$form->status->setvalue($wpis->status);
			$form->tytul->setvalue($wpis->tytul);
			$form->data->setvalue($wpis->data);
			$form->obrazek->setvalue($wpis->plik);
			$form->tekst->setvalue($wpis->tekst);
			$form->wprowadzenie->setvalue($wpis->wprowadzenie);
			$form->meta_slowa->setvalue($wpis->meta_slowa);
			$form->meta_opis->setvalue($wpis->meta_opis);
			$form->meta_tytul->setvalue($wpis->meta_tytul);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów $tytul, $data, $obrazek, $wprowadzenie, $tekst
					$status = $this->_request->getPost('status');
					$tytul = $this->_request->getPost('tytul');
					$tekst = $this->_request->getPost('tekst');
					$wprowadzenie = $this->_request->getPost('wprowadzenie');
					$meta_slowa = $this->_request->getPost('meta_slowa');
					$meta_opis = $this->_request->getPost('meta_opis');
					$meta_tytul = $this->_request->getPost('meta_tytul');
					$datadodania = $this->_request->getPost('data');
					$dataimg = date("dmYHs");
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($tytul).'_'.$dataimg.'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
						$data = array(
							'id_inwest' => $id_inwest,
							'data' => $datadodania,
							'tytul' => $tytul,
							'wprowadzenie' => $wprowadzenie,
							'tekst' => $tekst,
							'meta_slowa' => $meta_slowa,
							'meta_opis' => $meta_opis,
							'meta_tytul' => $meta_tytul,
							'status' => $status
						);
						$db->update('inwestycje_news', $data, 'id = '.$id);

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
		
################################################### BUDYNKI ###################################################
		
// Nowy budynek
		public function dodajBudynekAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('budynek', null, true);
			$this->view->pagename = "Nowy budynek";

			$id_inwest = (int)$this->getRequest()->getParam('inwestycja');

			//Czy masz dostep
			
			
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwest.'/">Wróć do listy budynków</a></div>';
			$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwest));

			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div><div class="info">Wymiary pliku z rzutem budynku: <b>1350px</b> szerokości / wysokość wg. wysokości obrazka</div>';
			
			$form = new Form_BudynekForm();
			$this->view->form = $form;

			$form->getElement('zakres_powierzchnia')->getDecorator('label')->setOption('escape', false);
			$form->getElement('zakres_pokoje')->getDecorator('label')->setOption('escape', false);
			$form->getElement('zakres_cen')->getDecorator('label')->setOption('escape', false);
			
			$form->getElement('meta_opis')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_slowa')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_tytul')->getDecorator('label')->setOption('escape', false);

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

			//Odczytanie wartosci z inputów
			$nazwa = $this->_request->getPost('nazwa');
			$numer = $this->_request->getPost('numer');
			$meta_slowa = $this->_request->getPost('meta_slowa');
			$meta_opis = $this->_request->getPost('meta_opis');
			$meta_tytul = $this->_request->getPost('meta_tytul');
			$zakres_powierzchnia = $this->_request->getPost('zakres_powierzchnia');
			$zakres_pokoje = $this->_request->getPost('zakres_pokoje');
			$zakres_cen = $this->_request->getPost('zakres_cen');
			$tag = zmiana($nazwa);
			$cords = $this->_request->getPost('cords');
			$html = $this->_request->getPost('html');
			$dzisiaj = date("YmdHis");
			$obrazek = $_FILES['obrazek']['name'];
			$plik = zmiana($nazwa).'_'.$dzisiaj.'.'.zmiennazwe($obrazek);
			$formData = $this->_request->getPost();

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
					$db->insert('inwestycje_budynki', $data);
					$lastId = $db->lastInsertId();

					if ($obrazek) {
						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/budynek/'.$plik);
						$upfile = FILES_PATH.'/inwestycje/budynek/'.$plik;
						chmod($upfile, 0755);
						require_once 'kCMS/Thumbs/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create($upfile)->resize(1350, 1350)->save($upfile);

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

			//Czy masz dostep
			
			
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwest));
			$this->view->pietro = $budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id = ?', $id_budynek));
			$this->view->pagename = "Edytuj budynek: ".$budynek->nazwa;

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwest.'/">Wróć do listy budynków</a></div>';
			$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwest));
			
			$form = new Form_BudynekForm();
			$this->view->form = $form;
			
			$form->getElement('zakres_powierzchnia')->getDecorator('label')->setOption('escape', false);
			$form->getElement('zakres_pokoje')->getDecorator('label')->setOption('escape', false);
			$form->getElement('zakres_cen')->getDecorator('label')->setOption('escape', false);
			
			$form->getElement('meta_opis')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_slowa')->getDecorator('label')->setOption('escape', false);
			$form->getElement('meta_tytul')->getDecorator('label')->setOption('escape', false);

			
			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Załadowanie do forma
			$form->nazwa->setvalue($budynek->nazwa);
			$form->numer->setvalue($budynek->numer);
			$form->cords->setvalue($budynek->cords);
			$form->meta_slowa->setvalue($budynek->meta_slowa);
			$form->meta_opis->setvalue($budynek->meta_opis);
			$form->meta_tytul->setvalue($budynek->meta_tytul);
			$form->html->setvalue($budynek->html);
			$form->obrazek->setvalue($budynek->plik);
			$form->zakres_pokoje->setvalue($budynek->zakres_pokoje);
			$form->zakres_powierzchnia->setvalue($budynek->zakres_powierzchnia);
			$form->zakres_cen->setvalue($budynek->zakres_cen);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$numer = $this->_request->getPost('numer');
					$meta_slowa = $this->_request->getPost('meta_slowa');
					$meta_opis = $this->_request->getPost('meta_opis');
					$meta_tytul = $this->_request->getPost('meta_tytul');
					$zakres_powierzchnia = $this->_request->getPost('zakres_powierzchnia');
					$zakres_pokoje = $this->_request->getPost('zakres_pokoje');
					$zakres_cen = $this->_request->getPost('zakres_cen');
					$tag = zmiana($nazwa);
					$cords = $this->_request->getPost('cords');
					$html = $this->_request->getPost('html');
					$dzisiaj = date("YmdHis");
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'_'.$dzisiaj.'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {

						$data = array(
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
					
						$db->update('inwestycje_budynki', $data, 'id = '.$id_budynek);
						
						if ($obrazek) {

							unlink(FILES_PATH."/inwestycje/budynek/".$budynek->plik);
							
							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/budynek/'.$plik);
							$upfile = FILES_PATH.'/inwestycje/budynek/'.$plik;
							chmod($upfile, 0755);
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';
							$thumb = PhpThumbFactory::create($upfile)->resize(1350, 1350)->save($upfile);
	
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

################################################### Domki ###################################################

// Pokaz pomieszczenia dla danego pietra
		public function pokazDomAction() {
			$db = Zend_Registry::get('db');

		}
 
// Dodaj pomieszczenie
		public function dodajDomAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('dom', null, true);

			$this->view->pagename = "Dodaj dom";

			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div>';
			
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');

			if($id_inwestycja) {
				$this->view->inwestycja = $inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwestycja));
			}

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id.'/">Wróć do listy domów</a></div>';
			
			$form = new Form_DomForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);
			
			$form->getElement('szukaj_metry')->getDecorator('label')->setOption('escape', false);
			$form->getElement('szukaj_cena')->getDecorator('label')->setOption('escape', false);

			//Akcja po wcisnieciu Submita
			if ($this->_request->getPost()) {

				//Odczytanie wartosci z inputów
				$nazwa = $this->_request->getPost('nazwa');
				$numer = $this->_request->getPost('numer');
				$cords = $this->_request->getPost('cords');
				$balkon = $this->_request->getPost('balkon');
				$promocja = $this->_request->getPost('promocja');
				$taras = $this->_request->getPost('taras');
				$pokoje = $this->_request->getPost('pokoje');
				$material = $this->_request->getPost('material');
				$typ_okno = $this->_request->getPost('typ_okno');
				$ogrzewanie = $this->_request->getPost('ogrzewanie');
				$html = $this->_request->getPost('html');
				$status = $this->_request->getPost('status');
				$metry = $this->_request->getPost('metry');
				$szukaj_metry = $this->_request->getPost('szukaj_metry');
				$cena = $this->_request->getPost('cena');
				$kuchnia = $this->_request->getPost('kuchnia');
				
				$cena_promocja = $this->_request->getPost('cena_promocja');
				$cena_m = $this->_request->getPost('cena_m');
				$cena_m_promocja = $this->_request->getPost('cena_m_promocja');
				
				$cena_netto = $this->_request->getPost('cena_netto');
				$szukaj_cena = $this->_request->getPost('szukaj_cena');
				$mdm = $this->_request->getPost('mdm');
				$media = $this->_request->getPost('media');
				$media = implode(',', $media);
				$zabezpieczenia = $this->_request->getPost('zabezpieczenia');
				$zabezpieczenia = implode(',', $zabezpieczenia);
				$info_dodatkowe = $this->_request->getPost('info_dodatkowe');
				$info_dodatkowe = implode(',', $info_dodatkowe);
				$okno = $this->_request->getPost('okno');
				$okno = implode(',', $okno);
				
				//$stan = $this->_request->getPost('stan');
				
				$dzisiaj = date("YmdHis");
				
				$obrazek = $_FILES['obrazek']['name'];
				if($obrazek){
					$plik = zmiana($nazwa).'_'.$dzisiaj.'.'.zmiennazwe($obrazek);
				}
				
				$obrazek2 = $_FILES['obrazek2']['name'];
				if($obrazek2){
					$plik2 = zmiana($nazwa).'_k'.$dzisiaj.'.'.zmiennazwe($obrazek2);
				}

				$pdfile = $_FILES['pdf']['name'];
				if($pdfile) {
					$pdf = zmiana($nazwa).'_'.$dzisiaj.'.'.zmiennazwe($pdfile);
				}
				
				$tag = zmiana($nazwa);
				$formData = $this->_request->getPost();

				//Sprawdzenie poprawnosci forma
				if ($form->isValid($formData)) {

					//Pomyslnie
					$data = array(
						'id_inwest' => $id_inwestycja,
						'pokoje' => $pokoje,
						'okno' => $okno,
						'material' => $material,
						'balkon' => $balkon,
						'taras' => $taras,
						'typ_okno' => $typ_okno,
						'ogrzewanie' => $ogrzewanie,
						'nazwa' => $nazwa,
						'mdm' => $mdm,
						'numer' => $numer,
						'cords' => $cords,
						'html' => base64_encode($html),
						'status' => $status,
						'typ' => 5,
						'metry' => $metry,
						'szukaj_metry' => $szukaj_metry,
						'cena' => $cena,
						'cena_promocja' => $cena_promocja,
						'cena_m' => $cena_m,
						'cena_m_promocja' => $cena_m_promocja,
						'promocja' => $promocja,
						'szukaj_cena' => $szukaj_cena,
						'cena_netto' => $cena_netto,
						'tag' => $tag,
						'media' => $media,
						'zabezpieczenia' => $zabezpieczenia,
						'info_dodatkowe' => $info_dodatkowe,
						'kuchnia' => $kuchnia,
						//'stan' => $stan,
						'galeria' => 1
					);
					$db->insert('inwestycje_powierzchnia', $data);
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
		
// Edytuj
		public function edytujDomAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('dom', null, true);
			$this->view->tinymce = "1";
			
			$this->view->pagename = "Edytuj dom";

			$this->view->info = '<div class="info">Aby przybliżyć obrazek użyj skrótu CTRL i +, aby oddalić CTRL i -, aby wyzerować przybliżenie CTRL i 0. Trzymając kursor na obrazku obiektu możesz przesuwać nim na boki używając rolki w myszce.</div>';
			
			$id = (int)$this->_request->getParam('id');
			$id_inwestycja = (int)$this->getRequest()->getParam('inwestycja');

			if($id_inwestycja){
				$this->view->inwestycja = $inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $id_inwestycja));
			}
			if($id) {
				$this->view->powierzchnia = $powierzchnia = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('id = ?', $id));
			}

			$this->view->back = '<div class="back"><a href="'.$this->view->baseUrl.'/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/">Wróć do listy domów</a></div>';
			
			$form = new Form_DomForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);
			
			$form->getElement('szukaj_metry')->getDecorator('label')->setOption('escape', false);
			$form->getElement('szukaj_cena')->getDecorator('label')->setOption('escape', false);

			// Załadowanie do forma 
			$form->nazwa->setvalue($powierzchnia->nazwa);
			$form->numer->setvalue($powierzchnia->numer);
			$form->pokoje->setvalue($powierzchnia->pokoje);
			$oknoArray = explode(',',$powierzchnia->okno);
			$form->okno->setvalue($oknoArray);
			$form->typ_okno->setvalue($powierzchnia->typ_okno);
			//$form->stan->setvalue($powierzchnia->stan);
			$form->ogrzewanie->setvalue($powierzchnia->ogrzewanie);
			$form->cords->setvalue($powierzchnia->cords);
			$form->html->setvalue(base64_decode($powierzchnia->html));
			$form->status->setvalue($powierzchnia->status);
			$form->metry->setvalue($powierzchnia->metry);
			$form->balkon->setvalue($powierzchnia->balkon);
			$form->taras->setvalue($powierzchnia->taras);
			$form->promocja->setvalue($powierzchnia->promocja);
			$form->mdm->setvalue($powierzchnia->mdm);
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
			$form->cena_netto->setvalue($powierzchnia->cena_netto);
			$form->obrazek->setvalue($powierzchnia->plik);
			$form->obrazek2->setvalue($powierzchnia->plik2);
			$form->pdf->setvalue($powierzchnia->pdf);
			
			$form->cena_promocja->setvalue($powierzchnia->cena_promocja);
			$form->cena_m->setvalue($powierzchnia->cena_m);
			$form->cena_m_promocja->setvalue($powierzchnia->cena_m_promocja);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$nazwa = $this->_request->getPost('nazwa');
					$numer = $this->_request->getPost('numer');
					$cords = $this->_request->getPost('cords');
					$balkon = $this->_request->getPost('balkon');
					$taras = $this->_request->getPost('taras');
					$pokoje = $this->_request->getPost('pokoje');
					$material = $this->_request->getPost('material');
					$typ_okno = $this->_request->getPost('typ_okno');
					$promocja = $this->_request->getPost('promocja');
					$ogrzewanie = $this->_request->getPost('ogrzewanie');
					$html = $this->_request->getPost('html');
					$status = $this->_request->getPost('status');
					$metry = $this->_request->getPost('metry');
					$szukaj_metry = $this->_request->getPost('szukaj_metry');
					$cena = $this->_request->getPost('cena');
					$kuchnia = $this->_request->getPost('kuchnia');
					
					$cena_promocja = $this->_request->getPost('cena_promocja');
					$cena_m = $this->_request->getPost('cena_m');
					$cena_m_promocja = $this->_request->getPost('cena_m_promocja');
					
					$cena_netto = $this->_request->getPost('cena_netto');
					$szukaj_cena = $this->_request->getPost('szukaj_cena');
					$mdm = $this->_request->getPost('mdm');
					$media = $this->_request->getPost('media');
					$media = implode(',', $media);
					$zabezpieczenia = $this->_request->getPost('zabezpieczenia');
					$zabezpieczenia = implode(',', $zabezpieczenia);
					$info_dodatkowe = $this->_request->getPost('info_dodatkowe');
					$info_dodatkowe = implode(',', $info_dodatkowe);
					$okno = $this->_request->getPost('okno');
					$okno = implode(',', $okno);
					
					//$stan = $this->_request->getPost('stan');
					
					$dzisiaj = date("YmdHis");
					
					$obrazek = $_FILES['obrazek']['name'];
					if($obrazek){
						$plik = zmiana($nazwa).'_'.$dzisiaj.'.'.zmiennazwe($obrazek);
					}
				
					$obrazek2 = $_FILES['obrazek2']['name'];
					if($obrazek2){
						$plik2 = zmiana($nazwa).'_k'.$dzisiaj.'.'.zmiennazwe($obrazek2);
					}

					$pdfile = $_FILES['pdf']['name'];
					if($pdfile) {
						$pdf = zmiana($nazwa).'_'.$dzisiaj.'.'.zmiennazwe($pdfile);
					}
					
					$tag = zmiana($nazwa);
					$formData = $this->_request->getPost();
					
						//Sprawdzenie poprawnosci forma
						if ($form->isValid($formData)) {

								$data = array(
									'id_inwest' => $id_inwestycja,
									'pokoje' => $pokoje,
									'okno' => $okno,
									'promocja' => $promocja,
									'material' => $material,
									'balkon' => $balkon,
									'taras' => $taras,
									'typ_okno' => $typ_okno,
									'ogrzewanie' => $ogrzewanie,
									'nazwa' => $nazwa,
									'mdm' => $mdm,
									'numer' => $numer,
									'cords' => $cords,
									'html' => base64_encode($html),
									'status' => $status,
									'typ' => 5,
									'metry' => $metry,
									'szukaj_metry' => $szukaj_metry,
									'cena' => $cena,
									'cena_promocja' => $cena_promocja,
									'cena_m' => $cena_m,
									'cena_m_promocja' => $cena_m_promocja,
									'szukaj_cena' => $szukaj_cena,
									'cena_netto' => $cena_netto,
									'tag' => $tag,
									'media' => $media,
									'zabezpieczenia' => $zabezpieczenia,
									'info_dodatkowe' => $info_dodatkowe,
									'kuchnia' => $kuchnia,
									//'stan' => $stan
								);
								
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
								$db->update('inwestycje_powierzchnia', $data, 'id = '.$id);

							
							$this->_redirect('/admin/inwestycje/pokaz/id/'.$id_inwestycja.'/');


						} else {

							//Wyswietl bledy	
							$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
							$form->populate($formData);

						}

				}
		}

// Usun pomieszczenie 
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

################################################### WYSZUKIWARKA ###################################################

// Wyszukiwarka
		public function wyszukiwarkaAction() {
			$db = Zend_Registry::get('db');
			

			//Ustawienia główne strony
			if ($this->_request->getPost('submitSearch', false)) {

				$mieszkania_pokoje = $this->_request->getPost('mieszkania_pokoje');
				$mieszkania_metry = $this->_request->getPost('mieszkania_metry');
				$mieszkania_cena = $this->_request->getPost('mieszkania_cena');

				$lokale_metry = $this->_request->getPost('lokale_metry');
				$lokale_cena = $this->_request->getPost('lokale_cena');

				$data = array(
					'mieszkania_pokoje' => $mieszkania_pokoje,
					'mieszkania_metry' => $mieszkania_metry,
					'mieszkania_cena' => $mieszkania_cena,
					'lokale_metry' => $lokale_metry,
					'lokale_cena' => $lokale_cena
				);
				
				$db->update('ustawienia', $data);
				$this->_redirect('/admin/inwestycje/wyszukiwarka/');
			}
		
		}

// Wyszukiwarka mieszkan
		public function szukajMieszkanieAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');

			//Czy masz dostep

			
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
		
################################################### OFERTA ###################################################

// Pokaz wszystkie mieszkania na sprzedaz
		public function ofertaAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');

			//Czy masz dostep

			
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			$this->view->lista = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('status = ?', 1)->order('sort ASC')->where('id_inwest = ?', $id));
		} 

// Pokaz wszystkie mieszkania na sprzedaz
		public function ofertaWyslanaAction() {
			$db = Zend_Registry::get('db');
			$this->view->lista = $db->fetchAll($db->select()->from('oferta')->order('id DESC'));
			$id = (int)$this->_request->getParam('id');

			//Czy masz dostep

			
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		} 

// Pokaz wyslana oferte
		public function ofertaPokazAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$this->view->oferta = $db->fetchRow($db->select()->from('oferta')->order('id DESC')->where('id = ?', $id));

			$id = (int)$this->_request->getParam('inwest');

			//Czy masz dostep

			
			$this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
		} 

// Formularz wysylki oferty
		public function ofertaWyslijAction() {
			$db = Zend_Registry::get('db');
			$ustawienia = $db->fetchRow($db->select()->from('ustawienia'));

			$id = (int)$this->_request->getParam('inwest');

			//Czy masz dostep

			
			$this->view->inwestycja = $inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));
			
			$lista = $this->view->lista = $db->fetchAll($db->select()->from('inwestycje_powierzchnia', array('id', 'id_inwest', 'numer_pietro', 'numer', 'plik', 'plik2', 'metry', 'pokoje', 'nazwa', 'id_budynek', 'cena', 'cena_promocja'))->where('status = ?', 1)->where('id IN (?)', $_GET[checkbox])->order('sort ASC'));
			
			// echo '<pre>';
			// print_r($lista);
			// echo '</pre>';

			if ($this->_request->isPost()) {
	
				$mieszkanieSend = '';
				$mieszkanieSend .= '<h2 style="margin-top:30px">'.$inwestycja->nazwa.'</h2>';
				$mieszkanieSend .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">';
				$mieszkanieSend .= '<thead>';
				$mieszkanieSend .= '<tr>';
				$mieszkanieSend .= '<th style="background:#4E5A64;color:white;font-size:13px;padding:10px">Nazwa</th>';
				$mieszkanieSend .= '<th style="background:#4E5A64;color:white;font-size:13px;padding:10px">Powierzchnia</th>';
				$mieszkanieSend .= '<th style="background:#4E5A64;color:white;font-size:13px;padding:10px">Pokoje</th>';
				$mieszkanieSend .= '<th style="background:#4E5A64;color:white;font-size:13px;padding:10px">Cena</th>';
				$mieszkanieSend .= '<th style="background:#4E5A64;color:white;font-size:13px;padding:10px">Cena promocyjna</th>';
				$mieszkanieSend .= '<th style="background:#4E5A64;color:white;font-size:13px;padding:10px"></th>';
				$mieszkanieSend .= '<tr>';
				$mieszkanieSend .= '</thead>';
				$mieszkanieSend .= '<tbody>';
foreach($lista as $powierzchnia){

$inwest = $inwestycja->tag;
$inwest_typ = $inwestycja->typ;

if($inwest_typ == 1) {
				$mieszkanieSend .= '<tr data-id="'.$powierzchnia->numer.'" data-url="'.$this->baseUrl.'/'.$powierzchnia->lang.'/'.$inwest.'/b/'.$powierzchnia->id_budynek.'/p/'.$powierzchnia->numer_pietro.'/m/'.$powierzchnia->numer.'.html">';
}
if($inwest_typ == 2) {
				$mieszkanieSend .= '<tr data-id="'.$powierzchnia->numer.'" data-url="'.$this->baseUrl.'/'.$powierzchnia->lang.'/'.$inwest.'/p/'.$powierzchnia->numer_pietro.'/m/'.$powierzchnia->numer.'.html">';
}
if($inwest_typ == 3) {
				$mieszkanieSend .= '<tr data-id="'.$powierzchnia->numer.'" data-url="'.$this->baseUrl.'/'.$powierzchnia->lang.'/'.$inwest.'/d/'.$powierzchnia->numer.'.html">';
}
					$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center">'.$powierzchnia->nazwa.'</td>';
					$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center">'.$powierzchnia->metry.'m<sup>2</sup></td>';
					$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center">'.$powierzchnia->pokoje.'</td>';
					$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center">'.number_format($powierzchnia->cena , 0, ',', '.').'zł</td>';
					$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center">'.number_format($powierzchnia->cena_promocja , 0, ',', '.').'zł</td>';

if($inwest_typ == 1) {
				$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center"><a href="'.$this->baseUrl.'/'.$powierzchnia->lang.'/'.$inwest.'/b/'.$powierzchnia->id_budynek.'/p/'.$powierzchnia->numer_pietro.'/m/'.$powierzchnia->numer.'.html">Zobacz mieszkanie</a></td>';
}
if($inwest_typ == 2) {
				$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center"><a href="'.$this->baseUrl.'/'.$powierzchnia->lang.'/'.$inwest.'/p/'.$powierzchnia->numer_pietro.'/m/'.$powierzchnia->numer.'.html">Zobacz mieszkanie</a></td>';
}
if($inwest_typ == 3) {
				$mieszkanieSend .= '<td style="font-size:13px;padding:10px;border-bottom:1px solid #dbdbdb;text-align:center"><a href="'.$this->baseUrl.'/'.$powierzchnia->lang.'/'.$inwest.'/d/'.$powierzchnia->numer.'.html">Zobacz dom</a></td>';
}
}
				$mieszkanieSend .= '</tbody>';
				$mieszkanieSend .= '</table>';
				$mieszkanieSend .= '';

				$email = $this->_request->getPost('odbiorca');
				$temat = $this->_request->getPost('temat');
				$tresc = $this->_request->getPost('tresc');
				$imie = $this->_request->getPost('imie');
	
				$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
				$message = '<html>';
				$message .= '<head>';
				$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
				$message .= '<meta name="viewport" content="width=device-width, initial scale=1.0"/>';
				$message .= '<meta name="format-detection" content="telephone=no">';
				$message .= '<style type="text/css">';
				$message .= '.ExternalClass {width:100%;}';
				$message .= 'img {display: block;}';
				$message .= 'table {border-collapse:collapse}';
				$message .= 'table td {border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;padding:0}';
				$message .= 'img{-ms-interpolation-mode: bicubic;} body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%;}';
				$message .= 'font, a {text-decoration: none !important}';
				$message .= '</style>';
				$message .= '<title></title>';
				$message .= '</head>';
				$message .= '<body style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background:#f2f2f2;padding:0;margin:0;text-align:center">';
				$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center"><tr><td>';
				$message .= '<table border="0" cellpadding="0" cellspacing="0" width="610" style="background-color:white" align="center">';
				$message .= '<tr><td style="text-align:center;background:#c4c7ce" align="center">Obrazek nagłówka</td></tr>';
				
				$message .= '<tr><td style="padding:30px 30px 0;color:black;font-family:Arial;font-size:18px;line-height:20px;text-align:left;width:550px" width="550"><font style="font-family:Arial;font-size:18px;line-height:20px">Witaj <b>'.$imie.'</b></td></tr>';
				$message .= '<tr><td style="padding:15px 30px 30px;color:black;font-family:Arial;font-size:14px;line-height:22px;text-align:left;width:550px" width="550"><font style="font-family:Arial;font-size:14px;line-height:20px">'.$tresc.'</td></tr>';
				
				$message .= '<tr><td style="padding:0 30px;color:black;font-family:Arial;font-size:17px;line-height:24px;text-align:left;width:550px" width="550">'.$mieszkanieSend.'</td></tr>';
				$message .= '<tr><td width="610" style="background:#2C2F38"><table border="0" cellpadding="0" cellspacing="0" width="610"><tr><td style="font-family:Arial;font-size:12px;line-height:18px;color:#fff;text-align:left;padding:30px 0 30px 30px">Stopka</td></tr></table></td></tr>';
				$message .= '</table>';
				$message .= '</td></tr></table>';
				$message .= '</body>';
				$message .= '</html>';

				$datadodania = date("d.m.Y");
				$podziekowanie = new Zend_Mail('UTF-8');
				$podziekowanie
				->setFrom($ustawienia->email, $ustawienia->nazwa)
				->addTo($email, 'Adres odbiorcy')
				->setSubject($temat)
				->setBodyHTML($message)
				->send();
				
					$ids_oferta = implode(',', $_GET[checkbox]);
					$stat = array(
						'klient' => $imie,
						'temat' => $temat,
						'email' => $email,
						'oferta' => $ids_oferta,
						'oferta_tekst' => $mieszkanieSend,
						'data' => date("d.m.Y - H:i:s"),
						'godz' => date("H"),
						'dzien' => date("d"),
						'msc' => date("m"),
						'rok' => date("Y"),
						'wiadomosc' => $tresc,
						'ip' => $_SERVER['REMOTE_ADDR']
					);
					$db->insert('oferta', $stat);	
						
					$this->_redirect('/admin/inwestycje/oferta-wyslana/id/'.$inwestycja->id);	
						
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
			$this->view->info = '<div class="info">Obrazek o wymiarach: szerokość <b>110</b>px / wysokość <b>110</b>px</div>';

			$form = new Form_NazwaSubPlikForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów 
					$nazwa = $this->_request->getPost('nazwa');
					$subnazwa = $this->_request->getPost('subnazwa');
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();
					
					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
			
						$data = array(
							'id_inwest' => $id_inwest,
							'nazwa' => $nazwa,
							'subnazwa' => $subnazwa
						);
	
					$db->insert('inwestycje_atut', $data);
					$lastId = $db->lastInsertId();
			
					//Pomyslnie
					if ($obrazek) {

						move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/atuty/'.$plik);
						$upfile = FILES_PATH.'/atuty/'.$plik;
						chmod($upfile, 0755);
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
			$this->view->info = '<div class="info">Obrazek o wymiarach: szerokość <b>110</b>px / wysokość <b>110</b>px</div>';

			$form = new Form_NazwaSubPlikForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Odczytanie id
			$this->view->pagename = " - Edytuj wpis: ".$wpis->nazwa;

			// Załadowanie do forma
			$form->nazwa->setvalue($wpis->nazwa);
			$form->subnazwa->setvalue($wpis->subnazwa);
			$form->obrazek->setvalue($wpis->ikona);


				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów $tytul, $data, $obrazek, $wprowadzenie, $tekst
					$nazwa = $this->_request->getPost('nazwa');
					$subnazwa = $this->_request->getPost('subnazwa');
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($nazwa).'.'.zmiennazwe($obrazek);
					$formData = $this->_request->getPost();
					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
					
						$data = array(
							'id_inwest' => $id_inwest,
							'nazwa' => $nazwa,
							'subnazwa' => $subnazwa
						);
						
						$db->update('inwestycje_atut', $data, 'id = '.$id);
						if ($obrazek) {
							//Usuwanie starych zdjęć
							unlink(FILES_PATH."/inwestycje/atuty/".$wpis->ikona);

							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/atuty/'.$plik);
							$upfile = FILES_PATH.'/inwestycje/atuty/'.$plik;
							chmod($upfile, 0755);
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
}
