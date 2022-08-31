<?php
class Default_InwestycjaController extends kCMS_Site
{
		public function preDispatch() {
			
			$this->menuPageId = 2;

			function statusMieszkaniaLista($numer){
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
			
			function rodzajOkno($numer){
				switch ($numer) {
					case '1':
						return "plastikowe";
					case '2':
						return "drewniane";
					case '3':
						return "aluminiowe";
				}
			}
			
			function materialBudynek($numer){
				switch ($numer) {
					case '1':
						return "cegła";
					case '2':
						return "drewno";
					case '3':
						return "pustak";
					case '4':
						return "keramzyt";
					case '5':
						return "płyta";
					case '6':
						return "beton";
					case '7':
						return "beton komórkowy";
					case '8':
						return "żelbeton";
				}
			}
			
			function ogrzewanie($numer){
				switch ($numer) {
					case '1':
						return "ogrzewanie miejskie";
					case '2':
						return "gazowe";
					case '3':
						return "kaflowe";
					case '4':
						return "elektryczne";
					case '5':
						return "kotłownia";
					case '6':
						return "inne";
				}
			}
			
			function typZabezpieczenie($numer){
				switch ($numer) {
					case '1':
						return "rolety";
					case '2':
						return "domofon";
					case '3':
						return "monitoring";
					case '4':
						return "alarm";
					case '5':
						return "teren zamknięty";
					case '6':
						return "telefon";
				}
			}
			
			function taknie($numer){
				switch ($numer) {
					case '1':
						return "tak";
					case '0':
						return "nie";
				}
			}

			function okno($numer){
				switch ($numer) {
					case '1':
						return "północ";
					case '2':
						return "południe";
					case '3':
						return "wschód";
					case '4':
						return "zachód";
					case '5':
						return "północny-wschód";
					case '6':
						return "północny-zachód";
					case '7':
						return "południowy-wschód";
					case '8':
						return "południowy-zachód";
				}
			}
			
			function media($numer){
				switch ($numer) {
					case '1':
						return "internet";
					case '2':
						return "telewizja";
					case '3':
						return "telefon";
				}
			}
			
			function kuchnia($numer){
				switch ($numer) {
					case '1':
						return "kuchnia";
					case '2':
						return "aneks kuchenny";
				}
			}
			
			function dodatkowe($numer){
				switch ($numer) {
					case '1':
						return "pomieszczenie użytkowe";
					case '2':
						return "garaż";
					case '3':
						return "piwnica";
					case '4':
						return "ogródek";
					case '5':
						return "taras";
					case '6':
						return "winda";
					case '7':
						return "oddzielna kuchnia";
					case '8':
						return "klimatyzacja";
				}
			}
			
			function lokalTyp($numer){
				switch ($numer) {
					case '1':
						return "Mieszkanie";
					case '2':
						return "Lokal usługowy";
                    case '4':
                    case '3':
						return "Miejsce parkingowe";
                }
			}
		}

/* Inwestycje zrealizowane */
		public function zrealizowaneAction() {
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
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 2;
				$this->view->pageclass = ' zrealizowane-page';
				$this->view->canonical = $this->baseUrl.'/inwestycje-zrealizowane/';

				$inwest = $db->select()
					->from('inwestycje', array(
						'nazwa',
						'miniaturka',
						'tag',
						'status',
						'lista',
						'koniec_mieszkania',
						'koniec_rok',
						'koniec_miasto',
						'miniaturka',
					))
					->where('status = ?', 2)
					->order('sort ASC');
				$this->view->inwestycje = $db->fetchAll($inwest);
			}
		}
		
/* Inwestycje zrealizowana */
		public function zrealizowanaAction() {
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
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {
				$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag');
				$zrealizowana = $this->view->zrealizowana = $db->fetchRow($db->select()->from('inwestycje')->where('tag = ?', $tag));

				if(!$zrealizowana) {
					$request->setModuleName('default');
					$request->setControllerName('error');
					$request->setActionName('error');
					$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
					$this->view->nofollow = 1;
					$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
					$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				} else {
			
					$this->view->strona_nazwa = $zrealizowana->nazwa;

					$this->view->strona_tytul = ' - '.$zrealizowana->nazwa;
					$this->view->seo_tytul = $zrealizowana->meta_tytul;
					$this->view->seo_opis = $zrealizowana->meta_opis;
					$this->view->seo_slowa = $zrealizowana->meta_slowa;
					
					$this->view->strona_id = 2;
					$this->view->pageclass = ' zrealizowana-page';
					$this->view->canonical = $this->baseUrl.'/inwestycje-zrealizowane/';
					
					$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/'.$page->tag.'/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$zrealizowana->nazwa.'</b><meta itemprop="position" content="3" /></li>';

				}
				
			}
		}		
		
/* Inwestycje w sprzedazy */		
		public function indexAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', $this->menuPageId));

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

				$this->view->strona_nazwa = $page->nazwa;
				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = $this->menuPageId;
				$this->view->canonical = $this->baseUrl.'/oferta/';
				$this->view->pageclass = ' offer-page';

                $uri = $request->getRequestUri();
				$inwest = $db->select()
					->from('inwestycje', array('nazwa', 'inwestycja_plik', 'tag', 'status', 'id', 'lista', 'lat', 'lng', 'addresspicker_map'))
					->order('sort ASC');

                if($uri == '/oferta/zakonczone' || $uri == '/oferta/zakonczone/') {
                    $inwest->where('status =?', 2);
                }

                if($uri == '/oferta/w-sprzedazy' || $uri == '/oferta/w-sprzedazy/') {
                    $inwest->where('status =?', 1);
                }



				$inwestycje = $this->view->inwestycje = $db->fetchAll($inwest);
				$this->view->inwestycjecount = count($inwestycje);
				$this->view->notop = 1;
				$this->view->nobottom = 1;
				$this->view->hidepageheader = 1;
			}
		}
			
/* Strona głowna inwestycji */		
		public function inwestycjaAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));
			
			//Wywolanie JS
			$this->view->validation = 1;
			$this->view->searchonplan = 1;
			
			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {

				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				$this->view->strona_h1 = 'O inwestycji';
				$this->view->strona_nazwa = $inwestycja->nazwa;

				$this->view->strona_tytul = ' - O inwestycji';
				$this->view->seo_tytul = $inwestycja->meta_tytul;
				$this->view->seo_opis = $inwestycja->meta_opis;
				$this->view->seo_slowa = $inwestycja->meta_slowa;
				$this->view->inwestmenupage = 'opis';

				// Czy jest plan inwestycji?
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id));

				// Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'), array('nazwa', 'id', 'tag', 'typ', 'tag_inwest', 'sort', 'status'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));
			
				// Atuty inwestycji
				$this->view->atuty = $db->fetchAll($db->select()->from('inwestycje_atut')->order('sort ASC')->where('id_inwest = ?', $inwestycja->id));
				$this->view->dziennik = $db->fetchAll($db->select()->from('inwestycje_news')->order('data DESC')->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->limit(5));

					//Formularz na karcie
					if ($this->_request->isPost()) {

						$ip = $_SERVER['REMOTE_ADDR'];
						$adresip = $db->fetchRow($db->select()->from('blokowanie')->where('ip = ?', $ip));
			
						if($adresip){  } else {

							$email = $this->_request->getPost('form_email');
							$imie = $this->_request->getPost('form_imie');
							$telefon = $this->_request->getPost('form_tel');
							$temat = $this->_request->getPost('form_temat');
							$wiadomosc = $this->_request->getPost('form_wiadomosc');
							$ip = $_SERVER['REMOTE_ADDR'];
							$datadodania = date("d.m.Y - H:i:s");

							$ustawienia = $db->fetchRow($db->select()->from('ustawienia'));

							$mail = new Zend_Mail('UTF-8');
							$mail
							->setFrom($ustawienia->email, 'Zapytanie ze strony www')
							->addTo($ustawienia->email, 'Adres odbiorcy')
							->addTo($temat, 'Adres odbiorcy')
							->setReplyTo($email, $imie)
							->setSubject($ustawienia->domena.' - Zapytanie z inwestycji - '.$inwestycja->nazwa)
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
							</div>');

							try {
								$mail->send();
							} catch (Zend_Exception $e) {
								//echo $e->getMessage();
								exit;
							} 
							$this->view->message = '1';
							$stat = array(
								'akcja' => 1,
								'miejsce' => 5,
								'id_inwest' => $inwestycja->id,
								'data' => date("d.m.Y - H:i:s"),
								'godz' => date("H"),
								'dzien' => date("d"),
								'msc' => date("m"),
								'rok' => date("Y"),
								'tekst' => $wiadomosc,
								'email' => $email,
								'telefon' => $telefon,
								'timestamp' => date("d-m-Y"),
								'ip' => $_SERVER['REMOTE_ADDR']
							);
							$db->insert('statystyki', $stat);

						}
					}

			}
		}
	
