<?php namespace Telenok\Core\Controller\Backend;

/**
 * Class to process initial backend http request
 * 
 * @class Telenok.Core.Controller.Backend.Controller
 * @extends Telenok.Core.Abstraction.Controller.Backend.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Controller\Backend\Controller {

    /**
     * @protected
     * @property {String} $key
     * Controller string key.
     * @member Telenok.Core.Controller.Backend.Controller
     */
    protected $key = 'backend';

    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for {@link Telenok.Core.Support.Traits.Language#LL Telenok.Core.Support.Traits.Language->LL()} method.
     * @member Telenok.Core.Controller.Backend.Controller
     */
    protected $languageDirectory = 'controller';
            
    /**
     * @constructor
     * Use middleware 'auth.backend' to limit access to Control Panel
     * @member Telenok.Core.Controller.Backend.Controller
     */
    public function __construct()
    {
        $this->middleware('auth.backend', ['except' => ['errorAccessDenied']]);
    }

    /**
     * @method updateBackendUISetting
     * Use middleware 'auth.backend' to limit access to Control Panel
     * @param {String} $key
     * Key of UI setting
     * @param {String/Number/Boolean}$ value
     * Mixed data linked to key
     * @return {void}
     * @member Telenok.Core.Controller.Backend.Controller
     */
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

    /**
     * @method errorAccessDenied
     * Show page with "Access denied" message
     * @return {string}
     * @member Telenok.Core.Controller.Backend.Controller
     */
    public function errorAccessDenied()
    {
        return view('core::controller.backend-denied', ['controller' => $this])->render();
    }

    /**
     * @method validateSession
     * Validate user logined and CSRF token
     * @return {Array}
     * @member Telenok.Core.Controller.Backend.Controller
     */
    public function validateSession()
    {
        return ['logined' => (int)app('auth')->check(), 'csrf_token' => csrf_token()];
    }

    /**
     * @method getContent
     * Process initial request and return HTML of Control Panel
     * @return {String}
     * @member Telenok.Core.Controller.Backend.Controller
     */
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