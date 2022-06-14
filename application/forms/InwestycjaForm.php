<?php
class Form_InwestycjaForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('objekt');
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('class', 'mainForm');
		
		$typ = new Zend_Form_Element_Select('typ');
        $typ->setLabel('Typ')
		->addMultiOption('1','Inwestycja osiedlowa')
		->addMultiOption('2','Inwestycja budynkowa')
		->addMultiOption('3','Inwestycja z domami')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status')
		->addMultiOption('1','Inwestycja w sprzedaży')
		->addMultiOption('2','Inwestycja zakończona')
		->addMultiOption('3','Inwestycja planowana')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$uslugowe = new Zend_Form_Element_Select('uslugowe');
        $uslugowe->setLabel('Lokale usługowe')
		->addMultiOption('2','Nie')
		->addMultiOption('1','Tak')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $data_koniec = new Zend_Form_Element_Text('data_koniec');
        $data_koniec->setLabel('Termin zakończenia')
		->setRequired(false)
		->setAttrib('size', 103)
		->addValidator('stringLength', false, array(3, 255))
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $data_start = new Zend_Form_Element_Text('data_start');
        $data_start->setLabel('Termin rozpoczęcia')
		->setRequired(false)
		->setAttrib('size', 103)
		->addValidator('stringLength', false, array(3, 255))
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $postep = new Zend_Form_Element_Textarea('postep');
        $postep->setLabel('Postęp<br /><span style="font-size:11px;color:#A8A8A8">xx;tytuł;opis - gdzie xx to % postępu</span>')
		->setRequired(false)
		->setAttrib('rows', 17)
		->setAttrib('cols', 100)
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $zakres_cen = new Zend_Form_Element_Text('zakres_cen');
        $zakres_cen->setLabel('Zakres cen w wyszukiwarce xx-xx<br /><span style="font-size:11px;color:#A8A8A8">(liczby oddzielone przecinkiem)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $zakres_powierzchnia = new Zend_Form_Element_Text('zakres_powierzchnia');
        $zakres_powierzchnia->setLabel('Zakres powierzchni w wyszukiwarce xx-xx<br /><span style="font-size:11px;color:#A8A8A8">(liczby oddzielone przecinkiem)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $zakres_pokoje = new Zend_Form_Element_Text('zakres_pokoje');
        $zakres_pokoje->setLabel('Zakres pokoi w wyszukiwarce<br /><span style="font-size:11px;color:#A8A8A8">(liczby oddzielone przecinkiem)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Nazwa inwestycji')
		->setRequired(true)
		->setAttrib('size', 103)
		->setFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'validate[required]')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $lista = new Zend_Form_Element_Text('lista');
        $lista->setLabel('Krótki opis na liście')
		->setRequired(true)
		->setAttrib('size', 103)
		->setFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'validate[required]')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_slowa = new Zend_Form_Element_Text('meta_slowa');
        $meta_slowa->setLabel('Słowa kluczowe<br /><span style="font-size:11px;color:#A8A8A8">Meta tag - Keywords</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_tytul = new Zend_Form_Element_Text('meta_tytul');
        $meta_tytul->setLabel('Tytuł strony<br /><span style="font-size:11px;color:#A8A8A8">(Title)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_opis = new Zend_Form_Element_Text('meta_opis');
        $meta_opis->setLabel('Opis strony<br /><span style="font-size:11px;color:#A8A8A8">(Description)</span>')
		->setRequired(false)
		->setAttrib('size', 123)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Biuro: adres e-mail')
		->setRequired(false)
		->setAttrib('class', 'validate[required]')
		->setAttrib('size', 47)
		->addValidator('NotEmpty')
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $telefon = new Zend_Form_Element_Text('telefon');
        $telefon->setLabel('Biuro: telefon')
		->setRequired(false)
		->setAttrib('size', 47)
		->addValidator('NotEmpty')
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $biuro = new Zend_Form_Element_Text('biuro');
        $biuro->setLabel('Biuro: adres biura')
		->setRequired(false)
		->setAttrib('size', 47)
		->addValidator('NotEmpty')
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$obrazek = new Zend_Form_Element_File('obrazek');
		$obrazek->setLabel('Miniaturka<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 950px / 530px)</span>')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$obrazek3 = new Zend_Form_Element_File('obrazek3');
		$obrazek3->setLabel('Obrazek nagłówka<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 1920px / 600px)</span>')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$logo = new Zend_Form_Element_File('logo');
		$logo->setLabel('Logo inwestycji')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));


		$lokalizacja_plik = new Zend_Form_Element_File('lokalizacja_plik');
		$lokalizacja_plik->setLabel('Lokalizacja: obrazek<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 755px / 675px)</span>')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$inwestycja_plik = new Zend_Form_Element_File('inwestycja_plik');
		$inwestycja_plik->setLabel('Obrazek inwestycji<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 960px / 630px)</span>')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$dlaczego_plik = new Zend_Form_Element_File('dlaczego_plik');
		$dlaczego_plik->setLabel('Dlaczego warto: obrazek<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 755px / 675px)</span>')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $lat = new Zend_Form_Element_Text('lat');
        $lat->setLabel('Szerokość geograficzna')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $lng = new Zend_Form_Element_Text('lng');
        $lng->setLabel('Długość geograficzna')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $zoom = new Zend_Form_Element_Text('zoom');
        $zoom->setLabel('Zbliżenie')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $adres = new Zend_Form_Element_Text('addresspicker_map');
        $adres->setLabel('Adres')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $tekst = new Zend_Form_Element_Textarea('tekst');
        $tekst->setLabel('Opis inwestycji')
		->setRequired(false)
		->setAttrib('rows', 17)
		->setAttrib('cols', 100)
		->setAttrib('class', 'editor')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
        $opis = new Zend_Form_Element_Textarea('opis');
        $opis->setLabel('Opis inwestycji po zakończeniu')
		->setRequired(false)
		->setAttrib('rows', 17)
		->setAttrib('cols', 100)
		->setAttrib('class', 'editor')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
        $dlaczegowarto = new Zend_Form_Element_Textarea('dlaczegowarto');
        $dlaczegowarto->setLabel('Dlaczego warto')
		->setRequired(false)
		->setAttrib('rows', 17)
		->setAttrib('cols', 100)
		->setAttrib('class', 'smalleditor')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
        $lokalizacja = new Zend_Form_Element_Textarea('lokalizacja');
        $lokalizacja->setLabel('Lokalizacja')
		->setRequired(false)
		->setAttrib('rows', 17)
		->setAttrib('cols', 100)
		->setAttrib('class', 'smalleditor')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
        $koniec_mieszkania = new Zend_Form_Element_Text('koniec_mieszkania');
        $koniec_mieszkania->setLabel('Zakończenie - ilość mieszkań')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $koniec_rok = new Zend_Form_Element_Text('koniec_rok');
        $koniec_rok->setLabel('Zakończenie - rok zakończenia')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $koniec_miasto = new Zend_Form_Element_Text('koniec_miasto');
        $koniec_miasto->setLabel('Zakończenie - miasto')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		// Polskie tlumaczenie errorów
		$polish = kCMS_Polish::getPolishTranslation();
		$translate = new Zend_Translate('array', $polish, 'pl');
		$this->setTranslator($translate);
		
 	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form'));
        $this->addElements(array(
			$typ,
			$status,
			$lat,
			$lng,
			// $zoom,
			$adres,
			$uslugowe,
			$nazwa,
			$meta_slowa,
			$meta_tytul,
			$meta_opis,
			// $data_start,
			// $data_koniec,
			// $postep,
			$zakres_cen,
			$zakres_powierzchnia,
			$zakres_pokoje,
			$email,
			// $telefon,
			// $biuro,
			$obrazek,
			$obrazek3,
			// $logo,
			$inwestycja_plik,
			$koniec_mieszkania,
			$koniec_rok,
			$koniec_miasto,
			$lista,
			$tekst,
			$opis,
			// $lokalizacja_plik,
			// $lokalizacja,
			// $dlaczego_plik,
			// $dlaczegowarto,
			$submit
		));
    }
}