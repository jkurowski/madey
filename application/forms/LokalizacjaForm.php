<?php
class Form_LokalizacjaForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('lokalizacja');
		$this->setAttrib('class', 'mainForm');

		$grupa = new Zend_Form_Element_Select('grupa');
        $grupa->setLabel('Grupa')
		->addMultiOption ('1', 'Inwestycja')
		->addMultiOption ('2', 'Autobus')
		->addMultiOption ('3', 'Tramwaj')
		->addMultiOption ('4', 'Basen')
		->addMultiOption ('5', 'Sklepy')
		->addMultiOption ('6', 'Szkoła, przedszkole')
		->addMultiOption ('7', 'Park')
		->addMultiOption ('8', 'Szpital, lekarz')
		->addMultiOption ('9', 'Restauracja')
		->addMultiOption ('10', 'Biblioteka')
		->addMultiOption ('11', 'Sport')
		->addMultiOption ('12', 'Stacja paliw')
		->addMultiOption ('13', 'Inne')
		->addMultiOption ('14', 'Poczta')
		->addMultiOption ('15', 'Parking')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Nazwa')
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
		
	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		// Polskie tlumaczenie errorów
		$polish = kCMS_Polish::getPolishTranslation();
		$translate = new Zend_Translate('array', $polish, 'pl');
		$this->setTranslator($translate);
		
		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array($grupa, $nazwa, $lat, $lng, $zoom, $adres, $submit));
    }
}