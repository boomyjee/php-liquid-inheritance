<?php

class LiquidBlockDrop extends LiquidDrop {
    
    function __construct($block) {
        $this->block = $block;
    }
    
    function _beforeMethod($method) {
        return $this->block->$method($this->context);
    }
}

class LiquidTagBlock extends LiquidBlock
{
    public function __construct($markup, &$tokens, &$file_system) {
        
        $syntax_regexp = new LiquidRegexp('/(\w)+/');
        
        if ($syntax_regexp->match($markup)) {
            $this->name = $syntax_regexp->matches[1];
        }
        else {
            throw new LiquidException("Syntax Error in 'block' - Valid syntax: block [name]");
        }        
        if ($tokens)
            parent::__construct($markup,$tokens,$file_system);
    }    
    
    public function render(&$context)
    {
        $context->push();
        $context->set('block',new LiquidBlockDrop($this));
        $res = $this->render_all($this->_nodelist,$context);
        $context->pop();
        return $res;
    }
    
    public function add_parent(&$nodelist) {
        if ($this->parent) {
            $this->parent->add_parent($nodelist);
        } else {
            $tokens = false;
            $this->parent = new LiquidTagBlock($this->name,$tokens,$this->file_system);
        }
    }
    
    function call_super(&$context) {
        if ($this->parent)
            return $this->parent->render($context);
        else
            return '';
    }
}