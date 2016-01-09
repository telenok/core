<?php namespace Telenok\Core\Controller\Backend;

class Controller extends \Telenok\Core\Interfaces\Controller\Backend\Controller {

    protected $key = 'backend';
    protected $languageDirectory = 'controller';
            
    public function __construct()
    {
        $this->middleware('auth.backend', ['except' => ['errorAccessDenied']]);
    }

    public function updateBackendUISetting($key = null, $value = null)
    {
        $input = $this->getRequest();

        $key = $key ? : $input->input('key');

        if ($key)
        {
            $user = app('auth')->user();

            $userConfig = $user->configuration;

            $userConfig->put($key, ($value ? : $input->input('value')));

            $user->configuration = $userConfig;

            $user->update();
        }
    }

    public function errorAccessDenied()
    {
        return view('core::controller.backend-denied', ['controller' => $this])->render();
    }

    public function validateSession()
    {
        return ['logined' => (int)app('auth')->check(), 'csrf_token' => csrf_token()];
    }

    public function getContent()
    {
        $listModuleMenuLeft = collect();
        \Event::fire('telenok.module.menu.left', [$listModuleMenuLeft]);

        $config = app('telenok.config.repository');

        $setArray = [];
        
        $listModule = $config->getModule()
                ->filter(function($item) use ($listModuleMenuLeft)
                {
                    if (!$item->getParent() && $listModuleMenuLeft->has($item->getKey()))
                    {
                        return true;
                    }

                    if ($item->getParent() && app('auth')->can('read', 'module.' . $item->getKey()) && $listModuleMenuLeft->has($item->getKey()))
                    {
                        return true;
                    }
                })
                ->sortBy(function($item) use ($listModuleMenuLeft)
        {
            return $item->getModelModule()->module_order;
        });


        $listModule = $listModule->filter(function($item) use ($listModule)
        {
            foreach ($listModule as $module)
            {
                if ($item->getParent() || (!$item->getParent() && $module->getParent() == $item->getKey()))
                {
                    return true;
                }
            }

            return false;
        });

        $listModuleGroup = $config->getModuleGroup()->filter(function($item) use ($listModule)
        {
            foreach ($listModule as $module)
            {
                if ($module->getGroup() && $module->getGroup() == $item->getKey())
                {
                    return true;
                }
            }

            return false;
        });

        $setArray['listModule'] = $listModule;
        $setArray['listModuleGroup'] = $listModuleGroup;


        $listModuleMenuTop = collect();

        $listModuleMenuTopCollection = collect();

        \Event::fire('telenok.module.menu.top', [$listModuleMenuTopCollection]);

        $listModuleMenuTopCollection->each(function($item) use ($listModuleMenuTop, $config)
        {
            list($code, $method) = explode('@', $item, 2);

            $listModuleMenuTop->push($config->getModule()->get($code)->{$method}());
        });

        $listModuleMenuTop->sortBy(function($item)
        {
            return $item->get('order');
        });

        $setArray['listModuleMenuTop'] = $listModuleMenuTop;
        $setArray['controller'] = $this;

        if ($this->getRequest()->has('backend_external_event'))
        {
            \Event::fire('telenok.backend.external', [$this]);
        }

        \Event::fire('telenok.backend.controller.content', [$setArray]);

        $this->addJsCode(view('core::special.telenok.table', $setArray)->render());
        
        return view('core::controller.backend', $setArray)->render();
    }
}