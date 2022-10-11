<?php
class Default_IndexController extends kCMS_Site
{
		public function preDispatch() {
			
			function statusMieszkaniaListaTag($numer){
				switch ($numer) {
					case '1':
						return "dostepny";
					case '2':
						return "sprzedany";
					case '3':
						return "rezerwacja";
					case '4':
						return "wynajete";
				}
			}

			function statusMieszkania($numer){
				switch ($numer) {
					case '1':
						return "Dostępne";
					case '2':
						return "Sprzedane";
					case '3':
						return "Rezerwacja";
					case '4':
						return "Wynajęte";
				}
			}
			
		}

// Ustaw kolejność
		public function sortujAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$db = Zend_Registry::get('db');
			$tabela = $this->_request->getParam('table');
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {
				$data = array('sort' => $listingCounter);
				$db->update($tabela, $data, 'id = '.$recordIDValue);
				$listingCounter = $listingCounter + 1;
				}
		}
	
		public function indexAction() {
			$this->_helper->viewRenderer->setNoRender(true);
			$db = Zend_Registry::get('db');

			$this->view->slider = $db->fetchAll($db->select()->from('slider')->order('sort ASC'));
		
			//Pobranie regulek
			$url = 'https://madey.developro.pl/api/regulki/';
			$login = 'admin';
			$password = 'admin';
			$id = 1;
			$key = "s8as8dfy8a7sdf";
		 
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$id.'&key='.$key);
			curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			
			$regulkiCURL = curl_exec($ch);  
			$this->view->regulkis = $db->fetchAll($db->select()->from('rodo_regulki')->order('sort ASC')->where('status = ?', 1));
		
			$this->view->rodo = $db->fetchRow($db->select()->from('rodo_ustawienia')->where('id =?', 1));
			$this->view->paralaxa = $db->fetchRow($db->select()->from('paralaxa')->where('id =?', 1));
			
			$this->view->lista = $db->fetchAll($db->select()->from('przykladowe')->order('sort ASC'));
			
			$this->view->boksy = $db->fetchAll($db->select()->from('minislider')->order('sort ASC')->where('typ = ?', 1));
            $inwest = $db->select()
                ->from('inwestycje', array('nazwa', 'inwestycja_plik', 'tag', 'status', 'id', 'lista'))
                ->order('sort ASC');
            $this->view->inwestycje = $db->fetchAll($inwest);

				if ($this->_request->isPost()) {

					$ip = $_SERVER['REMOTE_ADDR'];
					$adresip = $db->fetchRow($db->select()->from('blokowanie')->where('ip = ?', $ip));
					
					$grecaptcha = $this->_request->getPost('g-recaptcha-response');
					$secret = '6LcbhL8UAAAAAN5gd0KCO5MI8rrNMRRqHYFCe2ow';
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$grecaptcha);
					$responseData = json_decode($verifyResponse);
		
					if($responseData->success){
						
					if(!$adresip) {

						$imie = $this->_request->getPost('form_imie');
						$email = $this->_request->getPost('form_mail');
						$temat = $this->_request->getPost('form_temat');
						$telefon = $this->_request->getPost('form_telefon');
						$wiadomosc = $this->_request->getPost('form_wiadomosc');
						$useremail = $this->_request->getPost('useremail');
						$ip = $_SERVER['REMOTE_ADDR'];
						$datadodania = date("d.m.Y - H:i:s");

						$ustawienia = $db->fetchRow($db->select()->from('ustawienia'));

						if(!$useremail) {	
							$mail = new Zend_Mail('UTF-8');
							$mail
							->setFrom($ustawienia->email, 'Zapytanie ze strony www')
							->addTo($ustawienia->email, 'Adres odbiorcy')
							->setReplyTo($email, $imie)
							->setSubject($ustawienia->domena.' - Zapytanie ze strony www - Strona główna')
							->setBodyHTML('
							<div style="width:550px;border:1px solid #ececec;padding:0 20px;margin:0 auto;font-family:Arial;font-size:14px;line-height:27px">
							<p style="text-align:center">'.$ustawienia->nazwa.'</p>
							<p><b>Wiadomość wysłana: '. $datadodania .'</b></p>
							<hr style="border:0;border-bottom:1px solid #ececec" />
							<p><b>Imię i nazwisko:</b> '.$imie.'<br />
							<b>E-mail:</b> '. $email .'<br />
							<b>Telefon:</b> '. $telefon .'<br />
							<b>IP:</b> '. $ip .'<br />
							<hr style="border:0;border-bottom:1px solid #ececec" />
							<p style="margin-top:0">'. $wiadomosc .'</p>
							</div>')
							->send();
						}
						$this->view->message = '1';
						
						$stat = array(
							'akcja' => 1,
							'miejsce' => 4,
							'data' => date("d.m.Y - H:i:s"),
							'timestamp' => date("d-m-Y"),
							'godz' => date("H"),
							'dzien' => date("d"),
							'msc' => date("m"),
							'rok' => date("Y"),
							'tekst' => $wiadomosc,
							'email' => $email,
							'telefon' => $telefon,
							'ip' => $_SERVER['REMOTE_ADDR']
						);
						$db->insert('statystyki', $stat);
		
						$formData = $this->_request->getPost();
						$formData['data_dodania'] = date("d-m-Y H:s");
						$formData['miasto'] = 54;
						$formData['id_handlowiec'] = 56;
						$formData['status'] = 55;
						$formData['id_inwest'] = 1;
						$formData['ip'] = $_SERVER['REMOTE_ADDR'];
						$formData['www'] = $_SERVER['REQUEST_URI'];
						$formData['imie'] = $formData['form_imie'];
						unset($formData['form_imie']);
						$formData['email'] = $formData['form_mail'];
						unset($formData['form_mail']);
						$formData['telefon'] = $formData['form_telefon'];
						unset($formData['form_telefon']);
						
						unset($formData['g-recaptcha-response']); 

						foreach ($formData as $key => $value) {
							$post_items[] = $key . '=' . $value;
						}
						$post_string = implode ('&', $post_items); 

						$url2 = 'https://madey.developro.pl/api/formularz/';
						$ch = curl_init(); 
						curl_setopt($ch, CURLOPT_URL, $url2);
						curl_setopt($ch, CURLOPT_POST, true); 
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
						curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
						curl_setopt($ch, CURLOPT_TIMEOUT, 4); 
						
						$regulkiCURL = curl_exec($ch);  
						curl_close($ch);
						
					}
					}
				}
		}

	   public function menuAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$tag = $this->getRequest()->getParam('tag');
	        $front = Zend_Controller_Front::getInstance();
	        $request = $front->getRequest();

			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('uri = ?', $tag));
			
	        $front = Zend_Controller_Front::getInstance();
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$master = explode("/", $page->uri);
				//echo $master[0];
				$this->getRequest()->setParam('parenttag', $master[0]);
				$this->getRequest()->setParam('sitetag', $master[0]);
				$this->getRequest()->setParam('siteid', $page->id);
				$this->getRequest()->setParam('uri', $page->uri);
				$this->getRequest()->setParam('tag', $page->tag);
				$this->view->parenttag = $master[0];

				$this->view->strona_nazwa = $page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = $page->id;
			}
	   }
	   
	   public function onasAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 5));
			
	        $front = Zend_Controller_Front::getInstance(); 
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 5;
				$this->view->pageclass = ' aboutus-page';

			}
	   }
	   
	   public function finansowanieAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 2));
			
	        $front = Zend_Controller_Front::getInstance(); 
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 3;
				$this->view->pageclass = ' finansowanie-page';

			}
	   }
	   
	   public function jakupicAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 5));
			
	        $front = Zend_Controller_Front::getInstance(); 
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 3;
				$this->view->pageclass = ' jakupic-page';

			}
	   }
	   
	   public function specjalnaAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 3));
			$tag = $this->getRequest()->getParam('oferta_tag');
			$oferta = $this->view->oferta = $db->fetchRow($db->select()->from('specjalne')->where('tag = ?', $tag));
			
	        $front = Zend_Controller_Front::getInstance(); 
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;

				$this->view->strona_tytul = ' - '.$oferta->nazwa;
				$this->view->strona_h1 = $oferta->nazwa;
				$this->view->seo_tytul = $oferta->meta_tytul;
				$this->view->seo_opis = $oferta->meta_opis;
				$this->view->seo_slowa = $oferta->meta_slowa;
				
				$this->view->strona_id = 3;
				
				$this->view->inne = $db->fetchAll($db->select()->from('specjalne')->where('id != ?', $oferta->id)->order('id DESC'));
					
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/'.$page->tag.'/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$oferta->nazwa.'</b><meta itemprop="position" content="3" /></li>';
			}
	   }
	   
	   public function inwestorAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 4));
			
	        $front = Zend_Controller_Front::getInstance(); 
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 4;


			}
	   }
	   
		public function ofertaAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$tag = $this->getRequest()->getParam('tag');
			$oferta = $this->view->oferta = $db->fetchRow($db->select()->from('mieszkania')->where('tag = ?', $tag));
			
			$front = Zend_Controller_Front::getInstance(); 
			$request = $front->getRequest();

			if(!$oferta) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $oferta->nazwa;

				$this->view->strona_tytul = ' - '.$oferta->nazwa;
				$this->view->strona_h1 = $oferta->nazwa;
				$this->view->seo_tytul = $oferta->meta_tytul;
				$this->view->seo_opis = $oferta->meta_opis;
				$this->view->seo_slowa = $oferta->meta_slowa;
				
				$this->view->listamieszkan = 1;

				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/o-inwestycji/"><span itemprop="name">O inwestycji</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$oferta->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
			$select_pomieszczenia = $db->select()
			->from(array('p' => 'inwestycje_powierzchnia'),
			array(
				'id',
				'id_inwest',
				'numer_pietro',
				'numer',
				'metry',
				'szukaj_metry',
				'pokoje',
				'status',
				'id_budynek',
				'promocja',
				'cena_m',
				'cena_m_promocja',
				'cena',
				'cena_promocja', 
				'html',
				'cords',
				'plik',
				'nazwa',
				'kuchnia',
				'ogrodek',
				'balkon',
				'pdf'
			));

			$select_pomieszczenia->where('pokoje IN (?)', explode(',', $oferta->pokoje));
			$select_pomieszczenia->where('id_inwest IN (?)', array(1,3,4,5));

			$select_pomieszczenia->where('status =?', 1);
			$this->view->powierzchnia = $result = $db->fetchAll($select_pomieszczenia);
			$this->view->wynik = count($result);

			$this->view->inwestycje = $db->fetchAll($db->select()->from('inwestycje'));
				
				
				
			}
		}
		public function inlineAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$inline = $db->fetchRow($db->select()->from('inline')->where('id = ?', $id));
			unset($inline['id']);
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($inline);
		}
		
		public function inlineiconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$inline = $db->fetchRow($db->select()->from('inline_icons')->where('id = ?', $id));
			unset($inline['id']);
			
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($inline);
		}
		
		public function inlineimgAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$inline = $db->fetchRow($db->select()->from('inline_img')->where('id = ?', $id));
			unset($inline['id']);
			
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($inline);
		}
		
		public function zapisziconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');

			$id = (int)$this->_request->getParam('id');
			$inlineQuery = $db->fetchRow($db->select()->from('inline_icons')->where('id = ?', $id));
			
			$formData = $this->_request->getPost();
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			
			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = zmiana($formData['modaltytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
			$iconheight = $formData['iconheight'];
			$iconwidth = $formData['iconwidth'];
			unset($formData['iconheight']);
			unset($formData['iconwidth']);
			
			$db->update('inline_icons', $formData, 'id = '.$id);
			
			if($obrazek) {
				unlink(FILES_PATH."/ikonki/".$inlineQuery['plik']);

				move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/ikonki/'.$plik);
				$upfile = FILES_PATH.'/ikonki/'.$plik;
				chmod($upfile, 0755);
				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($iconwidth, $iconheight)->save($upfile);
				
				$dataImg = array('plik' => $plik);
				$db->update('inline_icons', $dataImg, 'id = '.$id);
			}
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);
		}
		
		public function zapiszimgAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');

			$id = (int)$this->_request->getParam('id');
			$inlineQuery = $db->fetchRow($db->select()->from('inline_img')->where('id = ?', $id));
			
			$formData = $this->_request->getPost();
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			
			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = zmiana($formData['modaltytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
			$iconheight = $formData['iconheight'];
			$iconwidth = $formData['iconwidth'];
			unset($formData['iconheight']);
			unset($formData['iconwidth']);
			
			$db->update('inline_img', $formData, 'id = '.$id);
			
			if($obrazek) {
				unlink(FILES_PATH."/gfx/".$inlineQuery['plik']);

				move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/gfx/'.$plik);
				$upfile = FILES_PATH.'/gfx/'.$plik;
				chmod($upfile, 0755);
				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($iconwidth, $iconheight)->save($upfile);
				
				$dataImg = array('plik' => $plik);
				$db->update('inline_img', $dataImg, 'id = '.$id);
			}
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);
		}
		
		public function nowaiconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			
			$id_place = (int)$this->_request->getParam('id_place');
			
			$formData = $this->_request->getPost();
			$formData['id_place'] = $id_place;
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			
			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = zmiana($formData['modaltytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
			$iconheight = $formData['iconheight'];
			$iconwidth = $formData['iconwidth'];
			unset($formData['iconheight']);
			unset($formData['iconwidth']);
			
			$db->insert('inline_icons', $formData);
			$lastId = $db->lastInsertId();
			
			if($obrazek) {
				move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/ikonki/'.$plik);
				$upfile = FILES_PATH.'/ikonki/'.$plik;
				chmod($upfile, 0755);
				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($iconheight, $iconwidth)->save($upfile);
				
				$dataImg = array('plik' => $plik);
				$db->update('inline_icons', $dataImg, 'id = '.$lastId);
			}
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);

		}
		
		public function usuniconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inline_icons', $where);
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);

		}
		
		public function zapiszinlineAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$formData = $this->_request->getPost();
			$db->update('inline', $formData, 'id = '.$id);
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);

		}

}