/* Wizualizacja */		
		public function planAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$this->view->pagetag = $tag_inwest_page = $this->getRequest()->getParam('tag_inwest_page');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje', array('id', 'nazwa', 'typ', 'tag', 'uslugowe', 'nazwa', 'zakres_pokoje', 'zakres_powierzchnia', 'header', 'status'))->where('tag = ?', $tag));

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				//Menu
				$menupage = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));

				//Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'), array('nazwa', 'id', 'tag', 'typ', 'tag_inwest', 'sort', 'status', 'meta_tytul', 'meta_slowa', 'meta_opis', 'ikonka'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));

				//Lokale uslugowe
				if($inwestycja->uslugowe == 1) {
					$this->view->uslugowki = lokale_uslugowe($inwestycja->typ, $inwestycja->id, $inwestycja->tag);
				}

				//SEO
				$this->view->strona_tytul = ' - '.$inwestycja->nazwa;
				$this->view->strona_nazwa = $inwestycja->nazwa;
				$this->view->strona_tekst = $inwestycja->tekst;
				$this->view->pagetag = $inwestycja->tag;
				
				$this->view->strona_h1 = $inwestycja->nazwa;
				$this->view->seo_tytul = $inwestycja->meta_tytul;
				$this->view->seo_slowa = $inwestycja->meta_slowa;
				$this->view->seo_opis = $inwestycja->meta_opis;
				
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');
				
				//Aktywne boczne menu
				$this->view->pagetag = 'plan-inwestycji';

				//Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$menupage->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				//Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 

				//Inwestycja osiedlowa
				if($inwestycja->typ == 1) {
					
					//Pobierz budynki
					$this->view->budynki = $db->fetchAll($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $inwestycja->id)->order('sort ASC'));
					
					//Pobierz mieszkania
					$this->view->powierzchnia = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest = ?', $inwestycja->id));

					$select_pomieszczenia = $db->select()->from(
					array('p' => 'inwestycje_powierzchnia'),
					array(
						'id',
						'id_inwest',
						'id_budynek',
						'numer',
						'metry',
						'szukaj_metry',
						'pokoje',
						'status',
						'plik',
						'pdf',
						'balkon',
						'ogrodek',
						'info_dodatkowe',
						'plik',
						'html',
						'nazwa',
						'kuchnia',
						'ogrodek',
						'numer_pietro',
					))
					->where('id_inwest = ?', $inwestycja->id);

					//Wyszukiwarka
					$action = $this->getRequest()->getParam('a');
					$this->view->s_pokoje = $s_pokoje = (int)$this->_request->getParam('s_pokoje');
					$this->view->s_metry =$s_metry = $this->getRequest()->getParam('s_metry');
					
					if($action == 'szukaj') {

						if($s_pokoje) {
								$select_pomieszczenia->where('pokoje =?', $s_pokoje);
						}

						if($s_metry) {
							$areapieces = explode("-", $s_metry);
							$select_pomieszczenia->where('szukaj_metry >=?', $areapieces[0]);
							$select_pomieszczenia->where('szukaj_metry <=?', $areapieces[1]);
						}

						if(!$s_pokoje && !$s_metry){
							$select_pomieszczenia->order('sort ASC');
						}

						if($s_pokoje || $s_metry) {
								
								$s_floor = "99";
								
								$stat = array(
									'id_inwest' => $inwestycja->id,
									'akcja' => 2,
									'miejsce' => 2,
									'pietro' => $s_floor,
									'pokoje' => $s_pokoje,
									'pow_od' => $areapieces[0],
									'pod_do' => $areapieces[1],
									'data' => date("d.m.Y - H:i:s"),
									'timestamp' => date("d-m-Y"),
									'godz' => date("H"),
									'dzien' => date("d"),
									'msc' => date("m"),
									'rok' => date("Y"),
									'ip' => $_SERVER['REMOTE_ADDR']
								);
								$db->insert('statystyki', $stat);
								
						}

						$select_pomieszczenia->where('status !=?', 2);
						
						$this->view->szukajeczka = 1;					
					
					} else { $this->view->wynikakcja = 'bezszukania'; }

					if($inwestycja->id == 1) {
						$select_pomieszczenia->where('status =?', 1);
					}

					$wyni = $this->view->powierzchnia = $db->fetchAll($select_pomieszczenia);
					$this->view->wynik = count($wyni);
					
					// Wywolanie JS

					$this->view->listamieszkan = 1;
					$this->view->budynkimap = 1;
					$this->view->tip = 1;
				}
				
				//Inwestycja z jednym budynkiem
				if($inwestycja->typ == 2) {

					$this->view->pietra = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer DESC'));

					$select_pomieszczenia = $db->select()->from(
					array('p' => 'inwestycje_powierzchnia'),
					array(
						'id',
						'id_inwest',
						'numer',
						'metry',
						'szukaj_metry',
						'pokoje',
						'status',
						'plik',
						'pdf',
						'balkon',
						'ogrodek',
						'info_dodatkowe',
						'html',
						'nazwa',
						'kuchnia',
						'numer_pietro',
						// 'promocja', 
						// 'cena_m',
						// 'cena_m_promocja',
						// 'cena',
						// 'cena_promocja'
					))
					->where('id_inwest = ?', $inwestycja->id);
					
						// Pobierz z URL
						$this->view->szukajeczka = $action = $this->getRequest()->getParam('a');
						$this->view->s_pokoje = $s_pokoje = $this->_request->getParam('s_pokoje');
						$this->view->s_metry = $s_metry = $this->_request->getParam('s_metry');
						
						if($action == 'szukaj') {
							
							if($s_pokoje) {
									$select_pomieszczenia->where('pokoje =?', $s_pokoje);
							}

							if($s_metry) {
								$areapieces = explode("-", $s_metry);
								$select_pomieszczenia->where('szukaj_metry >=?', $areapieces[0]);
								$select_pomieszczenia->where('szukaj_metry <=?', $areapieces[1]);
							}

							if(!$s_metry && !$s_pokoje){
								$select_pomieszczenia->order('numer ASC');
							}

							if($s_metry || $s_pokoje ) {
									
									if($numer_pietro || $numer_pietro == '0') { $s_floor = "99";} else { $s_floor = 0; }
									
									if(!$numer_pietro) { $s_floor = 0; }
									if($numer_pietro == '0') { $s_floor = "99";}
									if($numer_pietro && $numer_pietro <> '0') { $s_floor = $numer_pietro;}
									
									$stat = array(
										'id_inwest' => $inwestycja->id,
										'akcja' => 2,
										'miejsce' => 2,
										'pietro' => $s_floor,
										'pokoje' => $s_pokoje,
										'pow_od' => $areapieces[0],
										'pod_do' => $areapieces[1],
										'data' => date("d.m.Y - H:i:s"),
										'timestamp' => date("d-m-Y"),
										'godz' => date("H"),
										'dzien' => date("d"),
										'msc' => date("m"),
										'rok' => date("Y"),
										'ip' => $_SERVER['REMOTE_ADDR']
									);
									$db->insert('statystyki', $stat);
									
							}

							$select_pomieszczenia->where('status !=?', 2)->order('numer ASC');
							$this->view->szukajeczka = 1;
							
						} else { 
							$select_pomieszczenia->order('numer ASC');
						}

                    $select_pomieszczenia->where('status =?', 1);
					$wyni = $this->view->powierzchnia = $db->fetchAll($select_pomieszczenia);
					$this->view->wynik = count($wyni);

					// Wywolanie JS

					$this->view->listamieszkan = 1;
					$this->view->budynkimap = 1;
					$this->view->tip = 1;
				}
				
				//Inwestycja domkowa
				if($inwestycja->typ == 3) {

					$select_pomieszczenia = $db->select()->from(
					array('p' => 'inwestycje_powierzchnia'),
					array(
						'id',
						'id_inwest',
						'numer',
						'metry',
						'szukaj_metry',
						'pokoje',
						'status',
						'plik',
						'pdf',
						'balkon',
						'ogrodek',
						'info_dodatkowe',
						'plik',
						'html',
						'nazwa',
						'kuchnia',
						'ogrodek',
						// 'promocja', 
						// 'cena_m',
						// 'cena_m_promocja',
						// 'cena',
						// 'cena_promocja'
					))
					->where('id_inwest = ?', $inwestycja->id);

					//Wyszukiwarka
					// $this->view->searching = $action = $this->getRequest()->getParam('a');
					// $this->view->cena = $cena = $this->getRequest()->getParam('cena');
					// $this->view->metraz = $area = $this->getRequest()->getParam('metraz');
					// $this->view->pokoje = $rooms = (int)$this->_request->getParam('pokoje');
					// $this->view->s_room =$s_room = $this->getRequest()->getParam('s_room');
					// $this->view->s_area =$s_area = $this->getRequest()->getParam('s_area');
					
					// if($action == 'szukaj') {
					
						// if($rooms) {
								// $select_pomieszczenia->where('pokoje =?', $rooms);
						// }

						// if($cena) {
							// $pieces = explode("-", $cena);
		
							// $select_pomieszczenia->where('szukaj_cena >=?', $pieces[0]);
							// $select_pomieszczenia->where('szukaj_cena <=?', $pieces[1]);
						// }

						// if($area) {
							// $areapieces = explode("-", $area);
							// $select_pomieszczenia->where('szukaj_metry >=?', $areapieces[0]);
							// $select_pomieszczenia->where('szukaj_metry <=?', $areapieces[1]);
						// }

						// if(!$cena && !$area && !$rooms){
							// $select_pomieszczenia->order('sort ASC');
						// }

						// if ($opcja == 'kuchnia') {
							// $select_pomieszczenia->where('kuchnia =?', 1);
						// }			
						// if ($opcja == 'aneks') {
							// $select_pomieszczenia->where('kuchnia =?', 2);
						// }			
						// if ($opcja == 'promocja') {
							// $select_pomieszczenia->where('promocja =?', 1);
						// }
						// if ($opcja == 'mdm') {
							// $select_pomieszczenia->where('mdm=?', 1);
						// }

						// if($statusroom || $rooms || $areaFrom || $areaTo) {
								
								// if($numer_pietro || $numer_pietro == '0') { $s_floor = "99";} else { $s_floor = 0; }
								
								// if(!$numer_pietro) { $s_floor = 0; }
								// if($numer_pietro == '0') { $s_floor = "99";}
								// if($numer_pietro && $numer_pietro <> '0') { $s_floor = $numer_pietro;}
								
								// $stat = array(
									// 'id_inwest' => $inwestycja->id,
									// 'akcja' => 2,
									// 'miejsce' => 2,
									// 'pietro' => $s_floor,
									// 'pokoje' => $rooms,
									// 'pow_od' => $areaFrom,
									// 'pod_do' => $areaTo,
									// 'data' => date("d.m.Y - H:i:s"),
									// 'timestamp' => date("d-m-Y"),
									// 'godz' => date("H"),
									// 'dzien' => date("d"),
									// 'msc' => date("m"),
									// 'rok' => date("Y"),
									// 'ip' => $_SERVER['REMOTE_ADDR']
								// );
								// $db->insert('statystyki', $stat);
								
						// }

						// $select_pomieszczenia->where('status !=?', 2);
					
					// } else { $this->view->wynikakcja = 'bezszukania'; }//$action == 'szukaj'
					
					// $s_room = $this->getRequest()->getParam('s_room');
					// $s_area = $this->getRequest()->getParam('s_area');
					// if($s_room){
						// $select_pomieszczenia->order('pokoje '.$s_room);
					// }
					
					// if($s_area){
						// $select_pomieszczenia->order('szukaj_metry '.$s_area);
					// }
	
					$wyni = $this->view->powierzchnia = $db->fetchAll($select_pomieszczenia);
					$this->view->wynik = count($wyni);

					// Wywolanie JS
					$this->view->domymap = 1;
					$this->view->listamieszkan = 1;
					$this->view->tip = 1;

					// echo '<pre>';
					// print_r($wyni);
					// echo '<pre>';
				}
			}
		}
		
