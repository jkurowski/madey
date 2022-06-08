<?php

class Admin_ParalaxaController extends kCMS_Admin
{
		
		public function preDispatch() {
			$this->view->controlname = "Paralaxa";
			
			$this->duzaszerokosc = '1920';
			$this->duzawysokosc = '1080';
			$this->sql_table = 'paralaxa';
			$this->backto = '/admin/paralaxa/';
		}

// Pokaz wszystkie wpisy
		public function indexAction() {
			$db = Zend_Registry::get('db');
			$this->view->lista = $db->fetchAll($db->select()->from($this->sql_table));
		}

// Edytuj wpis
		public function edytujAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);

			$this->view->back = '<div class="back"><a href="'.$this->backto.'">Wróć do listy</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';
			
			$form = new Form_ParalaxaForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$wpis = $db->fetchRow($db->select()->from($this->sql_table)->where('id = ?', $id));
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

						//Sprawdzenie poprawnosci forma
						if ($form->isValid($formData)) {
						
							$db->update($this->sql_table, $formData, 'id = '.$id);

							if ($obrazek) {
								//Usuwanie starych zdjęć
								unlink(FILES_PATH."/paralaxa/".$wpis->plik);
								
								move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/paralaxa/'.$plik);
								$upfile = FILES_PATH.'/paralaxa/'.$plik;
								chmod($upfile, 0755);
								
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';

								$options = array('jpegQuality' => 90);
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
}