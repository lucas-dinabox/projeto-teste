<?php
/**
 * FormDynamicSessionCriteriaView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormDynamicSessionCriteriaView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new BootstrapFormBuilder('form_hierarchical');
        $this->form->setFormTitle(_t('Hierarchical combo'));
        
        $city_id = new TDBCombo('city_id', 'samples', 'City', 'id', 'name', 'name');
        
        $filter = new TCriteria;
        $filter->add(new TFilter('city_id', '=', "'{session.city_id_filter}'"));
        
        $customer_id = new TDBUniqueSearch('customer_id', 'samples', 'Customer', 'id', 'name', 'name', $filter);
        
        $city_id->enableSearch();
        $customer_id->setMinLength(1);
        
        // add the fields inside the form
        $this->form->addFields( [new TLabel('City')],  [$city_id] );
        $this->form->addFields( [new TLabel('Customer')],  [$customer_id] );
        
        $city_id->setChangeAction( new TAction( array($this, 'onCityChange' )) );
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style='width:100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Action to be executed when the user changes the city
     * @param $param Action parameters
     */
    public static function onCityChange($param)
    {
        if (!empty($param['city_id']))
        {
            TSession::setValue('city_id_filter', $param['city_id']);
        }
    }
}
