<?php
/**
 * GanttGroup Active Record
 * @author  <your-name-here>
 */
class GanttGroup extends TRecord
{
    const TABLENAME = 'gantt_group';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
    }
}
