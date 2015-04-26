<?php namespace Telenok\Core\Module\Packages\ComposerManager;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller { 
    
    protected $key = 'composer-manager';
    protected $parent = 'packages';
    protected $icon = 'fa fa-file';

	protected $presentation = 'tree-tab-object';
    protected $presentationContentView = 'core::module.composer-manager.content';
    protected $presentationComposerJsonView = 'core::module.composer-manager.composer-json';

	protected $tableColumn = ['name', 'size', 'perm', 'writeable', 'readable', 'updated_at'];
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

    public function getComposerJsonContent()
	{
        return [
            'tabKey' => "{$this->getTabKey()}-{$this->getParent()}-" . str_random(),
            'tabLabel' => 'Composer.json',
            'tabContent' => view($this->presentationComposerJsonView, array(
				'routerParam' => $this->getRouterParam('composer-json-update'),
                'controller' => $this,
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
			throw new \Exception($this->LL('error.json.locked'));
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

			file_put_contents($validateFile, $json);

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

			file_put_contents($lastFile, file_get_contents(base_path('composer.json')));
			file_put_contents(base_path('composer.json'), $json);
		} 
		catch (\Exception $e) 
		{
			\File::delete($validateFile);

			throw new \Exception($this->LL('error.json'));
		}
		
		\File::delete($validateFile);

		return $this->getComposerJsonContent();
	}
	
    public function getList()
    {
		//		$composerLock = json_decode(file_get_contents(base_path('composer.lock')), JSON_UNESCAPED_SLASHES);
		
        $content = []; 

        $input = \Illuminate\Support\Collection::make($this->getRequest()->input());

		$currentDirectory = $input->get('currentDirectory');

		if (strstr($currentDirectory, base_path()) === FALSE)
		{
			$currentDirectory = base_path();
		}
		
		$directory = new \SplFileInfo($currentDirectory);
        
		$sEcho = $input->get('sEcho');
        $uniqueId = $input->get('uniqueId');
        $iDisplayStart = $input->get('iDisplayStart', 0);
        $iTotalDisplayRecords = $input->get('iDisplayLength', 20);
		
        $collection = \Symfony\Component\Finder\Finder::create()->in($directory->getPathname());

        if ($title = trim($input->get('sSearch')))
        {
			$collection->name("*{$title}*");
        }
        
        if ($input->has('filter'))
        {
            $filter = $input->get('filter');

            if ($name = trim(array_get($filter, 'name')))
            {
                $collection->name($name);
            }

            if ($contain = array_get($filter, 'contain'))
            {
                $collection->contains($contain);
            }

            if ($size = (array)array_get($filter, 'size', []))
            {
                $min = intval(array_get($size, 'min', 0));
                $max = intval(array_get($size, 'max', PHP_INT_MAX));
               
                $collection->size('>= ' . $min);
                $collection->size('<= ' . $max);
                
                $collection->files();
            }

            if ($lastModify = (array)array_get($filter, 'last_modify', []))
            {
                $start = array_get($lastModify, 'start');
                $end = array_get($lastModify, 'end');
               
                if ($start)
                {
                    $collection->date('>= ' . $start);
                }
               
                if ($end)
                {
                    $collection->date('<= ' . $end);
                }
            }
        }
        else
        {
            $collection->depth(0);
        }
        
        $collection->sortByType();
        
        $iter = -1;
        $iter2 = 0;
        
        $collection->filter(function($i) use ($iDisplayStart, $iTotalDisplayRecords, &$iter, &$iter2)
        {
            $iter++;
            
            if ($iter < $iDisplayStart || $iter > $iDisplayStart + $iTotalDisplayRecords + 1)
            {
                return false;
            }
            else
            {
                $iter2++;
            }
        });
        
		if ($directory->getPathname() != base_path())
		{
			$link = '<i class="fa fa-level-up"></i> <i class="fa fa-folder"></i> '
					. '<a href="#" onclick="currentDirectory' . $uniqueId . ' = \'' . addslashes($directory->getPath()). '\'; telenok.getPresentation(\'' . $this->getPresentationModuleKey() . '\')'
					. '.reloadDataTableOnClick({url: \'' . $this->getRouterList() . '\', gridId: \'' . $this->getGridId() . '\', data : {uniqueId: \'' . $uniqueId . '\', currentDirectory: \'' . addslashes($directory->getPath()) . '\'}}); return false;">' . $directory->getPath() . '</a>';
			
			$content[] = [
							'tableCheckAll' => '', 
							'name' => $link,
							'size' => '',
							'updated_at' => '',
							'perm' => '',
							'writeable' => '',
							'readable' => '',
							'tableManageItem' => '',
					];
		} 

        foreach($collection as $item)
        {
            $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="' . $item->getRealpath() . '" /><span class="lbl"></span></label>'];

			if ($item->isDir())
			{
				$put['name'] = '<i class="fa fa-folder"></i> '
					. '<a href="#" onclick="currentDirectory' . $uniqueId . ' = \'' . addslashes($item->getPathname()). '\'; telenok.getPresentation(\'' . $this->getPresentationModuleKey() . '\')'
					. '.reloadDataTableOnClick({url: \'' . $this->getRouterList() . '\', gridId: \'' . $this->getGridId() . '\', data : {uniqueId: \'' . $uniqueId . '\', currentDirectory: \'' . addslashes($item->getPathname()) . '\'}}); return false;">' . $item->getFilename() . '</a>';
			}
			else if ($item->isFile())
			{
				$put['name'] = '<i class="fa ' . ($item->isDir() ? 'fa-folder' : 'fa-file-o') . '"></i> ' . $item->getFilename();
			}

			$put['size'] = $item->isDir() ? "" : $item->getSize();
			$put['updated_at'] = date('Y-m-d H:i:s', $item->getATime());
			$put['perm'] = substr(sprintf('%o', $item->getPerms()), -4);
			$put['writeable'] = $item->isWritable();
			$put['readable'] = $item->isReadable();

            $put['tableManageItem'] = $this->getListButton($item);

            $content[] = $put;
        }

        if ($iter2 > $iTotalDisplayRecords)
        {
            array_pop($content);
        }

        return [
            'gridId' => $this->getGridId(),
            'sEcho' => $sEcho,
            'iTotalRecords' => $iter,
            'iTotalDisplayRecords' => $iter,
            'aaData' => $content
        ];
    } 

    public function getListButton($item)
    {
        return '
                <div class="hidden-phone visible-lg btn-group">
				' . (
				$item->isReadable() && ($item->isFile() || $item->isDir()) ? '
                    <button class="btn btn-minier btn-info disable" title="'.$this->LL('list.btn.edit').'" 
                        onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \'' 
                        . $this->getRouterEdit(['id' => $item->getRealPath()]) . '\'});">
                        <i class="fa fa-pencil"></i>
                    </button> ' : ''
				) . '
                    <button class="btn btn-minier btn-danger" title="'.$this->LL('list.btn.delete').'" 
                        onclick="if (confirm(\'' . $this->LL(preg_match('/^_delme/', $item->getFilename()) ? 'notice.delete.force' : 'notice.sure') . '\')) telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').deleteByURL(this, \'' 
                        . $this->getRouterDelete(['id' => $item->getRealPath()]) . '\');">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>';
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

			case 'composer-json-update':
				return [ route("cmf.module.composer-manager.composer-json.update", ['id' => $filePath, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey]) ];
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
	
	public function getTreeContent()
    {
        return;
    }

} 