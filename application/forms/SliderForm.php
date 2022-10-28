<?php
class Form_SliderForm extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('nowypanel');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('class', 'mainForm');

        $inwest_status = new Zend_Form_Element_Select('inwest_status');
        $inwest_status->setLabel('Status inwestycji')
            ->addMultiOption('0','Brak')
            ->addMultiOption('1','W sprzedaży')
            ->addMultiOption('2','Zakończona')
            ->addMultiOption('3','Planowana')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $galeria_id = new Zend_Form_Element_Select('galeria_id');
        $galeria_id->setLabel('Galeria')
            ->addMultiOption (NULL, 'Brak')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
        $db = Zend_Registry::get('db');
        $katalog = $db->fetchAll($db->select()->from('galeria')->order( 'nazwa ASC' ));

        foreach ($katalog as $listItem) {
            $galeria_id->addMultiOption($listItem->id, $listItem->nazwa);
        }

        $tytul = new Zend_Form_Element_Text('tytul');
        $tytul->setLabel('Tytuł')
            ->setRequired(true)
            ->setAttrib('size', 33)
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[required, maxSize[110]]')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link')
            ->setRequired(true)
            ->setAttrib('size', 33)
            ->addValidator('stringLength', false, array(1, 255))
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[required]')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $link_tytul = new Zend_Form_Element_Text('link_tytul');
        $link_tytul->setLabel('Nazwa przycisku')
            ->setRequired(true)
            ->setAttrib('size', 33)
            ->addValidator('stringLength', false, array(1, 255))
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[required]')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $tekst = new Zend_Form_Element_Text('tekst');
        $tekst->setLabel('Tekst')
            ->setRequired(true)
            ->setAttrib('size', 103)
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[required, maxSize[185]]')
            ->addValidator('stringLength', false, array(1, 185))
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $dopisek = new Zend_Form_Element_Text('dopisek');
        $dopisek->setLabel('Dopisek')
            ->setRequired(false)
            ->setAttrib('size', 103)
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[maxSize[185]]')
            ->addValidator('stringLength', false, array(1, 185))
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $obrazek = new Zend_Form_Element_File('obrazek');
        $obrazek->setLabel('Plik')
            ->setRequired(false)
            ->addValidator('NotEmpty')
            ->addValidator('Extension', false, 'jpg, png, jpeg, bmp, gif, webp')
            ->addValidator('Size', false, 1402400)
            ->setDecorators(array(
                'File',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz panel')
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
            $inwest_status,
            $galeria_id,
            $tytul,
            $link,
            $link_tytul,
            $tekst,
            $dopisek,
            $obrazek,
            $submit
        ));
    }
}