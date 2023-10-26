<?php
/**
 * GanttDatabaseView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class GanttDatabaseView extends TPage
{
    private $gantt;
    private $loaded;
    
    /**
     * Constructor method
     */
    public function __construct($param)
    {
        parent::__construct();
        
        $this->gantt = new TGantt(TGantt::MODE_MONTHS_WITH_DAY, 'sm');
        $this->gantt->enableStripedMonths();
        $this->gantt->enableStripedRows();
        $this->gantt->setReloadAction(new TAction([$this, 'onReload']));
        $this->gantt->setStartDate( $param['start_time'] ?? date('Y-01-01') );
        $this->gantt->setEventClickAction(new TAction(['GanttEventPanelForm', 'onEdit']));
        $this->gantt->setDayClickAction(new TAction(['GanttEventPanelForm', 'onStartEdit']));
        
        if (!empty(TSession::getValue('gantt_view_mode')))
        {
            $this->gantt->setViewMode(TSession::getValue('gantt_view_mode'));
        }
        
        if (!empty(TSession::getValue('gantt_size_mode')))
        {
            $this->gantt->setSizeMode(TSession::getValue('gantt_size_mode'));
        }
        
        $this->gantt->setTitle('Eventos');
        $this->gantt->enableFullHours();

        $button = $this->gantt->addHeaderAction(new TAction([$this, 'onAction']), 'Ação', new TImage('fa:rocket'));
        $button->{'class'} .= 'btn-success';

        $button = $this->gantt->addHeaderAction(new TAction([$this, 'onAction']), 'Ação 2', new TImage('fa:rocket'));
        $button->{'class'} .= 'btn-danger';

        $this->gantt->enableDragEvent( new TAction(['GanttEventPanelForm', 'onUpdateEvent']), 60  );
        
        $this->gantt->enableViewModeButton(true, true);
        $this->gantt->enableSizeModeButton(true, true);
        
        parent::add($this->gantt);
    }
    
    /**
     * Generic action to show parameters
     */
    public static function onAction($param)
    {
        new TMessage('info', str_replace(',', ',<br> ', json_encode($param)));
    }
    
    /**
     * Load/Reload Gantt Events
     */
    public function onReload($param=NULL)
    {
        if (! empty($param['start_time']))
        {
            $this->gantt->setStartDate($param['start_time']);
        }

        if (!empty($param['view_mode']))
        {
            TSession::setValue('gantt_view_mode', $param['view_mode']);
            $this->gantt->setViewMode($param['view_mode']);
        }
        
        if (!empty($param['size_mode']))
        {
            TSession::setValue('gantt_size_mode', $param['size_mode']);
            $this->gantt->setSizeMode($param['size_mode']);
        }
        
        try
        {
            $this->gantt->clearEvents();
            TTransaction::open('samples');
            
            $groups = GanttGroup::getIndexedArray('id', 'name');
            
            foreach ($groups as $id => $name)
            {
                $this->gantt->addRow($id, $name);
            }
            
            $events = GanttEvent::where('start_time', '<=', $this->gantt->getEndDate())
                                ->where('end_time',   '>=', $this->gantt->getStartDate())->load();
            
            if ($events)
            {
                foreach ($events as $event)
                {
                    $popover_content = $event->render("<b>Title</b>: {title} <br> <b>Color</b>: {color} <br> <b>Percent</b>: {percent}%");
                    $event->title = TGantt::renderPopover($event->title, 'Popover title', $popover_content);
                    
                    // trocar para parametros obrigatorios e obj por ultimo
                    $this->gantt->addEvent($event->id, $event->group_id, $event->title, $event->start_time, $event->end_time, $event->color, $event->percent);
                }
            }
            
            TTransaction::close();
            
            $this->loaded = TRUE;
        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Show Gantt
     */
    public function show()
    {
        // check if the gantt is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) || $_GET['method'] !== 'onReload'))
        {
            $this->onReload( func_get_arg(0) );
        }

        parent::show();
    }
}
