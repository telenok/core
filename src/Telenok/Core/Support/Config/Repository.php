<?php

namespace Telenok\Core\Support\Config;

use App\Events\Event;
use Telenok\Core\Event\AclFilterResource;
use Telenok\Core\Event\RepositoryObjectField;
use Telenok\Core\Event\RepositoryObjectFieldViewModel;
use Telenok\Core\Event\RepositoryPackage;
use Telenok\Core\Event\RepositorySetting;

/**
 * @class Telenok.Core.Support.Config.Repository
 * Repository stored configuration data for widgets, eloquent fields, modules, packages, etc.
 */
class Repository {

    public function getValue(Event $event, $key = '')
    {
        try
        {
            app('events')->fire($event);

            $collection = $event->getList();

            $list = collect();

            foreach ($collection->all() as $class)
            {
                $object = app($class);

                $list->put($object->getKey(), $object);
            }
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException('Failed to fire event "' . get_class($event) . '". Error: ' . $e->getMessage());
        }

        if ($key)
        {
            $el = $list->get($key);

            if ($el)
            {
                $class = get_class($el);

                return app($class);
            }
            else
            {
                throw new \RuntimeException('Failed to fire event "' . get_class($event) . '" with key "' . $key . '". Error: Class "' . e($el) . '" not exists.');
            }
        }
        else
        {
            return $list;
        }
    }

    public function getAclResourceFilter($key = '')
    {
        return $this->getValue(new AclFilterResource(), $key);
    }

    public function getPackage($key = '')
    {
        return $this->getValue(new RepositoryPackage(), $key);
    }

    public function getSetting($key = '')
    {
        return $this->getValue(new RepositorySetting(), $key);
    }

    public function getViewTheme()
    {
        $list = collect();

        $directory = base_path(config('app.path_theme'));

        foreach(app('files')->directories($directory) as $dir)
        {
            $list->push(pathinfo($dir, PATHINFO_BASENAME));
        }
        
        return $list;
    }

    public function getObjectFieldController($key = '')
    {
        return $this->getValue(new RepositoryObjectField(), $key);
    }

    public function getObjectFieldViewModel($flush = false)
    {
        static $list = null;

        if ($list === null || $flush)
        {
            try
            {
                app('events')->fire($objectRepositoryObjectFieldViewModel = new RepositoryObjectFieldViewModel());

                $l = [];
                $collection = $objectRepositoryObjectFieldViewModel->getList();

                foreach ($collection as $view)
                {
                    list($fieldKey, $viewModel) = explode('#', $view, 2);

                    $l[$fieldKey][] = $viewModel;
                }

                $list = collect($l);
            }
            catch (\Exception $e)
            {
                throw new \RuntimeException('Failed to get view model of field. Error: ' . $e->getMessage());
            }
        }

        return $list;
    }

    public function getModuleGroup()
    {
        try
        {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\ModuleGroup::active()->get()->each(function($item) use (&$list)
            {
                $object = app($item->controller_class);
                $object->setModelModuleGroup($item);
                $list->put($object->getKey(), $object);
            });
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException('Failed to get module-group. Error: ' . $e->getMessage());
        }

        return $list;
    }

    public function getModule()
    {
        try
        {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\Module::active()->get()->each(function($item) use (&$list)
            {
                $object = app($item->controller_class);
                $object->setModelModule($item);
                $list->put($object->getKey(), $object);
            });
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException('Failed to get module. Error: ' . $e->getMessage());
        }

        return $list;
    }

    public function getWidgetGroup()
    {
        try
        {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\WidgetGroup::active()->get()->each(function($item) use (&$list)
            {
                $object = app($item->controller_class);
                $object->setWidgetGroupModel($item);
                $list->put($object->getKey(), $object);
            });
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException('Failed to get widget group. Error: ' . $e->getMessage());
        }

        return $list;
    }

    public function getWidget()
    {
        try
        {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\Widget::active()->get()->each(function($item) use (&$list)
            {
                $object = app($item->controller_class);
                $list->put($object->getKey(), $object);
            });
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException('Failed to get widget. Error: ' . $e->getMessage());
        }

        return $list;
    }

    public function compileRoute()
    {
        $path = base_path('routes');

        $file = 'telenok.php';

        if (!\File::exists($path))
        {
            \File::makeDirectory($path, 0777, true);
        }

        $content = [];
        $routeCommon = [];
        $routeDomain = [];

        $domains = \App\Vendor\Telenok\Core\Model\Web\Domain::active()->get();

        $pages = \App\Vendor\Telenok\Core\Model\Web\Page::whereHas('pageDomain', function($query) use ($domains)
                {
                    $query->whereIn('page_domain', $domains->modelKeys() ? : [0]);
                }, '>=', 0)
                ->active()
                ->get();

        $chooseHttpMethod = function($page)
        {
            switch (strtoupper($page->http_method))
            {
                case 'GET':
                    return 'get';
                case 'POST':
                    return 'post';
                case 'PUT':
                    return 'put';
                case 'PATCH':
                    return 'patch';
                case 'DELETE':
                    return 'delete';
                case 'OPTIONS':
                    return 'options';
            }

            return 'get';
        };


        foreach ($domains->all() as $domain)
        {
            foreach ($pages->all() as $page)
            {
                if (!method_exists($page->controller_class, $page->controller_method))
                {
                    throw new \Exception('Method "' . $page->controller_method . '" not exists in class "' . $page->controller_class . '"');
                }

                if ($page->page_domain && $domain->getKey() == $page->page_domain)
                {
                    $routeDomain[$page->page_domain][] = 'app("router")->' . $chooseHttpMethod($page) . '("' . $page->getAttribute('url_pattern') . '", array("as" => "'
                        . ($page->router_name ? : 'page_' . $page->getKey())
                        . '", "uses" => "' . addcslashes($page->controller_class, '\\"') . '@' . $page->controller_method . '"));'
                    ;
                }
                else if (!$page->page_domain)
                {
                    $routeCommon[$page->getKey()] = 'app("router")->' . $chooseHttpMethod($page) . '("' . $page->getAttribute('url_pattern') . '", array("as" => "'
                        . ($page->router_name ? : 'page_' . $page->getKey())
                        . '", "uses" => "' . addcslashes($page->controller_class, '\\"') . '@' . $page->controller_method . '"));'
                    ;
                }
            }
        }

        foreach ($domains->all() as $domain)
        {
            if (!empty($routeDomain[$domain->getKey()]) && !empty($routeDomain[$domain->getKey()]))
            {
                $content[] = 'app("router")->group(array("domain" => "' . $domain->domain . '"), function() {';

                foreach ($routeDomain[$domain->getKey()] as $dC)
                {
                    $content[] = $dC;
                }

                $content[] = '});';
            }
        }

        file_put_contents($path . '/' . $file, '<?php ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $content)
            . PHP_EOL . implode(PHP_EOL, $routeCommon) . PHP_EOL . PHP_EOL . '?>', LOCK_EX
        );
    }

    public function compileSetting()
    {
        if (app('db')->table('setting')->where('active', 1)->whereNull('deleted_at')->count())
        {
            foreach (\App\Vendor\Telenok\Core\Model\System\Setting::all() as $setting)
            {
                try
                {
                    $w = app('telenok.config.repository')->getSetting(strtolower($setting->code));

                    $w->fillSettingValue($setting, $setting->value);
                }
                catch (\Exception $e)
                {
                    app('config')->set($setting->code, $setting->value);
                }
            }
        }
    }

}