/* Budynek */		
		public function budynekAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$this->getRequest()->setParam('sitetag', 'oferta');
			$this->getRequest()->setParam('tag', 'oferta');
			
			// Wywolanie JS
			$this->view->listamieszkan = 1;
			$this->view->budynkimap = 1;
			$this->view->tip = 1;
			
			$this->view->pagetag = 'plan-inwestycji';

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$id_budynek = $this->view->budynekid = $this->getRequest()->getParam('id_budynek');
			
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				// Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				// Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
				
				// Plan budynku
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $inwestycja->id)->where('id = ?', $id_budynek));
				
				// SEO
				$this->view->strona_h1 = $inwestycja->nazwa.' - '.$budynek->nazwa;
				$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$budynek->nazwa;
				$this->view->seo_tytul = $budynek->meta_tytul;
				$this->view->seo_opis = $budynek->meta_opis;
				$this->view->seo_slowa = $budynek->meta_slowa;
				
				// Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'), array('nazwa', 'id', 'tag', 'typ', 'tag_inwest', 'sort', 'status'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));

				// Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/'.$page->tag.'/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/"><span itemprop="name">'.$inwestycja->nazwa.'</span></a><meta itemprop="position" content="3" /></li>
				<li class="sep"></li>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$budynek->id.'/"><span itemprop="name">'.$budynek->nazwa.'</span><meta itemprop="position" content="4" /></li>';
			
				// Nawigacja budynkami
				$this->view->kolejne = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id > ?', $id_budynek)->where('id_inwest = ?', $inwestycja->id)->order('id ASC')->limit(1));
				$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id < ?', $id_budynek)->where('id_inwest = ?', $inwestycja->id)->order('id DESC')->limit(1));

				//Lokale uslugowe
				if($inwestycja->uslugowe == 1) {
					$this->view->uslugowki = lokale_uslugowe($inwestycja->typ, $inwestycja->id, $inwestycja->tag);
				}

				if($inwestycja->typ == 1) {

					$this->view->pietra = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->where('id_budynek = ?', $id_budynek)->order('numer DESC')->order('typ ASC'));
					
				}

				$this->view->canonical = $this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$id_budynek.'/';

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
					'plik',
					'kuchnia',
					'ogrodek',
					'nazwa'
					
				))
				->where('id_inwest = ?', $inwestycja->id)
				->where('id_budynek = ?', $id_budynek);

				// Wyszukiwarka
				$this->view->searching = $action = $this->getRequest()->getParam('a');
				$this->view->s_pokoje = $s_pokoje = (int)$this->_request->getParam('s_pokoje');
				$this->view->s_metry =$s_metry = $this->getRequest()->getParam('s_metry');
				
				if($action == 'szukaj') {
					
					if($s_pokoje) {
							$select_pomieszczenia->where('pokoje =?', $s_pokoje);
					}

					if($s_metry) {
						$areapieces = explode("-", $s_metry);
						$select_pomieszczenia->where('szukaj_metry >=?', $areapieces[0]);
						$select_pomieszczenia->where('szukaj_metry <=?', $areapieces[1]);
					}

					if(!$s_pokoje && !$s_metry){
						$select_pomieszczenia->order('sort ASC');
					}

					if($s_pokoje || $s_metry ) {
							
							if($numer_pietro || $numer_pietro == '0') { $s_floor = "99";} else { $s_floor = 0; }
							
							if(!$numer_pietro) { $s_floor = 0; }
							if($numer_pietro == '0') { $s_floor = "99";}
							if($numer_pietro && $numer_pietro <> '0') { $s_floor = $numer_pietro;}
							
							$stat = array(
								'id_inwest' => $inwestycja->id,
								'akcja' => 2,
								'miejsce' => 2,
								'pietro' => $s_floor,
								'budynek' => $id_budynek,
								'pokoje' => $s_pokoje,
								'pow_od' => $areapieces[0],
								'pod_do' => $areapieces[1],
								'data' => date("d.m.Y - H:i:s"),
								'timestamp' => date("d-m-Y"),
								'godz' => date("H"),
								'dzien' => date("d"),
								'msc' => date("m"),
								'rok' => date("Y"),
								'ip' => $_SERVER['REMOTE_ADDR']
							);
							$db->insert('statystyki', $stat);
							
					}

					$select_pomieszczenia->where('status !=?', 2);
					
					$this->view->szukajeczka = 1;
					
				} else { 
					$this->view->wynikakcja = 'bezszukania'; //$action == 'szukaj'
				}

				$wyni = $this->view->powierzchnia = $db->fetchAll($select_pomieszczenia);
				$this->view->wynik = count($wyni);

			}

		}

