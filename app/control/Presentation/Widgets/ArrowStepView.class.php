<?php
/**
 * StepView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ArrowStepView extends TPage
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
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle(_t('Arrow steps'));
        $this->form->generateAria(); // automatic aria-label
        
        $step = new TArrowStep('step');
        $step->addItem('Step 1', 1, '#fa7d00');
        $step->addItem('Step 2', 2, '#0d9ddb');
        $step->addItem('Step 3', 3, '#0fd927');
        //$step->setCurrent('Step 1');
        $step->setCurrentKey(1);
        $step->setHeight(40);
        
        $this->form->addFields( [new TLabel('Steps')]);
        $this->form->addFields( [$step]);
        
        $this->form->addAction('Send', new TAction(array($this, 'onSend')), 'far:check-circle green');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        parent::add($vbox);
    }
    
    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSend($param)
    {
        $data = $this->form->getData();
        
        // put the data back to the form
        $this->form->setData($data);
        
        // show the message
        new TMessage('info', 'Step: ' . $data->step);
    }
}
