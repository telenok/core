<?php namespace Telenok\Core\Field\Text;

class Processing {

    protected $rawValue;

    public function getProcessed()
    {
        $v = $this->getRawValue();
        
        $doc = new \DOMDocument();
        @$doc->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $v);
        $widgetInline = $doc->getElementsByTagName('widget_inline');

        for ($i = 0; $i < $widgetInline->length; $i++) {

            $label = $widgetInline->item($i);

            return $label->getAttribute('data-widget-id');
        }
        
        dd();
        
        return '';
    }

    public function setRawValue($rawValue)
    {
        $this->rawValue = $rawValue;
        
        return $this;
    }

    public function getRawValue()
    {
        return $this->rawValue;
    }
    
    public function __toString()
    {
        return $this->getRawValue();
    }
}