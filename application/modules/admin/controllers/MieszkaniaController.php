<?php

class Admin_MieszkaniaController extends kCMS_Admin
{
		
		public function preDispatch() {
			$this->view->controlname = "Mieszkania";
			
			$this->duzaszerokosc = '350';
			$this->duzawysokosc = '350';
			$this->sql_table = 'mieszkania';
			$this->backto = '/admin/mieszkania/';
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
		
// Pokaz wszystkie wpisy
		public function indexAction() {
			$db = Zend_Registry::get('db');
			$this->view->lista = $db->fetchAll($db->select()->from($this->sql_table)->order('sort ASC'));
		}
		
// Dodaj nowy wpis
		public function nowaAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowa usługa";
			$this->view->tinymce = "1";

			$this->view->back = '<div class="back"><a href="'.$this->backto.'">Wróć do listy</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';

			$form = new Form_MieszkaniaForm();
			$this->view->form = $form;

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$obrazek = $_FILES['obrazek']['name'];
					$plik = zmiana($formData['nazwa']).'.'.zmiennazwe($obrazek);
					$formData['tag'] = zmiana($formData['nazwa']);

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
			
						$db->insert($this->sql_table, $formData);
						$lastId = $db->lastInsertId();

						if ($obrazek) {

							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/mieszkania/'.$plik);
							$upfile = FILES_PATH.'/mieszkania/'.$plik;
							chmod($upfile, 0755);
							
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';

							$options = array('jpegQuality' => 80);
							$thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant($this->duzaszerokosc, $this->duzawysokosc)->save($upfile);

							$dataImg = array('plik' => $plik);
							$db->update($this->sql_table, $dataImg, 'id = '.$lastId);
							
						}

						$this->_redirect($this->backto);

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
// Edytuj wpis
		public function edytujAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->tinymce = "1";
			
			$this->view->back = '<div class="back"><a href="'.$this->backto.'">Wróć do listy</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';
			
			$form = new Form_MieszkaniaForm();
			$this->view->form = $form;

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$wpis = $db->fetchRow($db->select()->from($this->sql_table)->where('id = ?', $id));
			$this->view->pagename = " - Edytuj usługę: ".$wpis->tytul;

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
					$formData['tag'] = zmiana($formData['nazwa']);

						//Sprawdzenie poprawnosci forma
						if ($form->isValid($formData)) {
						
							$db->update($this->sql_table, $formData, 'id = '.$id);

							if ($obrazek) {
								//Usuwanie starych zdjęć
								unlink(FILES_PATH."/mieszkania/".$wpis->plik);
								
								move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/mieszkania/'.$plik);
								$upfile = FILES_PATH.'/mieszkania/'.$plik;
								chmod($upfile, 0755);
								
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';

								$options = array('jpegQuality' => 80);
								$thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant($this->duzaszerokosc, $this->duzawysokosc)->save($upfile);

								$dataImg = array('plik' => $plik);
								$db->update($this->sql_table, $dataImg, 'id = '.$id);
								
							}
							
							$this->_redirect($this->backto);
							
						} else {
											
							//Wyswietl bledy	
							$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
							$form->populate($formData);

						}

			}
		}

// Usun wpis
		public function usunAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$wpis = $db->fetchRow($db->select()->from($this->sql_table)->where('id = ?', $id));
			
			unlink(FILES_PATH."/mieszkania/".$wpis->plik);
			
			$db->delete($this->sql_table, 'id = '.$id);
			$this->_redirect($this->backto);
		}

}