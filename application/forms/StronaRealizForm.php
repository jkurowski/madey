<?php
class Form_StronaRealizForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('strona');
		$this->setAttrib('class', 'mainForm');

		$status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status')
		->addMultiOption (1, 'Pokaż')
		->addMultiOption (0, 'Ukryj')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Tytuł strony')
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
		
        $ikonka = new Zend_Form_Element_Text('ikonka');
        $ikonka->setLabel('Ikonka: <a href="http://ionicons.com/" target="_blank">http://ionicons.com/</a>')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
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
        $meta_tytul->setLabel('Nagłówek<br /><span style="font-size:11px;color:#A8A8A8">Meta tag - Title</span>')
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
        $meta_opis->setLabel('Opis strony<br /><span style="font-size:11px;color:#A8A8A8">Meta tag - Description</span>')
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
		
        $tekst = new Zend_Form_Element_Textarea('tekst');
        $tekst->setLabel('Treść')
		->setRequired(false)
		->setAttrib('rows', 27)
		->setAttrib('cols', 100)
		->setAttrib('class', 'editor')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
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
        $this->addElements(array(
			$status,
			$nazwa,
			// $ikonka,
			$meta_tytul,
			$meta_slowa,
			$meta_opis,
			$tekst,
			$submit
		));
    }
}