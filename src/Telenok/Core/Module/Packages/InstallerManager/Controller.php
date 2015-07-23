<?php namespace Telenok\Core\Module\Packages\InstallerManager;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller { 
    
    protected $key = 'installer-manager';
    protected $parent = 'packages';
    protected $icon = 'fa fa-file';

	protected $presentation = 'tree-tab-object';
    protected $presentationContentView = 'core::module.installer-manager.content';

	protected $tableColumn = ['name', 'key', 'description', 'image'];

	public function getTreeContent()
    {
        return;
    }

    public function getContent()
    { 
        return [
            'tabKey' => "{$this->getTabKey()}-{$this->getParent()}",
            'tabLabel' => $this->LL('header.title'),
            'tabContent' => view($this->getPresentationContentView(), array(
                'controller' => $this,
				'currentDirectory' => addslashes(base_path()),
                'fields' => $this->tableColumn,
                'gridId' => $this->getGridId(),
                'uniqueId' => str_random(),
            ))->render()
        ];
    }

    public function getList()
    {
        $content = []; 

        $request = $this->getRequest(); 
		$list = (array)json_decode(file_get_contents('http://telenok.com/package/lists/json'), true);

		$sEcho = $request->input('sEcho');
        $uniqueId = $request->input('uniqueId');
        $iDisplayStart = $request->input('iDisplayStart', 0);
        $iTotalDisplayRecords = $request->input('iDisplayLength', 20);

        foreach($list as $item)
        {
            $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="' . $item['key'] . '" /><span class="lbl"></span></label>'];

			$put['name'] = '<i class="fa fa-folder"></i> ' . array_get($item, 'title.en');
			$put['key'] = $item['key'];
			$put['description'] = array_get($item, 'description.en');
			$put['image'] = '<img src="http://www.pxleyes.com/images/tutorials/ext/Logo-Design-Process-and-Walkthrough-for-Vivid-Ways.jpg" class="img-thumbnail" style="height:100px;" />';

            $put['tableManageItem'] = $this->getListButton($item);

            $content[] = $put;
        }

        return [
            'gridId' => $this->getGridId(),
            'sEcho' => $sEcho,
            'iTotalRecords' => count($list),
            'iTotalDisplayRecords' => count($list),
            'aaData' => $content
        ];
    } 

    public function getListButton($item)
    {
        return '
                <div class="hidden-phone visible-lg btn-group">
				
                    <button class="btn btn-xs btn-info" title="'.$this->LL('list.btn.view').'" 
                        onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \'' 
                        . $this->getRouterUpdate(['id' => $item['key']]) . '\'});">
                        <i class="ace-icon glyphicon glyphicon-eye-open bigger-110"></i>
						View
						<i class="ace-icon fa fa-arrow-circle-o-right icon-on-right"></i>
                    </button>

					<button class="btn btn-xs btn-success"
						onclick="if (confirm(\'' . $this->LL('notice.sure') . '\'))
							telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').installByURL({url: \''. $this->getRouterInstall(['key' => $item['key']]) . '\'});">
						<i class="ace-icon fa fa-gavel bigger-110"></i>
						Install
						<i class="ace-icon fa fa-cloud-download icon-on-right"></i>
					</button>				
					<button class="btn btn-xs btn-warning">
						<i class="ace-icon fa fa-arrow-circle-o-down bigger-110"></i>
						Update
						<i class="ace-icon fa fa-cloud-download icon-on-right"></i>
					</button>				
					<button class="btn btn-xs btn-danger">
						<i class="ace-icon fa fa-circle-o bigger-110"></i>
						Uninstall
						<i class="ace-icon fa fa-exclamation-circle icon-on-right"></i>
					</button>				
                </div>';
    }

    public function getRouterInstall($param = [])
    {
        return route("cmf.module.{$this->getKey()}.install", $param);
    }

    public function install()
	{
		try
		{
			\File::makeDirectory(storage_path('telenok/composer'), 0775, true, true);

			$jsonArray = json_decode(file_get_contents(base_path('composer.json')), true);
			$tmpComposerJsonFile = base_path('composer.json');

			$fileName = str_random();

			if (is_array(array_get($jsonArray, 'repositories')))
			{
				foreach ($jsonArray['repositories'] as &$v)
				{
					if ($v['package']['name'] == 'fzaninotto/faker')
					{
						$v['package']['version'] = '1.5.0';
						$v['package']['dist']['url'] = storage_path('telenok/composer/' . $fileName . '.zip');
					}
				}
			}
			else
			{
				$jsonArray['repositories'][] = [
					'type' => 'package',
					'package' => [
						'name' => 'fzaninotto/faker',
						'version' => '1.5.0',
						'dist' => [
							'url' => storage_path('telenok/composer/' . $fileName . '.zip'),
							'type' => 'zip',
						]
					]
				];
			}

			file_put_contents($tmpComposerJsonFile, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

			$input = new \Symfony\Component\Console\Input\ArrayInput([
					'command' => 'require',
					'--working-dir' => base_path(),
					'packages' => ['fzaninotto/faker'],
				]);

			$out = new \Symfony\Component\Console\Output\BufferedOutput();
			$application = new \Composer\Console\Application();
			$application->setAutoExit(false);
			$application->run($input, $out);

			if (strpos($out->fetch(), 'is invalid') !== false)
			{
				throw new \Exception();
			}
			
			var_dump($out->fetch());
			
			dd('ssssssssssssss');

			$request = $this->getRequest(); 

			//file_get_contents('http://telenok.com/package/get/process?' . http_build_query(['key' => $request->input('key')]));
			//file_put_contents(base_path('aa.zip'), file_get_contents("https://api.github.com/repos/laravel/framework/zipball/master"));

			return [
				'tabKey' => $this->getTabKey() . '-new-' . $tabKey,
				'tabLabel' => $this->LL('list.create.' . (in_array($modelType, ['file', 'directory'], true) ? $modelType : "")),
				'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
					'controller' => $this,
					'currentDirectory' => addslashes($currentDirectory),
					'modelType' => $modelType,
					'model' => null,
					'tabKey' => $tabKey,
					'modelCurrentDirectory' => new \SplFileInfo($currentDirectory),
					'routerParam' => $this->getRouterParam('create'),
					'uniqueId' => str_random(),  
				), $this->getAdditionalViewParam()))->render()
			];
		}
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
	}
	
	
    public function create()
	{
		try
		{ 
			$modelType = $this->getRequest()->input('modelType');
			$currentDirectory = realpath($this->getRequest()->input('currentDirectory'));

			if (strstr($currentDirectory, base_path()) === FALSE)
			{
				throw new \Exception($this->LL('error.access-denied-over-base-directory'));
			}

			$tabKey = str_random();
			
			return [
				'tabKey' => $this->getTabKey() . '-new-' . $tabKey,
				'tabLabel' => $this->LL('list.create.' . (in_array($modelType, ['file', 'directory'], true) ? $modelType : "")),
				'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
					'controller' => $this,
					'currentDirectory' => addslashes($currentDirectory),
					'modelType' => $modelType,
					'model' => null,
					'tabKey' => $tabKey,
					'modelCurrentDirectory' => new \SplFileInfo($currentDirectory),
					'routerParam' => $this->getRouterParam('create'),
					'uniqueId' => str_random(),  
				), $this->getAdditionalViewParam()))->render()
			];
		}
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
	}

    public function edit($id = 0)
	{
		$id = $id ?: $this->getRequest()->input('id');

		try
		{
			$model = new \SplFileInfo($id);

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

			return [
				'tabKey' => $this->getTabKey() . '-edit-' . $tabKey,
				'tabLabel' => $this->LL('list.edit.' . $modelType),
				'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
					'controller' => $this,
					'currentDirectory' => addslashes($model->getPath()),
					'modelType' => $modelType,
					'model' => $model,
					'tabKey' => $tabKey,
					'modelCurrentDirectory' => new \SplFileInfo($model->getPath()),
					'routerParam' => $this->getRouterParam('edit'),
					'uniqueId' => str_random(),
				), $this->getAdditionalViewParam()))->render()
			];

		} 
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
	}
	
    public function store($id = null)
	{
		try
		{
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

			$modelType = $input->get('modelType');
			$name = trim($input->get('name'));

			$currentDirectory = new \SplFileInfo($this->getRequest()->input('directory'));

			if (strstr($currentDirectory->getRealPath(), base_path()) === FALSE)
			{
				throw new \Exception($this->LL('error.access-denied-over-base-directory'));
			}

			$validator = \Validator::make(
				[
					'name' => $name,
				],
				[
					'name' => ['required', 'regex:/^[\w .-]+$/u'],
				]
			);

			if ($validator->fails())
			{
				throw (new \Telenok\Core\Interfaces\Exception\Validate())->setMessageError($validator->messages());
			}
			
			$modelPath = $currentDirectory->getRealPath() . DIRECTORY_SEPARATOR . $name;

            if (file_exists($modelPath))
            {
				throw new \Exception($this->LL('error.file.exists'));
            }
            
			if ($modelType == 'directory')
			{
				\File::makeDirectory($modelPath, 0775, true, true);
			}
			else if ($modelType == 'file')
			{
				\File::put($modelPath, $this->getRequest()->input('content', ''));
			}
			else
			{
				throw new \Exception($this->LL('error.create.unknown-file-type'));
			}
			
			return [
				'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
					'controller' => $this,
					'currentDirectory' => addslashes($currentDirectory->getRealPath()),
                    'success' => true,
					'modelType' => $modelType,
					'model' => new \SplFileInfo($modelPath),
					'modelCurrentDirectory' => $currentDirectory,
					'routerParam' => $this->getRouterParam('update'),
					'uniqueId' => str_random(),  
				), $this->getAdditionalViewParam()))->render()
			];
		}
		catch (\Telenok\Core\Interfaces\Exception\Validate $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw $e;
		}
	}
	
    public function update($id = null)
	{
		try
		{
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 
            
			$modelType = $input->get('modelType');
			$modelPath = $input->get('modelPath');
			$directory = trim($input->get('directory'));
			$name = trim($input->get('name'));

			$currentDirectory = new \SplFileInfo($directory);
			$model = new \SplFileInfo($modelPath);
			
			if (strstr($currentDirectory->getRealPath(), base_path()) === FALSE || strstr($model->getPath(), base_path()) === FALSE)
			{
				throw new \Exception($this->LL('error.access-denied-over-base-directory'));
			}

			$validator = \Validator::make(
				[
					'name' => $name,
				],
				[
					'name' => ['required', 'regex:/^[\w .-]+$/u'],
				]
			);

			if ($validator->fails())
			{
				throw (new \Telenok\Core\Interfaces\Exception\Validate())->setMessageError($validator->messages());
			}

			if ($modelType == 'directory')
			{
				$pathNew = $currentDirectory->getPathname() . DIRECTORY_SEPARATOR . $name;

				\File::move($model->getRealPath(), $pathNew); 
			}
			else if ($modelType == 'file')
			{
				$pathNew = $currentDirectory->getPathname() . DIRECTORY_SEPARATOR . $name; 

				if (strlen($this->getRequest()->input('content', '')) && \File::size($modelPath) < $this->getMaxSizeToView())
				{
					\File::put($model->getRealPath(), $this->getRequest()->input('content'));
				}

				if ($model->getRealPath() != $pathNew)
				{
					\File::move($model->getRealPath(), $pathNew);
				}
			}
			else
			{
				throw new \Exception($this->LL('error.create.unknown-file-type'));
			}
			
			return [
				'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
					'controller' => $this,
					'currentDirectory' => addslashes($currentDirectory->getRealPath()),
                    'success' => true,
					'modelType' => $modelType,
					'model' => new \SplFileInfo($pathNew),
					'modelCurrentDirectory' => $currentDirectory,
					'routerParam' => $this->getRouterParam('update'),
					'uniqueId' => str_random(),  
				), $this->getAdditionalViewParam()))->render()
			];
		}
		catch (\Telenok\Core\Interfaces\Exception\Validate $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw $e;
		}
	}

    public function delete($id = null, $force = false)
    { 
		try
		{
			$model = new \SplFileInfo(strlen($id) ? $id : $this->getRequest()->input('id'));
			
			if (strstr($model->getPath(), base_path()) === FALSE)
			{
				throw new \Exception($this->LL('error.access-denied-over-base-directory'));
			}

			$name = $model->getFilename();
			
			if (preg_match('/^_delme/', $name) || $force)
			{
				if ($model->isDir())
				{
					\File::deleteDirectory($model->getRealPath());
				}
				else
				{
					\File::delete($model->getRealPath());
				}
			}
			else
			{
				\File::move($model->getRealPath(), $model->getPath() . DIRECTORY_SEPARATOR . '_delme' . date('YmdHis') . '_' . $name);
			}

            return ['success' => 1];
		}
		catch (\Telenok\Core\Interfaces\Exception\Validate $e)
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

			default:
				return [];
				break;
		}
	}
	
	
	
	
	
	
    public function getWizardListContent111()
    {
        return array(
            'content' => view("core::module/files-browser.wizard", array(
                    'controller' => $this,
                    'route' => $this->getRouterEdit(),
                    'uniqueId' => str_random(),
                ))->render() 
        );
    }

    public function getTreeList111()
    {
        $basePath = base_path();
        $basePathLength = \Str::length($basePath);
        
        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());
        
        $id = $basePath . $input->get('id');
        
        $listTree = [];
                
        foreach (\Symfony\Component\Finder\Finder::create()->ignoreDotFiles(true)->ignoreVCS(true)->directories()->in( $id )->depth(0) as $dir)
        { 
            $path = $dir->getPathname();

            $listTree[] = array(
                "data" => $dir->getFilename(),
                "metadata" => array('path' => substr($dir->getPathname(), $basePathLength, \Str::length($path) - $basePathLength)),
                "state" => "closed",
                "children" => [],
            );
        }
        
        if (!$input->get('id'))
        {
            $listTree = array(
                'data' => array(
                    "title" => "Root node", 
                    "attr" => array('id' => 'root-not-delete'), 
                ),
                "state" => "open",
                'children' => $listTree
            );
        }
        
        return $listTree;
    } 
} 