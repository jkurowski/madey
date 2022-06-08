<?php
class Form_InlineIconForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('eventForm3');
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('class', 'mainForm');

        $nazwa = new Zend_Form_Element_Text('modaltytul');
        $nazwa->setLabel('Tytuł sekcji')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required] form-control')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group form-modaltytul'))));
		
        $tekst = new Zend_Form_Element_Text('modaleditor');
        $tekst->setLabel('Tekst')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required] form-control')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group form-modaleditor'))));
		
        $link = new Zend_Form_Element_Text('modallink');
        $link->setLabel('Button link')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required] form-control')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group form-modallink'))));
		
        $linkbutton = new Zend_Form_Element_Text('modallinkbutton');
        $linkbutton->setLabel('Button tekst')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required] form-control')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group form-modallinkbutton'))));
		
		$obrazek = new Zend_Form_Element_File('obrazek');
		$obrazek->setLabel('Obrazek')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group form-obrazek'))));
		
	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'btn btn-primary')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form'));
        $this->addElements(array(
			$nazwa,
			$tekst,
			$link,
			$linkbutton,
			$obrazek, 
			$submit
		));
    }
}