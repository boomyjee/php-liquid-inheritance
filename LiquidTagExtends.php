<?php

class LiquidTagExtends extends LiquidBlock
{
    public $template_name;
    public $blocks;
    
    public function __construct($markup, &$tokens, &$file_system) {
        
        $syntax_regexp = new LiquidRegexp('/(' . LIQUID_QUOTED_FRAGMENT . ')/');
        
        if ($syntax_regexp->match($markup)) {
            $this->template_name = $syntax_regexp->matches[1];
        }
        else {
            throw new LiquidException("Syntax Error in 'extends' - Valid syntax: extends [template]");
        }        
        
        parent::__construct($markup,$tokens,$file_system);
    
        $this->blocks = array();
        foreach ($this->_nodelist as $node) {
            if ($node instanceof LiquidTagBlock) $this->blocks[$node->name] = $node;
        }
    }
    
    public function render(&$context) {
        $template = $this->load_template($context);
        $parent_blocks = array();
        $this->find_blocks($template->getRoot(),$parent_blocks);
            
        foreach ($this->blocks as $name=>$block) {
            if ($pb = $parent_blocks[$name]) {
                $pb->parent = $block->parent;
                $pb->add_parent($pb->_nodelist);
                $pb->_nodelist = $block->_nodelist;
            } else {
                if ($this->is_extending($template))
                    $template->getRoot()->_nodelist[] = $block;
            }
        }
        return $template->getRoot()->render($context);
    }
    
    function assert_missing_delimitation() {
        return;
    }
    
    function load_template(&$context) {
        if (!isset($this->file_system)) throw new LiquidException("No file system");
        $source = $this->file_system->read_template_file($this->template_name);
        $template = new \LiquidTemplate();
        $template->setFileSystem($this->file_system);
        $template = $template->parse($source);
        return $template;
    }
    
    function find_blocks($node,&$blocks) {
        if ($node->_nodelist && is_array($node->_nodelist)) {
            foreach ($node->_nodelist as $sub) {
                if ($sub instanceof LiquidTagBlock)
                    $blocks[$sub->name] = $sub;
                else
                    $this->find_blocks($sub,$blocks);
            }
        }
    }
    
    function is_extending($template) {
        foreach ($template->getRoot()->_nodelist as $node) 
            if ($node instanceof LiquidTagExtends) return true;
        return false;
    }
}