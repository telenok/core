<?php namespace Telenok\Core\Field\Text;

class Processing {

    protected $rawValue;

    public function getProcessed()
    {
        $v = $this->getRawValue();
        $doc = new \DOMDocument();
        
        @$doc->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $v);
        $widgetInline = $doc->getElementsByTagName('widget_inline');

        for ($i = 0; $i < $widgetInline->length; $i++)
        {
            $widgetInlineElement = $widgetInline->item($i);

            $wop = \App\Telenok\Core\Model\Web\WidgetOnPage::withPermission()->find((int)$widgetInlineElement->getAttribute('data-widget-id'));
            
            if ($wop)
            {
                $repositoryWidgets = app('telenok.config.repository')->getWidget(); 
                
                $node = $doc->createElement("span", $repositoryWidgets->get($wop->key)
                                                    ->setWidgetModel($wop)
                                                    ->setConfig($wop->structure)
                                                    ->setFrontendController(app('controllerRequest'))
                                                    ->getContent());
            }
            else
            {
                $node = $doc->createElement("span", "");
            }
            
            $widgetInlineElement->parentNode->replaceChild($node, $widgetInlineElement);
        }
        
        return mb_substr($doc->saveHTML($doc->getElementsByTagName('body')->item(0)), 6, -7);
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
        return (string)$this->getRawValue();
    }
}