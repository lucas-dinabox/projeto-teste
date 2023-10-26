<?php
/**
 * GanttEvent Active Record
 * @author  <your-name-here>
 */
class GanttEvent extends TRecord
{
    const TABLENAME = 'gantt_event';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('group_id');
        parent::addAttribute('start_time');
        parent::addAttribute('end_time');
        parent::addAttribute('title');
        parent::addAttribute('description');
        parent::addAttribute('color');
        parent::addAttribute('percent');
    }
}