/* Pietro przy inwestycji osiedlowej */		
		public function pietrobudynekAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			// Wywolanie JS
			$this->view->listamieszkan = 1;
			$this->view->pietromap = 1;
			$this->view->tip = 1;
				
			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				// Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', 7));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				$id_budynek = $this->getRequest()->getParam('id_budynek');
				$numer_pietro = $this->getRequest()->getParam('numer_pietro');
				$typ_pietro = $this->getRequest()->getParam('typ_pietro');

				// Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
				
				// Budynek
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $inwestycja->id)->where('id = ?', $id_budynek));
			
				// Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'), array('nazwa', 'id', 'tag', 'typ', 'tag_inwest', 'sort', 'status'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));

				//Lokale uslugowe
				if($inwestycja->uslugowe == 1) {
					$this->view->uslugowki = lokale_uslugowe($inwestycja->typ, $inwestycja->id, $inwestycja->tag);
				}
	
				// Lista pięter
				$this->view->pietra = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->where('id_budynek = ?', $id_budynek)->order('numer ASC'));

				// Lista budynkow
				$this->view->budynki = $db->fetchAll($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
				
				// Wybrane pietro
				$pietro = $this->view->pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->where('id_budynek = ?', $id_budynek)->where('numer = ?', $numer_pietro)->where('typ = ?', $typ_pietro));
				
				// SEO
				$this->view->strona_nazwa = $inwestycja->nazwa;
				$this->view->strona_h1 = $inwestycja->nazwa.' - '.$pietro->nazwa;
				$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$pietro->nazwa;
				$this->view->seo_tytul = $pietro->meta_tytul;
				$this->view->seo_opis = $pietro->meta_opis;
				$this->view->seo_slowa = $pietro->meta_slowa;
				$this->view->pagetag = 'plan-inwestycji';
				
				// Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/'.$page->tag.'/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li>
				<li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="item">'.$inwestycja->nazwa.'</span><meta itemprop="position" content="3" /></li>
				<li class="sep"></li>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$budynek->id.'/"><span itemprop="name">'.$budynek->nazwa.'</span></a><meta itemprop="position" content="4" /></li>
				<li class="sep"></li>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="name">'.$pietro->nazwa.'</b><meta itemprop="position" content="5" /></li>';
				
				if(!$pietro) {
			
					$front = Zend_Controller_Front::getInstance();
					$request = $front->getRequest();
					$request->setModuleName('default');
					$request->setControllerName('error');
					$request->setActionName('error');

					$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
					$this->view->nofollow = 1;
					$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
					$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
					
				} else {
				
					$this->view->kolejne = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('numer > ?', $numer_pietro)->where('id_budynek = ?', $id_budynek)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $typ_pietro)->order('numer ASC')->limit(1));
					
					$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('numer < ?', $numer_pietro)->where('id_budynek = ?', $id_budynek)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $typ_pietro)->order('numer DESC')->limit(1));
		
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
					))
					->where('id_inwest = ?', $inwestycja->id)
					->where('numer_pietro = ?', $numer_pietro)
					->where('typ = ?', $typ_pietro)
					->where('id_budynek = ?', $id_budynek);

					// Wyszukiwarka
					$this->view->searching = $action = $this->getRequest()->getParam('a');
					$this->view->s_pokoje = $s_pokoje = (int)$this->getRequest()->getParam('s_pokoje');
					$this->view->s_metry = $s_metry = $this->getRequest()->getParam('s_metry');
						
						if($action == 'szukaj') {
							
							if($s_pokoje) {
									$select_pomieszczenia->where('pokoje =?', $s_pokoje);
							}

							if($s_metry) {
								$areapieces = explode("-", $s_metry);
								$select_pomieszczenia->where('szukaj_metry >=?', $areapieces[0]);
								$select_pomieszczenia->where('szukaj_metry <=?', $areapieces[1]);
							}

							if(!$s_pokoje && !$s_metry ){
								$select_pomieszczenia->order('sort ASC');
							}

							$this->view->canonical = $this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$id_budynek.'/p/'.$numer_pietro.'/typ/'.$typ_pietro.'/';

							if($s_pokoje || $s_metry) {
									
									if($numer_pietro || $numer_pietro == '0') { $s_floor = "99";} else { $s_floor = 0; }
									
									if(!$numer_pietro) { $s_floor = 0; }
									if($numer_pietro == '0') { $s_floor = "99";}
									if($numer_pietro && $numer_pietro <> '0') { $s_floor = $numer_pietro;}
									
									$stat = array(
										'id_inwest' => $inwestycja->id,
										'akcja' => 2,
										'miejsce' => 2,
										'pietro' => $s_floor,
										'pokoje' => $s_pokoje,
										'pow_od' => $areapieces[0],
										'pod_do' => $areapieces[1],
										'data' => date("d.m.Y - H:i:s"),
										'timestamp' => date("d-m-Y"),
										'godz' => date("H"),
										'dzien' => date("d"),
										'msc' => date("m"),
										'rok' => date("Y"),
										'ip' => $_SERVER['REMOTE_ADDR']
									);
									
									$db->insert('statystyki', $stat);
									
							}

							$select_pomieszczenia->where('status !=?', 2);
							$this->view->szukajeczka = 1;
						
						} else {
							$this->view->wynikakcja = 'bezszukania'; //$action == 'szukaj'
						}

						$wyni = $this->view->powierzchnia = $db->fetchAll($select_pomieszczenia);
						$this->view->wynik = count($wyni);
				}
				
			}
		}
		
