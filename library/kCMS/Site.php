<?php
require_once 'Zend/Controller/Action.php';
abstract class kCMS_Site extends Zend_Controller_Action {

    public function init() {
		$front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
		$action = $this->view->action = $request->getActionName();
		$controller = $this->view->controller = $request->getControllerName();
		$this->view->user = Zend_Auth::getInstance()->getIdentity();
	
        $this->view->baseUrl = $this->baseUrl = $this->_request->getBaseUrl();
		$db = Zend_Registry::get('db');

		$this->view->header = $db->fetchRow($db->select()->from('ustawienia'));

		// if($_SESSION["schowek"]){
			
			// $this->view->ileschowek = count($_SESSION["schowek"]);
		// }

		//$this->view->fmenu = $db->fetchAll($db->select()->from('strony', array('nazwa', 'tag', 'menu', 'sort'))->where('menu = ?', 9)->order('sort ASC'));

		//$inwest = $db->select()->from('inwestycje', array('nazwa', 'adres_miasto', 'adres_ulica', 'tag', 'status'))->where('status = ?', 1);
		//$this->view->finwestycje = $db->fetchAll($inwest);
	
		$menu = new kCMS_MenuBuilder();
		Zend_Registry::set('querymenu', $menu);
		
		$inlineArray = $db->fetchAll($db->select()->from('inline'));
		Zend_Registry::set('inlineStore', $inlineArray);		
		$inlineIconsArray = $db->fetchAll($db->select()->from('inline_icons')->order('sort ASC'));
		Zend_Registry::set('inlineIcons', $inlineIconsArray);
		$inlineImgArray = $db->fetchAll($db->select()->from('inline_img'));
		Zend_Registry::set('inlineImg', $inlineImgArray);
		
		function zlotowka($value) {
			$value = str_replace('zĹ‚', 'zł', trim($value));
			return $value;
		}
		
		function lamane($value) {
			$value = str_replace(';', '<br>', $value);
			return $value;
		}

		function zmiana($value) {
            $value = strtr($value, array('ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z', 'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z'));
            $value = str_replace(' ', '-', trim($value));
            $value = preg_replace('/[^a-zA-Z0-9\-_]/', '', (string) $value);
            $value = preg_replace('/[\-]+/', '-', $value);
            $value = stripslashes($value);
            return urlencode(strtolower($value));
		}

		function findKeyValue($array, $needle, $val, $found = false){
			foreach ($array as $key => $item){
				if(is_array($item)){
					$found = findKeyValue($item, $needle, $val, $found);
				}
				if( ! empty($key) && $key == $needle && strpos($item, $val) !== false){
					return true;
				}
			}

			return $found;
		}

		function galeria($input) {
			$db = Zend_Registry::get('db');
			$images = $db->fetchAll($db->select()->from('galeria_zdjecia')->order( 'sort ASC' )->where('id_gal =?', $input[2]));
			
			if($input[1] == 'slider-galeria') {
				$html = '<div class="slider-galeria">';
                $i = 0;
				foreach ($images as $key => $value) {
					$html.= '<a href="/files/galeria/big/'.$value['plik'].'" class="swipebox" rel="gallery-1'.$input[2].'" title="'.$value['nazwa'].'"><img src="/files/galeria/thumbs/'.$value['plik'].'"></a>';
                    if(++$i > 6) break;
				}
				$html.= '</div>';
			}

			if($input[1] == 'galeria') {
				$html = '<div class="galeria galeria-tekst clearfix">';
				foreach ($images as $value) {
					$html.= '<div class="insidegal"><a href="/files/galeria/big/'.$value->plik.'" class="swipebox" rel="gallery-1'.$input[2].'" title="'.$value->nazwa.'"><img src="/files/galeria/thumbs/'.$value->plik.'"><div></div></a></div>';
				}
				$html.= '<div class="clr"></div></div>';
			}
			if($input[1] == 'slider') { 
				$html.= '<div class="sliderWrapper"><ul class="rslides list-unstyled">';
				foreach ($images as $value) {
					$html.= '<li><a href="/files/galeria/big/'.$value->plik.'" title="'.$meta_tytul.'" class="swipebox" rel="gallery-2'.$input[2].'"><img src="/files/galeria/big/'.$value->plik.'" alt="'.$meta_tytul.'" /></a></li>';
				}
				$html.= '</ul><div class="clr"></div></div>';
			}
			if($input[1] == 'karuzela') { 
				$html.= '<div class="karuzelaWrapper"><ul class="list-unstyled mb-0">';
				foreach ($images as $value) {
					$html.= '<li><a href="/files/galeria/big/'.$value->plik.'" title="'.$meta_tytul.'" class="swipebox" rel="gallery-3'.$input[2].'"><img src="/files/galeria/thumbs/'.$value->plik.'" alt="'.$meta_tytul.'" /></a></li>';
				}
				$html.= '</ul></div>';
			}
			return($html);
		}
		
		//******** RODO z -> ********//
		function historylog($nazwa, $mail, $ip, $przegladarka, $regulki) {
			$db = Zend_Registry::get('db');
			
			$klient = $db->fetchRow($db->select()->from('rodo_klient')->where('mail =?', $mail));
			if($klient){
				$historia = array(
					'data_aktualizacji' => date("Y-m-d H:i:s"),
					'ip' => $ip,
					'host' => gethostbyaddr($ip),
					'przegladarka' => $przegladarka,
				);
				$db->update('rodo_klient', $historia, 'id = '.$klient->id);
				
				foreach($regulki as $key => $number){
					$getId = preg_replace('/[^0-9]/', '', $number);

					$regulkaArchiv = $db->fetchRow($db->select()->from('rodo_regulki_klient')->where('id_regulka = ?', $getId)->where('id_klient = ?', $klient->id));

					if($regulkaArchiv){

						$arrayRegulka = json_decode(json_encode($regulkaArchiv),true);
						unset($arrayRegulka['id']);
						$arrayRegulka['data_anulowania'] = strtotime(date("Y-m-d H:i:s"));
		
						$db->insert('rodo_regulki_archiwum', $arrayRegulka);
		
						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $klient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);
						$db->update('rodo_regulki_klient', $dataRodo, 'id_regulka = '.$getId);
				
					} else {

						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $klient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);	
						$db->insert('rodo_regulki_klient', $dataRodo);
					}
				}

			} else {
				$historia = array(
					'nazwa' => $nazwa,
					'mail' => $mail,
					'ip' => $ip,
					'host' => gethostbyaddr($ip),
					'przegladarka' => $przegladarka,
					'data_dodania' => date("Y-m-d H:i:s")
				);

				$db->insert('rodo_klient', $historia);
				$lastId = $db->lastInsertId();
				
				$newklient = $db->fetchRow($db->select()->from('rodo_klient')->where('id =?', $lastId));
				
				foreach($regulki as $key => $number){
					$getId = preg_replace('/[^0-9]/', '', $number);

					$regulkaArchiv = $db->fetchRow($db->select()->from('rodo_regulki_klient')->where('id_regulka = ?', $getId)->where('id_klient = ?', $newklient->id));

					if($regulkaArchiv){

						$arrayRegulka = json_decode(json_encode($regulkaArchiv),true);
						unset($arrayRegulka['id']);
						$arrayRegulka['data_anulowania'] = strtotime(date("Y-m-d H:i:s"));
		
						$db->insert('rodo_regulki_archiwum', $arrayRegulka);
		
						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $newklient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);
						$db->update('rodo_regulki_klient', $dataRodo, 'id_regulka = '.$getId);
				
					} else {

						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $newklient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);	
						$db->insert('rodo_regulki_klient', $dataRodo);
					}
				}

			}
		}
		//******** RODO ********//
		
		function zmienobrazek($value) {
				$value = strtr($value, array('ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z', 'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z'));
				$value = preg_replace( "/[^a-z0-9\._]+/", "-", strtolower($value));
				$value = str_replace(' ', '-', trim($value));
				$value = str_replace('_', '-', trim($value));
				$value = preg_replace('/[\-]+/', '-', $value);
				$value = stripslashes($value);
				return $value;
			}

		function zmiennazwe($value) {
				$filename = strtolower($value);
				$exts = explode("[/\\.]", $filename);
				$n = count($exts)-1; 
				$exts = $exts[$n];
				return $exts;
		}
		
		function miesiace( $time ){
			$miesiac = array( '', 'STY', 'LUT', 'MAR', 'KWI', 'MAJ', 'CZE', 'LIP', 'SIE', 'WRZ', 'PAŹ', 'LIS', 'GRU' );
			return $miesiac[$time];
		}
	
		function replace($input) {
			$input = preg_replace_callback('/\[galeria=(.*)](.*)\[\/galeria\]/', 'galeria', $input);
			$input = str_replace("</div></p>","</div>",$input);
            return str_replace("<p><div","<div",$input);
		}

        function previewParser($string, $len) {
            $pattern_clear = array(
                '@(\[)(.*?)(\])@si',
                '@(\[/)(.*?)(\])@si'
            );

            $replace_clear = array(
                '',
                ''
            );

            $string = preg_replace($pattern_clear, $replace_clear, $string);
            if (strlen($string) > $len) {
                $result = mb_substr($string, 0, $len, "UTF-8") . ' ...';
            } else {
                $result = $string;
            }
            return $result;
        }

		function lokale_uslugowe($inwestycja_typ, $inwestycja_id, $inwestycja_tag) {
			$db = Zend_Registry::get('db');
			$pieterka = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $inwestycja_id)->order('numer ASC'));
			
			$listaUslug = '';
			foreach($pieterka as $pieterko) {
				if($pieterko->typ == 2) {
					if($inwestycja_typ == 1){
						if($numer_pietro == $pieterko->numer && $id_budynek ==  $pieterko->id_budynek){ $active = ' active';} else {$active = '';}
						$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="http://testy.4dl.pl/i/'.$inwestycja_tag.'/b/'.$pieterko->id_budynek.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/"><i class="ion-icons">&#xf3f7;</i>'.$pieterko->nazwa.'</a></li>';
					}

					if($inwestycja_typ == 2){
						if($numer_pietro == $pieterko->numer){ $active = ' active';} else {$active = '';}
						$listaUslug .= '<li class="subsidemenu'.$active.'"><a href="http://testy.4dl.pl/i/'.$inwestycja->tag.'/p/'.$pieterko->numer.'/typ/'.$pieterko->typ.'/"><i class="ion-icons">&#xf3f7;</i>'.$pieterko->nazwa.'</a></li>';
					}
					
				}
			}

			return $listaUslug;
		}
	}
}
?>