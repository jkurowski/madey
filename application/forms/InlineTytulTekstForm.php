<?php
class Form_InlineTytulTekstForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('eventForm1');
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
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))));

        $tekst = new Zend_Form_Element_Textarea('modaleditor');
        $tekst->setLabel('Tekst')
		->setRequired(true)
		->setAttrib('rows', 19)
		->setAttrib('cols', 100)
		->setAttrib('class', 'editor1')
		->setAttrib('id', 'modaleditor1')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))));
		
	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'btn btn-primary')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form'));
        $this->addElements(array($nazwa, $tekst, $submit));
    }
}