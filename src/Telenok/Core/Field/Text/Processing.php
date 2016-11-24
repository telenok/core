<?php namespace Telenok\Core\Field\Text;

/**
 * @class Telenok.Core.Field.Text.Processing
 * Process value of field Telenok.Core.Field.Text.Controller
 */
class Processing {

    /**
     * @protected
     * @property {String} $rawValue
     * Plain not processed text.
     * @member Telenok.Core.Field.Text.Processing
     */
    protected $rawValue;

    /**
     * @method getProcessed
     * Return processed value of field Telenok.Core.Field.Text.Controller.
     * Text can contain special tags like &lt;widget_inline/&gt;
     * 
     * @return {String}
     * @member Telenok.Core.Field.Text.Processing
     */
    public function getProcessed()
    {
        $v = $this->getRawValue();
        $toRemove = [];
        $doc = new \DOMDocument();

        @$doc->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $v);
        $widgetInline = $doc->getElementsByTagName('widget_inline');

        for ($i = 0; $i < $widgetInline->length; $i++)
        {
            $widgetInlineElement = $widgetInline->item($i);

            $wop = \App\Vendor\Telenok\Core\Model\Web\WidgetOnPage::withPermission()->find((int)$widgetInlineElement->getAttribute('data-widget-id'));

            if ($wop)
            {
                $repositoryWidgets = app('telenok.repository')->getWidget();

                $node = $doc->createElement("span", $repositoryWidgets->get($wop->key)
                                                    ->setWidgetModel($wop)
                                                    ->setConfig($wop->structure)
                                                    ->setFrontendController(app('controllerRequest'))
                                                    ->getContent());

                $widgetInlineElement->parentNode->replaceChild($node, $widgetInlineElement);
            }
            else
            {
                $toRemove[] = $widgetInlineElement;
            }
        }
        
        foreach($toRemove as $remove)
        {
            $remove->parentNode->removeChild($remove);
        }

        return mb_substr($doc->saveHTML($doc->getElementsByTagName('body')->item(0)), 6, -7);
    }

    /**
     * @method setRawValue
     * Set raw value of text field.
     * 
     * @return {Telenok.Core.Field.Text.Processing}
     * @member Telenok.Core.Field.Text.Processing
     */
    public function setRawValue($rawValue)
    {
        $this->rawValue = $rawValue;
        
        return $this;
    }

    /**
     * @method getRawValue
     * Return raw value of text field.
     * 
     * @return {String}
     * @member Telenok.Core.Field.Text.Processing
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }
    
    /**
     * @method __toString
     * Cast value to {String}.
     * 
     * @return {String}
     * @member Telenok.Core.Field.Text.Processing
     */
    public function __toString()
    {
        return (string)$this->getRawValue();
    }
}