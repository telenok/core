<?php

namespace Telenok\Core\Support;

use App\Events\Event;
use Telenok\Core\Event\AclFilterResource;
use Telenok\Core\Event\RepositoryConfig;
use Telenok\Core\Event\RepositoryObjectField;
use Telenok\Core\Event\RepositoryObjectFieldViewModel;
use Telenok\Core\Event\RepositoryPackage;

/**
 * @class Telenok.Core.Support.Config.Repository
 * Repository stored configuration data for widgets, eloquent fields, modules, packages, etc.
 */
class Repository
{
    protected $routeFile = 'telenok.php';

    public function getValue(Event $event, $key = '')
    {
        try {
            app('events')->fire($event);

            $collection = $event->getList();

            $list = collect();

            foreach ($collection->all() as $class) {
                $object = app($class);

                $list->put($object->getKey(), $object);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fire event "'.get_class($event).'". Error: '.$e->getMessage());
        }

        if ($key) {
            $el = $list->get($key);

            if ($el) {
                $class = get_class($el);

                return app($class);
            } else {
                throw new \RuntimeException('Failed to fire event "'.get_class($event).'" with key "'.$key.'". Error: Class "'.e($el).'" not exists.');
            }
        } else {
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

    public function getConfigGroup($key = '')
    {
        return $this->getValue(new RepositoryConfig(), $key);
    }

    public function getViewTheme()
    {
        $list = collect();

        $directory = base_path(config('app.path_theme'));

        foreach (app('files')->directories($directory) as $dir) {
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

        if ($list === null || $flush) {
            try {
                app('events')->fire($objectRepositoryObjectFieldViewModel = new RepositoryObjectFieldViewModel());

                $l = [];
                $collection = $objectRepositoryObjectFieldViewModel->getList();

                foreach ($collection as $view) {
                    list($fieldKey, $viewModel) = explode('#', $view, 2);

                    $l[$fieldKey][] = $viewModel;
                }

                $list = collect($l);
            } catch (\Exception $e) {
                throw new \RuntimeException('Failed to get view model of field. Error: '.$e->getMessage());
            }
        }

        return $list;
    }

    public function getModuleGroup()
    {
        try {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\ModuleGroup::active()->get()->each(function ($item) use (&$list) {
                $object = app($item->controller_class);
                $object->setModelModuleGroup($item);
                $list->put($object->getKey(), $object);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to get module-group. Error: '.$e->getMessage());
        }

        return $list;
    }

    public function getModule()
    {
        try {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\Module::active()->get()->each(function ($item) use (&$list) {
                $object = app($item->controller_class);
                $object->setModelModule($item);
                $list->put($object->getKey(), $object);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to get module. Error: '.$e->getMessage());
        }

        return $list;
    }

    public function getWidgetGroup()
    {
        try {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\WidgetGroup::active()->get()->each(function ($item) use (&$list) {
                $object = app($item->controller_class);
                $object->setWidgetGroupModel($item);
                $list->put($object->getKey(), $object);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to get widget group. Error: '.$e->getMessage());
        }

        return $list;
    }

    public function getWidget()
    {
        try {
            $list = collect();

            \App\Vendor\Telenok\Core\Model\Web\Widget::active()->get()->each(function ($item) use (&$list) {
                $object = app($item->controller_class);
                $list->put($object->getKey(), $object);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to get widget. Error: '.$e->getMessage());
        }

        return $list;
    }

    public function compileRoute()
    {
        $path = base_path('routes');

        $file = $this->routeFile;

        if (!\File::exists($path)) {
            \File::makeDirectory($path, 0777, true);
        }

        $content = [];
        $routeCommon = [];
        $routeDomain = [];

        $domains = \App\Vendor\Telenok\Core\Model\Web\Domain::active()->get();

        $pages = \App\Vendor\Telenok\Core\Model\Web\Page::whereHas('pageDomain', function ($query) use ($domains) {
            $query->whereIn('page_domain', $domains->modelKeys() ?: [0]);
        }, '>=', 0)
            ->active()
            ->get();

        $chooseHttpMethod = function (\Telenok\Core\Model\Web\Page $page) {
            switch (strtoupper($page->http_method)) {
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

        foreach ($domains->all() as $domain) {
            foreach ($pages->all() as $page) {
                if (!method_exists($page->controller_class, $page->controller_method)) {
                    throw new \Exception('Method "'.$page->controller_method.'" not exists in class "'.$page->controller_class.'"');
                }

                if ($page->page_domain && $domain->getKey() == $page->page_domain) {
                    $routeDomain[$page->page_domain][] = 'app("router")->'.$chooseHttpMethod($page).'("'.$page->getAttribute('url_pattern').'", array("as" => "'
                        .($page->router_name ?: 'page_'.$page->getKey())
                        .'", "uses" => "'.addcslashes($page->controller_class, '\\"').'@'.$page->controller_method.'"));';
                } elseif (!$page->page_domain) {
                    $routeCommon[$page->getKey()] = 'app("router")->'.$chooseHttpMethod($page).'("'.$page->getAttribute('url_pattern').'", array("as" => "'
                        .($page->router_name ?: 'page_'.$page->getKey())
                        .'", "uses" => "'.addcslashes($page->controller_class, '\\"').'@'.$page->controller_method.'"));';
                }
            }
        }

        foreach ($domains->all() as $domain) {
            if (!empty($routeDomain[$domain->getKey()]) && !empty($routeDomain[$domain->getKey()])) {
                $content[] = 'app("router")->group(array("domain" => "'.$domain->domain.'"), function() {';

                foreach ($routeDomain[$domain->getKey()] as $dC) {
                    $content[] = $dC;
                }

                $content[] = '});';
            }
        }

        file_put_contents($path.'/'.$file, '<?php '.PHP_EOL.PHP_EOL.implode(PHP_EOL, $content)
            .PHP_EOL.implode(PHP_EOL, $routeCommon).PHP_EOL.PHP_EOL.'?>', LOCK_EX
        );
    }

    public function compileConfig()
    {
        app('db')->table('config')->where(function ($query) {
            $query->where('active', 1);
            $query->where('active_at_start', '<=', \Carbon\Carbon::now()->toDateTimeString());
            $query->where('active_at_end', '>=', \Carbon\Carbon::now()->toDateTimeString());
            $query->whereNull('deleted_at');
        })
        ->get()
        ->each(function ($config) {
            app('config')->set($config->code, unserialize($config->value));
        });
    }
}