/* Pietro */		
		public function pietroAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			// Wywolanie JS
			$this->view->listamieszkan = 1;
			$this->view->pietromap = 1;
			$this->view->tip = 1;
			
			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				// Menu głowne
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));

				// Wybrane pietro
				$numer_pietro = $this->getRequest()->getParam('numer_pietro');
				$typ_pietro = $this->getRequest()->getParam('typ_pietro');
				$pietro = $this->view->pietro = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->where('numer = ?', $numer_pietro)->where('typ = ?', $typ_pietro));

				if(!$pietro) {
			
					$front = Zend_Controller_Front::getInstance();
					$request = $front->getRequest();
					$request->setModuleName('default');
					$request->setControllerName('error');
					$request->setActionName('error');

					$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
					$this->view->nofollow = 1;
					$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
					$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
				} else {
				
					// SEO
					$this->view->strona_nazwa = $inwestycja->nazwa;
					$this->view->strona_h1 = $inwestycja->nazwa.' - '.$pietro->nazwa;
					$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$pietro->nazwa;
					$this->view->seo_tytul = $pietro->meta_tytul;
					$this->view->seo_opis = $pietro->meta_opis;
					$this->view->seo_slowa = $pietro->meta_slowa;
					
					$this->getRequest()->setParam('sitetag', 'oferta');
					$this->getRequest()->setParam('tag', 'oferta');
					$this->view->pagetag = 'plan-inwestycji';
					$this->view->canonical = $this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$numer_pietro.'/typ/'.$typ_pietro.'/';

					//Lokale uslugowe
					if($inwestycja->uslugowe == 1) {
						$this->view->uslugowki = lokale_uslugowe($inwestycja->typ, $inwestycja->id, $inwestycja->tag);
					}

					// Plan inwestycji
					$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
					
					// Strony tekstowe inwestycji
					$this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'), array('nazwa', 'id', 'tag', 'typ', 'tag_inwest', 'sort', 'status'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));

					// Schema breadcrumbs
					$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li>
					<li class="sep"></li>
					<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/"><span itemprop="name">'.$inwestycja->nazwa.'</span></a><meta itemprop="position" content="3" /></li>
					<li class="sep"></li>
					<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="name">'.$pietro->nazwa.'</b><meta itemprop="position" content="4" /></li>';
				
					// Pietro wyzej i nizej
					$this->view->kolejne = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('numer > ?', $numer_pietro)->where('id_inwest = ?', $inwestycja->id)->order('numer ASC')->limit(1));
					
					$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_pietro')->where('numer < ?', $numer_pietro)->where('id_inwest = ?', $inwestycja->id)->order('numer DESC')->limit(1));

					// Pobierz mieszkania
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
					))
					->where('id_inwest = ?', $inwestycja->id)
					->where('numer_pietro = ?', $numer_pietro)
					->where('typ = ?', $typ_pietro);
					
						// Pobierz z URL
						$this->view->szukajeczka = $action = $this->getRequest()->getParam('a');
						$this->view->s_pokoje = $s_pokoje = $this->_request->getParam('s_pokoje');
						$this->view->s_metry = $s_metry = $this->_request->getParam('s_metry');
						
						if($action == 'szukaj') {
							
							if($s_pokoje) {
									$select_pomieszczenia->where('pokoje =?', $s_pokoje);
							}

							if($s_metry) {
								$areapieces = explode("-", $s_metry);
								$select_pomieszczenia->where('szukaj_metry >=?', $areapieces[0]);
								$select_pomieszczenia->where('szukaj_metry <=?', $areapieces[1]);
							}

							if(!$s_metry && !$s_pokoje){
								$select_pomieszczenia->order('sort ASC');
							}

							if($s_metry || $s_pokoje ) {
									
									if($numer_pietro || $numer_pietro == '0') { $s_floor = "99";} else { $s_floor = 0; }
									
									if(!$numer_pietro) { $s_floor = 0; }
									if($numer_pietro == '0') { $s_floor = "99";}
									if($numer_pietro && $numer_pietro <> '0') { $s_floor = $numer_pietro;}
									
									// $stat = array(
										// 'id_inwest' => $inwestycja->id,
										// 'akcja' => 2,
										// 'miejsce' => 2,
										// 'pietro' => $s_floor,
										// 'pokoje' => $rooms,
										// 'pow_od' => $areapieces[0],
										// 'pod_do' => $areapieces[1],
										// 'data' => date("d.m.Y - H:i:s"),
										// 'timestamp' => date("d-m-Y"),
										// 'godz' => date("H"),
										// 'dzien' => date("d"),
										// 'msc' => date("m"),
										// 'rok' => date("Y"),
										// 'ip' => $_SERVER['REMOTE_ADDR']
									// );
									
									// $db->insert('statystyki', $stat);
									
							}

						$select_pomieszczenia->where('status !=?', 2);
						$this->view->szukajeczka = 1;
						
						} else { 
							$this->view->wynikakcja = 'bezszukania'; //$action == 'szukaj'
						}

						$wyni = $this->view->powierzchnia = $db->fetchAll($select_pomieszczenia);
						$this->view->wynik = count($wyni);
						
						// echo '<pre>';
						// print_r($wyni);
						// echo '<pre>';
				}
				
			}
		}
	
