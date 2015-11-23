<?php namespace Telenok\Core\Widget\Rte;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'rte';
    protected $parent = 'standart';

	public function getNotCachedContent()
	{
        if ($t = $this->getFileTemplatePath())
        {
            $v = file_get_contents($t);

            $doc = new \DOMDocument();
            @$doc->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $v);
            $widgetInline = $doc->getElementsByTagName('widget_inline');

            for ($i = 0; $i < $widgetInline->length; $i++)
            {
                $widgetTag = $widgetInline->item($i);

                $wop = \App\Telenok\Core\Model\Web\WidgetOnPage::withPermission()->find((int)$widgetTag->getAttribute('data-widget-id'));

                if ($wop)
                {
                    $attributes = $widgetTag->attributes;
                    
                    $a = [];
                    
                    foreach($attributes as $attr)
                    {
                        if (strpos($attr->localName, 'data-widget-config-') !== FALSE)
                        {
                            $a_ = str_replace(['data-widget-config-', '-'], ['', '_'], $attr->localName);

                            $a[$a_] = $attr->nodeValue;
                        }
                    }

                    $widgetRepository = app('telenok.config.repository')->getWidget();

                    $content = $widgetRepository->get($wop->key)
                        ->setWidgetModel($wop)
                        ->setConfig($wop->structure->merge($a))
                        ->setFrontendController($this->getFrontendController())
                        ->getContent();

                    $nodeSpan = $doc->createElement("span", $content);
                }
                else
                {
                    $nodeSpan = $doc->createElement("widget_inline_not_found", "");
                }
                
                $widgetTag->parentNode->replaceChild($nodeSpan, $widgetTag);
            }
            
            return preg_replace("/^<body[^>]*>|<\/body>$/si", "", $doc->saveHTML($doc->getElementsByTagName('body')->item(0)));
        }
	}
}