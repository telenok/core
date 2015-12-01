<?php namespace Telenok\Core\Module\Packages\ComposerManager;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller { 
    
    protected $key = 'composer-manager';
    protected $parent = 'packages';
    protected $icon = 'fa fa-file';

	protected $presentation = 'tree-tab-object';
    protected $presentationContentView = 'core::module.composer-manager.content';
    protected $presentationComposerJsonView = 'core::module.composer-manager.composer-json';

	protected $tableColumn = ['name','version', 'description', 'license', 'type'];
	protected $timeProcessLimit = 600;

    public function getContent()
    { 
        return [
            'tabKey' => "{$this->getTabKey()}-{$this->getParent()}",
            'tabLabel' => $this->LL('header.title'),
            'tabContent' => view($this->getPresentationContentView(), array(
                'controller' => $this,
				'currentDirectory' => addslashes(base_path()),
                'fields' => $this->tableColumn,
                //'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(),
                'uniqueId' => str_random(),
            ))->render()
        ];
    }

    public function getComposerJsonContent($success = false)
	{
        return [
            'tabKey' => "{$this->getTabKey()}-{$this->getParent()}-" . str_random(),
            'tabLabel' => 'Composer.json',
            'tabContent' => view($this->presentationComposerJsonView, array(
				'routerParam' => $this->getRouterParam('composer-json-update'),
                'controller' => $this,
                'success' => $success,
				'content' => file_get_contents(base_path('composer.json')),
                'gridId' => $this->getGridId(),
                'uniqueId' => str_random(),
            ))->render()
        ];
	}

	public function composerJsonUpdate()
	{
        \File::makeDirectory(storage_path('telenok/composer'), 0775, true, true);

		$lastFile = storage_path('telenok/composer/composer.last.json');
		$validateFile = storage_path('telenok/composer/composer.validate.json');

		if (file_exists($validateFile) && (time() - filemtime($validateFile) < $this->timeProcessLimit))
		{
			return \Response::json(['error' => $this->LL('error.json.locked')], 417 /* Expectation Failed */);
		}

		touch($validateFile);

		try
		{ 
			$json = $this->getRequest()->input('content');

			$content = json_decode($json);

			if ($content === null)
			{
                throw new \Exception();
			}

			file_put_contents($validateFile, $json, LOCK_EX);

			$input = new \Symfony\Component\Console\Input\ArrayInput([
					'command' => 'validate',
					'file' => storage_path('telenok/composer/composer.validate.json'),
				]);

			$out = new \Symfony\Component\Console\Output\BufferedOutput();
			$application = new \Composer\Console\Application();
			$application->setAutoExit(false);
			$application->run($input, $out);

			if (strpos($out->fetch(), 'is invalid') !== false)
			{
				throw new \Exception();
			}

			file_put_contents($lastFile, file_get_contents(base_path('composer.json')), LOCK_EX);
			file_put_contents(base_path('composer.json'), $json, LOCK_EX);
            
            if ($this->getRequest()->input('action') == "save.update")
            {
                $this->updatePackages();
            }
		} 
		catch (\Exception $e) 
		{
			\File::delete($validateFile);

			return \Response::json(['error' => $this->LL('error.json')], 417 /* Expectation Failed */);
		}
		
		\File::delete($validateFile);

		return $this->getComposerJsonContent(true);
	}
	
    public function getList()
    {
        $composer = (new \Telenok\Core\Composer\Application())->getEmbeddedComposer();
        
        $collection = collect();

        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) 
        {
            if (!$collection->has($package->getName())
                || !is_object($collection->get($package->getName()))
                || version_compare($collection->get($package->getName())->getVersion(), $package->getVersion(), '<')
            ) 
            {
                $collection->put($package->getName(), $package);
            }
        }
        
        $content = []; 

        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());

		$sEcho = $input->get('sEcho');
        $uniqueId = $input->get('uniqueId');
        $iDisplayStart = $input->get('iDisplayStart', 0);
        $iTotalDisplayRecords = $input->get('pageLength', 20);
		
        $filter = (array)$input->get('filter');
        
        if (($title = trim($input->get('search.value'))) || ($title = trim(array_get($filter, 'name'))))
        {
            $collection = $collection->filter(function($item) use ($title)
            {
                return strpos($item->getName(), $title) !== FALSE; 
            });
        }

        foreach($collection->sortBy(function($item) { return $item->getName(); }) as $item)
        {
            $put = ['tableCheckAll' => '<input type="checkbox" class="ace ace-checkbox-2" name="tableCheckAll[]" value="'.$item->getName().'"><span class="lbl"></span>'];

            $put['name'] = $item->getName();
			$put['type'] = $item->getType();
			$put['license'] = implode(', ', (array)$item->getLicense());
			$put['version'] = $item->getPrettyVersion();
			$put['description'] = $item->getDescription();

            $put['tableManageItem'] = $this->getListButton($item);

            $content[] = $put;
        }
        
        return [
            'gridId' => $this->getGridId(),
            'sEcho' => $sEcho,
            'iTotalRecords' => $collection->count(),
            'iTotalDisplayRecords' => $collection->count(),
            'aaData' => $content
        ];
    } 
    
    public function getListButton($item)
    {
        $random = str_random();
        $collection = \Illuminate\Support\Collection::make();
        
        $collection->put('open', ['order' => 0 , 'content' => 
            '<div class="dropdown">
                <a class="btn btn-white no-hover btn-transparent btn-xs dropdown-toggle" href="#" role="button" style="border:none;"
                        type="button" id="' . $random . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-menu-hamburger text-muted"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="' . $random . '">
            ']);
        
        $collection->put('close', ['order' => PHP_INT_MAX, 'content' => 
                '</ul>
            </div>']);
        
        $collection->put('edit', ['order' => 1000, 'content' => 
                '<li><a href="#" onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \'' 
                    . $this->getRouterEdit(['id' => $item->getName()]) . '\'}); return false;">' 
                    . ' <i class="fa fa-pencil"></i> ' . $this->LL('list.btn.edit') . '</a>
                </li>']);
        
        $collection->put('delete', ['order' => 2000, 'content' => 
                '<li><a href="#" onclick="if (confirm(\'' . $this->LL('notice.sure.delete') . '\')) telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').deleteByURL(this, \'' 
                        . $this->getRouterDelete(['id' => $item->getName()]) . '\'); return false;">'
                    . ' <i class="fa fa-trash-o"></i> ' . $this->LL('list.btn.delete') . '</a>
                </li>']);
            
        app('events')->fire($this->getListButtonEventKey(), $collection);

        return $this->getAdditionalListButton($item, $collection)->sort(function($a, $b)
                    {
                        return array_get($a, 'order', 0) > array_get($b, 'order', 0) ? 1 : -1;
                    })->implode('content');
    }

    public function getPackageData($id)
    {
        $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'show',
                'package' => $id,
                '--working-dir' => base_path(),
            ]);

        $out = new \Symfony\Component\Console\Output\BufferedOutput();
        $application = new \Composer\Console\Application();
        $application->setAutoExit(false);

        $application->run($input, $out);
        
        return $out->fetch();
    }
    
    public function updatePackages($id = [])
    {
        ob_start();

        $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'update',
                //'packages' => ($id ? (array)$id : []),
                '--working-dir' => base_path(),
            ]);

        $out = new \Symfony\Component\Console\Output\BufferedOutput();
        $application = new \Composer\Console\Application();
        $application->setAutoExit(false);

        $application->run($input, $out);

        ob_end_clean();
    }
    
    public function edit($id = 0)
    {
        $id = $this->getRequest()->input('id');

        return [
            'tabKey' => "{$this->getTabKey()}-" . md5($id),
            'tabLabel' => $id,
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'routerParam' => $this->getRouterParam('update'),
                'content' => $this->getPackageData($id),
                'id' => $id,
                'uniqueId' => str_random(),
                'model' => []
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function update($id = null)
	{
        $id = $this->getRequest()->input('id');

        $this->updatePackage($id);

        return [
            'tabKey' => "{$this->getTabKey()}-" . md5($id),
            'tabLabel' => $id,			
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                    'controller' => $this,
                    'success' => true,
                    'content' => $this->getPackageData($id),
                    'id' => $id,
                    'model' => [],
                    'routerParam' => $this->getRouterParam('update'),
                    'uniqueId' => str_random(),  
                ), $this->getAdditionalViewParam()))->render()
            ];
	}

    public function delete($id = null, $force = false)
    { 
        $id = $this->getRequest()->input('id');

		try
		{
            ob_start();

            $input = new \Symfony\Component\Console\Input\ArrayInput([
                    'command' => 'remove',
                    'packages' => [$id],
                    '--working-dir' => base_path(),
                ]);

            $out = new \Symfony\Component\Console\Output\BufferedOutput();
            $application = new \Composer\Console\Application();
            $application->setAutoExit(false);

            $application->run($input, $out);

            ob_end_clean();

            return ['success' => 1];
		}
		catch (\Telenok\Core\Support\Exception\Validator $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw $e;
		}
    }

    public function editList($id = null)
    { 
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

        $ids = $input->get('tableCheckAll');

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $content = [];
        $modelType = 'file';

        foreach ($ids as $id_)
        {
            try
            {
                $model = new \SplFileInfo($id_);

                if (strstr($model->getPath(), base_path()) === FALSE)
                {
                    throw new \Exception($this->LL('error.access-denied-over-base-directory'));
                }

                if ($model->isFile())
                {
                    $modelType = 'file';
                }
                else if ($model->isDir())
                {
                    $modelType = 'directory';
                }
                
                $tabKey = str_random();

                $content[] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                                'controller' => $this,
                                'currentDirectory' => addslashes($model->getPath()),
                                'modelType' => $modelType,
                                'model' => $model,
                                'tabKey' => $tabKey,
                                'modelCurrentDirectory' => new \SplFileInfo($model->getPath()),
                                'routerParam' => $this->getRouterParam('edit'),
                                'uniqueId' => str_random(),  
                            ), $this->getAdditionalViewParam()))->render();

            } 
            catch (\Exception $ex) 
            {
                return [
                    'exception' => $ex->getMessage(),
                ];
            }
        }

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . md5(implode('', $ids)),
            'tabLabel' => $this->LL('list.edit.' . $modelType),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    public function deleteList($id = null, $ids = [])
    { 
        $ids = empty($ids) ? (array)$this->getRequest()->input('tableCheckAll') : $ids;

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $error = false;

        try
        {
            foreach ($ids as $id_)
            {
                $this->delete($id_);
            }
        }
        catch (\Exception $e)
        {
           $error = true;
        }

        if ($error)
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        else
        {
            return \Response::json(['success' => 1]);
        }

    }

	public function getRouterParam($action = '', $filePath = null, $tabKey = null)
	{
		switch ($action)
		{
			case 'create':
				return [ $this->getRouterStore(['saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', false), 'tabKey' => $tabKey]) ];
				break;

			case 'edit':
				return [ $this->getRouterUpdate(['id' => $filePath, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey]) ];
				break;

			case 'store':
				return [ $this->getRouterUpdate(['saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey]) ];
				break;

			case 'update':
				return [ $this->getRouterUpdate(['id' => $filePath, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey]) ];
				break;

			case 'composer-json-update':
				return [ route("telenok.module.composer-manager.composer-json.update", ['id' => $filePath, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey]) ];
				break;

			default:
				return [];
				break;
		}
	}
    
	public function getTreeContent()
    {
        return;
    }
}