/* Mieszkanie przy inwestycji osiedlowej */		
		public function mieszkanieAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			//Wywolanie JS
			$this->view->validation = 1;

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$id_budynek = $this->getRequest()->getParam('id_budynek');
			$numer_pietro = $this->getRequest()->getParam('numer_pietro');
			$numer_mieszkanie = $this->getRequest()->getParam('mieszkanie');
			
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if($id_budynek){
				$budynek = $this->view->budynek = $db->fetchRow($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $inwestycja->id)->where('id = ?', $id_budynek));
			}
			
			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				//Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				if(isset($numer_pietro)){
					$select_pietro = $db->select()->from(array('p' => 'inwestycje_pietro'))->where('id_inwest = ?', $inwestycja->id)->where('numer = ?', $numer_pietro);
					$pietro = $this->view->pietro = $db->fetchRow($select_pietro);
					$this->view->pietroid = $pietro->id;
				}
				
				if($numer_mieszkanie) {
					
					//Inwestycja osiedlowa
					if($inwestycja->typ == 1) {
						$select_pomieszczenia = $db->select()
						->from('inwestycje_powierzchnia')
						->where('id_inwest = ?', $inwestycja->id)
						->where('numer_pietro = ?', $numer_pietro)
						->where('id_budynek = ?', $budynek->id)
						->where('numer = ?', $numer_mieszkanie)
						->order('sort ASC');
						$mieszkanie = $this->view->mieszkanie = $db->fetchRow($select_pomieszczenia);
					}
					
					//Inwestycja osiedlowa
					if($inwestycja->typ == 2) {
						$select_pomieszczenia = $db->select()
						->from('inwestycje_powierzchnia')
						->where('id_inwest = ?', $inwestycja->id)
						->where('numer_pietro = ?', $numer_pietro)
						->where('numer = ?', $numer_mieszkanie)
						->order('sort ASC');
						$mieszkanie = $this->view->mieszkanie = $db->fetchRow($select_pomieszczenia);
					}
					
					//Inwestycja domkowa
					if($inwestycja->typ == 3) {
						$select_pomieszczenia = $db->select()
						->from('inwestycje_powierzchnia')
						->where('id_inwest = ?', $inwestycja->id)
						->where('numer = ?', $numer_mieszkanie)
						->order('sort ASC');
						$mieszkanie = $this->view->mieszkanie = $db->fetchRow($select_pomieszczenia);
					}
				
					//SEO
					$this->view->strona_nazwa = $mieszkanie->nazwa;
					$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$mieszkanie->nazwa;
					$this->view->seo_tytul = $mieszkanie->meta_tytul;
					$this->view->seo_opis = $mieszkanie->meta_opis;
					$this->view->seo_slowa = $mieszkanie->meta_slowa;
					
					$this->view->oknoArray = explode(',',$mieszkanie->okno);
					$this->view->mediaArray = explode(',',$mieszkanie->media);
					$this->view->zabezpieczenieArray = explode(',',$mieszkanie->zabezpieczenia);
					$this->view->info_dodatkoweArray = explode(',',$mieszkanie->info_dodatkowe);

				}

				if(!$mieszkanie) {
			
					$front = Zend_Controller_Front::getInstance();
					$request = $front->getRequest();
					$request->setModuleName('default');
					$request->setControllerName('error');
					$request->setActionName('error');

					$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
					$this->view->nofollow = 1;
					$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
					$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
				} else {
						
					$stat = array(
						'akcja' => 3,
						'miejsce' => 3,
						'id_inwest' => $inwestycja->id,
						'mieszkanie' => $mieszkanie->nazwa,
						'pokoje' => $mieszkanie->pokoje,
						'timestamp' => date("d-m-Y"),
						'data' => date("d.m.Y - H:i:s"),
						'godz' => date("H"),
						'dzien' => date("d"),
						'msc' => date("m"),
						'rok' => date("Y"),
						'ip' => $_SERVER['REMOTE_ADDR']
					);
					$db->insert('statystyki', $stat);	

					//Inwestycja osiedlowa
					if($inwestycja->typ == 1) {
						
						if($inwestycja->id == 1) {
						
							$this->view->nastepne = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer > ?', $mieszkanie->numer)->order('numer ASC')->limit(1)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $mieszkanie->typ)->where('status = ?', 1));
						
							$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer < ?', $mieszkanie->numer)->order('numer DESC')->limit(1)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $mieszkanie->typ)->where('status = ?', 1));
						
						} else {
						
							$this->view->nastepne = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer > ?', $mieszkanie->numer)->order('numer ASC')->limit(1)->where('numer_pietro = ?', $numer_pietro)->where('id_inwest = ?', $inwestycja->id)->where('id_budynek = ?', $budynek->id)->where('typ = ?', $mieszkanie->typ));
						
							$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer < ?', $mieszkanie->numer)->order('numer DESC')->limit(1)->where('numer_pietro = ?', $numer_pietro)->where('id_inwest = ?', $inwestycja->id)->where('id_budynek = ?', $budynek->id)->where('typ = ?', $mieszkanie->typ));
							
						}
						
						$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
						
					}
					
					//Inwestycja budynkowa
					if($inwestycja->typ == 2) {
						$this->view->nastepne = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer > ?', $mieszkanie->numer)->order('numer ASC')->limit(1)->where('numer_pietro = ?', $numer_pietro)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $mieszkanie->typ));
					
						$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer < ?', $mieszkanie->numer)->order('numer DESC')->limit(1)->where('numer_pietro = ?', $numer_pietro)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $mieszkanie->typ));
						
						$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li>
						<li class="sep"></li>
						<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/"><span itemprop="name">'.$inwestycja->nazwa.'</span></a><meta itemprop="position" content="3" /></li>
						<li class="sep"></li>
						<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$pietro->numer.'/typ/'.$pietro->typ.'/"><span itemprop="name">'.$pietro->nazwa.'</span></a><meta itemprop="position" content="4" /></li>
						<li class="sep"></li>
						<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$mieszkanie->nazwa.'</b><meta itemprop="position" content="5" /></li>
						
						';
					}
					
					//Inwestycja domkowa
					if($inwestycja->typ == 3) {
						$this->view->nastepne = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer > ?', $mieszkanie->numer)->order('numer ASC')->limit(1)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $mieszkanie->typ));
					
						$this->view->poprzednie = $db->fetchRow($db->select()->from('inwestycje_powierzchnia')->where('numer < ?', $mieszkanie->numer)->order('numer DESC')->limit(1)->where('id_inwest = ?', $inwestycja->id)->where('typ = ?', $mieszkanie->typ));
					}
					
					//Pobranie regulek
					$url = 'https://madey.developro.pl/api/regulki/';
					$login = 'admin';
					$password = 'admin';
					$id = $inwestycja->id;
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
                    $regulki = $this->view->regulkis = json_decode($regulkiCURL, true);

					$this->view->rodo = $db->fetchRow($db->select()->from('rodo_ustawienia')->where('id =?', 1));
					
					//Formularz na karcie
					if ($this->_request->isPost()) {

						$ip = $_SERVER['REMOTE_ADDR'];
						$adresip = $db->fetchRow($db->select()->from('blokowanie')->where('ip = ?', $ip));
					
						$grecaptcha = $this->_request->getPost('g-recaptcha-response');
						$secret = '6LcbhL8UAAAAAN5gd0KCO5MI8rrNMRRqHYFCe2ow';
						$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$grecaptcha);
						$responseData = json_decode($verifyResponse);
			
						if($responseData->success){
							
							if($adresip){  } else {

								$email = $this->_request->getPost('form_email');
								$imie = $this->_request->getPost('form_imie');
								$telefon = $this->_request->getPost('form_telefon');
								$wiadomosc = $this->_request->getPost('form_wiadomosc');
								$ip = $_SERVER['REMOTE_ADDR'];
								$datadodania = date("d.m.Y - H:i:s");

								$ustawienia = $db->fetchRow($db->select()->from('ustawienia'));
								
								//Inwestycja osiedlowa
								if($inwestycja->typ == 1){
									$linkdomieszkania = '<p>Link do mieszkania: <a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$mieszkanie->id_budynek.'/p/'.$mieszkanie->numer_pietro.'/m/'. $mieszkanie->numer .'/">'.$mieszkanie->nazwa .'</a></p>';
								}
								
								//Inwestycja budynkowa
								if($inwestycja->typ == 2){
									$linkdomieszkania = '<p>Link do mieszkania: <a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$mieszkanie->numer_pietro.'/m/'. $mieszkanie->numer .'/">'.$mieszkanie->nazwa .'</a></p>';
								}
								
								//Inwestycja domkowa
								if($inwestycja->typ == 3){
									$linkdomieszkania = '<p>Link do domu: <a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/d/'. $mieszkanie->numer .'/">'.$mieszkanie->nazwa .'</a></p>';
								}
								
								$mail = new Zend_Mail('UTF-8');
								$mail
								->setFrom($ustawienia->email, $imie)
								->setReplyTo($email, $imie)
								->addTo('test-u9jat84r9@srv1.mail-tester.com', 'Adres odbiorcy')
								->addTo($inwestycja->email, 'Adres odbiorcy')
								->setSubject($ustawienia->domena.' - Zapytanie o mieszkanie - '.$inwestycja->nazwa)
								->setBodyHTML('
								<div style="width:550px;border:1px solid #ececec;padding:0 20px;margin:0 auto;font-family:Arial;font-size:14px;line-height:27px">
								<p style="text-align:center">'.$ustawienia->nazwa.'</p>
								<p><b>Wiadomość wysłana: '. $datadodania .'</b></p>
								<hr style="border:0;border-bottom:1px solid #ececec" />
								<p><b>Imię i nazwisko:</b> '.$imie.'<br />
								<b>E-mail:</b> '. $email .'<br />
								<b>Telefon:</b> '. $telefon .'<br />
								<b>Wiadomość:</b> '. $wiadomosc .'<br />
								<b>IP:</b> '. $ip .'<br />
								<hr style="border:0;border-bottom:1px solid #ececec" />
								<h3 style="margin-bottom:5px">Mieszkanie nr. '. $mieszkanie->numer .'</h3>
								<p style="margin-top:0">Piętro: '. $mieszkanie->numer_pietro .'<br>
								'.$linkdomieszkania.'</div>');

								try {
									$mail->send();
								} catch (Zend_Exception $e) {
									//echo $e->getMessage();
									exit;
								} 
								$this->view->message = '1';
								$stat = array(
									'akcja' => 1,
									'miejsce' => 3,
									'id_inwest' => $mieszkanie->id_inwest,
									'data' => date("d.m.Y - H:i:s"),
									'godz' => date("H"),
									'dzien' => date("d"),
									'msc' => date("m"),
									'rok' => date("Y"),
									'mieszkanie' => $mieszkanie->nazwa,
									'tekst' => $wiadomosc,
									'email' => $email,
									'telefon' => $telefon,
									'timestamp' => date("d-m-Y"),
									'ip' => $_SERVER['REMOTE_ADDR']
								);
								$db->insert('statystyki', $stat);
				
								$formData = $this->_request->getPost();
								$formData['data_dodania'] = date("d-m-Y H:s");
								$formData['miasto'] = 54;
								$formData['id_handlowiec'] = 56;
								$formData['status'] = 55;
								$formData['id_inwest'] = $inwestycja->id;
								$formData['ip'] = $_SERVER['REMOTE_ADDR'];
								$formData['www'] = $_SERVER['REQUEST_URI'];
								$formData['imie'] = $formData['form_imie'];
								unset($formData['form_imie']);
								$formData['email'] = $formData['form_email'];
								unset($formData['form_email']);
								$formData['telefon'] = $formData['form_telefon'];
								unset($formData['form_telefon']);
								$formData['pietro'] = $numer_pietro;
								$formData['pokoje'] = $mieszkanie->pokoje;
								$formData['powierzchnia'] = $mieszkanie->metry;
								$formData['id_room'] = $mieszkanie->id;
								
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
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
								curl_setopt($ch, CURLOPT_TIMEOUT, 4); 
								
								$regulkiCURL = curl_exec($ch);  

								curl_close($ch);

//								 $user = Zend_Auth::getInstance()->getIdentity();
//								 if($user) {
//
//									 echo '<pre>';
//									 print_r($regulkiCURL);
//									 echo '</pre>';
//
//								 }
								
							}
						}
					}

				}
			}
		}
			
