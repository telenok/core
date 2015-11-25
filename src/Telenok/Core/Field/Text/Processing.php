<?php namespace Telenok\Core\Field\Text;

class Processing {

    protected $rawValue;

    public function getProcessed()
    {
        $content = '';
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
                
                $node = $dom->createElement("span", $repositoryWidgets->get($wop->key)
                                                    ->setWidgetModel($wop)
                                                    ->setConfig($wop->structure)
                                                    ->setFrontendController(app('controllerRequest'))
                                                    ->getContent());
            }
            else
            {
                $node = $dom->createElement("span", "");
            }
            
            $widgetInlineElement->parentNode->replaceChild($node, $widgetInlineElement);
        }
        
        return $content;
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