/* Dziennik inwestycji */		
		public function dziennikAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$tag_inwest_page = $this->getRequest()->getParam('tag_inwest_page');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));
			
			$this->view->searchonplan = 1;

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				//Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');
				
				//SEO
				$this->view->strona_nazwa = $inwestycja->nazwa;
				$this->view->strona_tytul = ' - '.$inwestycja->nazwa;
				$this->view->seo_tytul = $inwestycja->meta_tytul;
				$this->view->seo_opis = $inwestycja->meta_opis;
				$this->view->seo_slowa = $inwestycja->meta_slowa;

				//Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/'.$page->tag.'/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				//Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
				
				//lokale uslugowe w inwestycji budynkowe
				if($inwestycja->typ == 2 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				//lokale uslugowe w inwestycji osiedlowej
				if($inwestycja->typ == 1 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer && $id_budynek ==  $pieterko->id_budynek){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$pieterko->id_budynek.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				//Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));
		
				foreach($inwespage as $page) {
					if($page->tag == $tag_inwest_page) {
						
						$this->view->strona_nazwa = $page->nazwa;
						$this->view->strona_tekst = $page->tekst;
						
						$this->view->pagetag = $page->tag;
						
						$this->view->title = ' - '.$inwestycja->nazwa.' - '.$page->nazwa;
						$this->view->titles = $page->tytul;
						$this->view->slowa = $page->slowa;
						$this->view->opis = $page->opis;
					}
				}
				
				$result = $db->select()
				->from(array('n' => 'inwestycje_news'), array('id', 'id_inwest', 'plik', 'data', 'status', 'tytul', 'wprowadzenie'))
				->where('n.status =?', 1)
				->where('n.id_inwest = ?', $inwestycja->id)
				->order('n.data DESC');
				
				$pageNo = $this->_getParam('strona', 1);
				$paginator = Zend_Paginator::factory($result);
				$paginator->setItemCountPerPage(5);
				$paginator->setCurrentPageNumber($pageNo);
				$this->view->news = $paginator;

			}
		}
		
		public function dziennikwpisAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$tag_inwest_page = $this->getRequest()->getParam('tag_inwest_page');
			$news_id = (int)$this->getRequest()->getParam('news_id');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));
			
			$this->view->searchonplan = 1;

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				//Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');
				
				//SEO
				$this->view->strona_nazwa = $inwestycja->nazwa;
				$this->view->strona_tytul = ' - '.$inwestycja->nazwa;
				$this->view->seo_tytul = $inwestycja->meta_tytul;
				$this->view->seo_opis = $inwestycja->meta_opis;
				$this->view->seo_slowa = $inwestycja->meta_slowa;
				
				//Aktywne boczne menu
				$this->view->pagetag = 'dziennik-inwestycji';

				//Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/'.$page->tag.'/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				//Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
				
				//lokale uslugowe w inwestycji budynkowe
				if($inwestycja->typ == 2 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
							
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				//lokale uslugowe w inwestycji osiedlowej
				if($inwestycja->typ == 1 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer && $id_budynek ==  $pieterko->id_budynek){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$pieterko->id_budynek.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
							
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
			
				//Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));

				$result = $db->select()
				->from(array('n' => 'inwestycje_news'))
				->where('n.status =?', 1)
				->where('n.id = ?', $news_id);
				$news = $this->view->news = $db->fetchRow($result);
				
				$this->view->inne = $db->fetchAll($db->select()->from('inwestycje_news')->where('status = ?', 1)->where('id_inwest = ?', $inwestycja->id)->where('id != ?', $news->id)->order('data DESC'));

				foreach($inwespage as $page) {
					if($page->tag == $tag) {
						
						$this->view->strona_nazwa = $page->nazwa;
						$this->view->strona_tekst = $page['tekst'];
						$this->view->title = ' - '.$inwestycja->nazwa.' - '.$news['tytul'];
						$this->view->titles = $news['tytul'];
						$this->view->slowa = $news['slowa'];
						$this->view->opis = $news['opis'];
	
					}
				}

			}
		}
			
/* Strona tekstowa inwestycji */		
		public function inwestycjastronaAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$this->view->fastmail = 1;
			$this->view->searchonplan = 1;

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$tag_inwest_page = $this->getRequest()->getParam('tag_inwest_page');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if(!$inwestycja) {
		
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				
				// Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				// Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				// Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
				
				// lokale uslugowe w inwestycji budynkowe
				if($inwestycja->typ == 2 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				// lokale uslugowe w inwestycji osiedlowej
				if($inwestycja->typ == 1 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer && $id_budynek ==  $pieterko->id_budynek){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$pieterko->id_budynek.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				// Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));
		
				foreach($inwespage as $page) {
					if($page->tag == $tag_inwest_page) {

						// SEO
						$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$page->nazwa;
						$this->view->strona_nazwa = $page->nazwa;
						$this->view->strona_tekst = $page->tekst;
						$this->view->pagetag = $page->tag;
						$this->view->seo_tytul = $page->meta_tytul;
						$this->view->seo_slowa = $page->meta_slowa;
						$this->view->seo_opis = $page->meta_opis;
					}
				}
				
			}
		}
		
/* Lokalizacja */		
		public function lokalizacjaAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$tag_inwest_page = $this->getRequest()->getParam('tag_inwest_page');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				// Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				// Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				// Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 
				
				// lokale uslugowe w inwestycji budynkowe
				if($inwestycja->typ == 2 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
							
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				// lokale uslugowe w inwestycji osiedlowej
				if($inwestycja->typ == 1 && $inwestycja->uslugowe == 1) {
					$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja->id)->order('numer ASC'));
					
					$listaUslug = '';
					foreach($pieterka as $pieterko) {
						if($pieterko->typ == 2) {
							if($numer_pietro == $pieterko->numer && $id_budynek ==  $pieterko->id_budynek){ $active = ' active';} else {$active = '';}
							$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="'.$this->baseUrl.'/i/'.$inwestycja->tag.'/b/'.$pieterko->id_budynek.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/">'.$pieterko->nazwa.'</a></li>';
							
						}
					}
					$this->view->uslugowki = $listaUslug;
				}
				
				// Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));
		
				foreach($inwespage as $page) {
					if($page->tag == $tag_inwest_page) {
						
						// SEO
						$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$page->nazwa;
						
						$this->view->strona_nazwa = $page->nazwa;
						$this->view->strona_tekst = $page->tekst;
						
						$this->view->pagetag = $page->tag;

						$this->view->seo_tytul = $page->meta_tytul;
						$this->view->seo_slowa = $page->meta_slowa;
						$this->view->seo_opis = $page->meta_opis;
						
						$this->view->mapa = $db->fetchAll($db->select()->from('inwestycje_markery')->where('id_inwest =?', $inwestycja->id));
					}
				}
				
			}
		}
			
/* Kontakt */		
		public function kontaktAction() {
			$this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			// Wywolanie JS
			$this->view->validation = 1;
			$this->view->searchonplan = 1;

			$tag = $this->view->wybranytag = $this->getRequest()->getParam('tag_inwest');
			$tag_inwest_page = $this->getRequest()->getParam('tag_inwest_page');
			$inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from(array('i' => 'inwestycje'))->where('i.tag = ?', $tag));

			if(!$inwestycja) {
			
				$front = Zend_Controller_Front::getInstance();
				$request = $front->getRequest();
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');

				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
				
			} else {
				// Menu
				$page = $db->fetchRow($db->select()->from(array('s' => 'strony'), array('id', 'tag', 'nazwa'))->where('s.id = ?', $this->menuPageId));
				$this->getRequest()->setParam('sitetag', 'oferta');
				$this->getRequest()->setParam('tag', 'oferta');

				// Pobierz wszystkie inwestycje
				$this->view->inwestycje = $db->fetchAll($db->select()->from('inwestycje', array('nazwa', 'tag', 'status'))->where('status = ?', 1));
				
				// Schema breadcrumbs
				$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->baseUrl.'/oferta/"><span itemprop="name">'.$page->nazwa.'</span></a><meta itemprop="position" content="2" /></li><li class="sep"></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$inwestycja->nazwa.'</b><meta itemprop="position" content="3" /></li>';
				
				// Plan inwestycji
				$this->view->plan = $db->fetchRow($db->select()->from('inwestycje_plan')->where('id_inwest = ?', $inwestycja->id)); 

				//Lokale uslugowe
				if($inwestycja->uslugowe == 1) {
					$this->view->uslugowki = lokale_uslugowe($inwestycja->typ, $inwestycja->id, $inwestycja->tag);
				}

				// Strony tekstowe inwestycji
				$inwespage = $this->view->inwestpage = $db->fetchAll($db->select()->from(array('inwestycje_strony'))->where('id_inwest = ?', $inwestycja->id)->where('status = ?', 1)->order('sort ASC'));
		
				foreach($inwespage as $page) {
					if($page->tag == $tag_inwest_page) {

						// SEO
						$this->view->strona_nazwa = $page->nazwa;
						$this->view->strona_h1 = $inwestycja->nazwa.' - '.$page->nazwa;
						$this->view->strona_tytul = ' - '.$inwestycja->nazwa.' - '.$page->nazwa;
						$this->view->seo_tytul = $page->meta_tytul;
						$this->view->seo_opis = $page->meta_opis;
						$this->view->seo_slowa = $page->meta_slowa;
						$this->view->strona_tekst = $page->tekst;
						
						$this->view->pagetag = 'kontakt';
	
					}
				}
				
				//Pobranie regulek
				$url = 'https://madey.developro.pl/api/regulki/';
				$login = 'admin';
				$password = 'admin';
				$id = $inwestycja->id;
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
				$regulki = $this->view->regulkis = json_decode($regulkiCURL, true);
				
				$this->view->rodo = $db->fetchRow($db->select()->from('rodo_ustawienia')->where('id =?', 1));
				
				if ($this->_request->isPost()) {

					$ip = $_SERVER['REMOTE_ADDR'];
					$adresip = $db->fetchRow($db->select()->from('blokowanie')->where('ip = ?', $ip));
					
					$grecaptcha = $this->_request->getPost('g-recaptcha-response');
					$secret = '6LcbhL8UAAAAAN5gd0KCO5MI8rrNMRRqHYFCe2ow';
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$grecaptcha);
					$responseData = json_decode($verifyResponse);
		
					if($responseData->success){
						
					if($adresip){  } else {

						$imie = $this->_request->getPost('form_imie');
						$email = $this->_request->getPost('form_email');
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
							->setFrom($ustawienia->email, $inwestycja->nazwa)
							->setReplyTo($email, $imie)
							->addTo($inwestycja->email, 'Adres odbiorcy')
							->setSubject($ustawienia->domena.' - Zapytanie z inwestycji: '.$inwestycja->nazwa)
							->setBodyHTML('
							<div style="width:550px;border:1px solid #ececec;padding:0 20px;margin:0 auto;font-family:Arial;font-size:14px;line-height:27px">
							<p style="text-align:center">'.$ustawienia->nazwa.'</p>
							<p><b>Wiadomość wysłana: '. $datadodania .'</b></p>
							<hr style="border:0;border-bottom:1px solid #ececec" />
							<p><b>Inwestycja:</b> '.$inwestycja->nazwa.'<br />
							<b>Imię i nazwisko:</b> '.$imie.'<br />
							<b>E-mail:</b> '. $email .'<br />
							<b>Telefon:</b> '. $telefon .'<br />
							<b>IP:</b> '. $ip .'<br />
							<hr style="border:0;border-bottom:1px solid #ececec" />
							<h3 style="margin-bottom:5px">'. $temat .'</h3>
							<p style="margin-top:0">'. $wiadomosc .'</p>
							</div>');

							$mail->send();
						}
						$this->view->message = '1';
						
						$stat = array(
							'akcja' => 1,
							'miejsce' => 5,
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
						$formData['id_inwest'] = $inwestycja->id;
						$formData['ip'] = $_SERVER['REMOTE_ADDR'];
						$formData['www'] = $_SERVER['REQUEST_URI'];
						$formData['imie'] = $formData['form_imie'];
						unset($formData['form_imie']);
						$formData['email'] = $formData['form_email'];
						unset($formData['form_email']);
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
		}

/* Wyszukiwarka mieszkan */		
	   public function szukajAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$this->view->hidetop = 1;
			$this->view->listamieszkan = 1;
			$this->view->canonical = $this->baseUrl.'/szukaj/';
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 4));
			$this->view->strona_nazwa = $page->nazwa;

			$this->view->title = ' - '.$page->nazwa;
			$this->view->titles = $page->tytul;
			$this->view->slowa = $page->slowa;
			$this->view->opis = $page->opis;
			
			$this->view->breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="name">'.$page->nazwa.'</b><meta itemprop="position" content="2" /></li>';
	
			$this->view->nofollow = 1;

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
			
			// Pobier z URL
			$this->view->id_inwest = $id_inwest = $this->_request->getParam('s_inwest');
			$this->view->s_metryod = $s_metryod = $this->_request->getParam('s_metryod');
			$this->view->s_metrydo = $s_metrydo = $this->_request->getParam('s_metrydo');
			$this->view->s_pokoje = $s_pokoje = $this->_request->getParam('s_pokoje');

			// Parametr inwestycji
			if ($id_inwest) {
				$select_pomieszczenia->where('id_inwest =?', $id_inwest);
			}
			
			// Pokoje
			if ($s_pokoje) {
				$select_pomieszczenia->where('pokoje =?', $s_pokoje);
			}

			// Metraz od
			if ($s_metryod) {
				$select_pomieszczenia->where('szukaj_metry >=?', $s_metryod);
			}
			
			// Metraz do
			if ($s_metrydo) {
				$select_pomieszczenia->where('szukaj_metry <=?', $s_metrydo);
			}

			$select_pomieszczenia->where('status !=?', 2);
			$this->view->powierzchnia = $result = $db->fetchAll($select_pomieszczenia);
			$this->view->wynik = count($result);

			$inwestycje = $this->view->inwestycje = $db->fetchAll($db->select()->from('inwestycje')->where('status IN(1,3)'));

			if($s_metryod || $s_metrydo || $s_pokoje) {

					$stat = array(
						'id_inwest' => '',
						'akcja' => 2,
						'miejsce' => 1,
						'pietro' => '',
						'pokoje' => $s_pokoje,
						'pow_od' => $s_metryod,
						'pod_do' => $s_metrydo,
						'data' => date("d.m.Y - H:i:s"),
						'timestamp' => date("d-m-Y"),
						'godz' => date("H"),
						'dzien' => date("d"),
						'msc' => date("m"),
						'rok' => date("Y"),
						'tekst' => '',
						'ip' => $_SERVER['REMOTE_ADDR']
					);
					$db->insert('statystyki', $stat);
					
			}
			
			// echo '<pre>';
			// print_r($result);
			// echo '</pre>';
			
	   }

}