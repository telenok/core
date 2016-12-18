<?php

namespace Telenok\Core\Module\Packages\InstallerManager;

/**
 * @class Telenok.Core.Module.Packages.InstallerManager.Controller
 * @extends Telenok.Core.Abstraction.Presentation.TreeTab.Controller
 */
class Controller extends \Telenok\Core\Abstraction\Presentation\TreeTab\Controller
{
    protected $key = 'installer-manager';
    protected $parent = 'packages';
    protected $icon = 'fa fa-file';
    protected $presentation = 'tree-tab-object';
    protected $presentationContentView = 'core::module.installer-manager.content';
    protected $presentationModelView = 'core::module.installer-manager.view';
    protected $tableColumn = ['name', 'key', 'description', 'image'];

    public function getTreeContent()
    {
    }

    public function getContent()
    {
        return [
            'tabKey'     => "{$this->getTabKey()}-{$this->getParent()}",
            'tabLabel'   => $this->LL('header.title'),
            'tabContent' => view($this->getPresentationContentView(), [
                'controller'       => $this,
                'currentDirectory' => addslashes(base_path()),
                'fields'           => $this->tableColumn,
                'gridId'           => $this->getGridId(),
                'uniqueId'         => str_random(),
            ])->render(),
        ];
    }

    public function getList()
    {
        $content = [];

        $request = $this->getRequest();

        $list = (array) json_decode(file_get_contents('http://telenok.local/package/lists/json'), true);

        $draw = $request->input('draw');
        $uniqueId = $request->input('uniqueId');
        $pageStart = $request->input('pageStart', 0);
        $iTotalDisplayRecords = $request->input('pageLength', 20);

        foreach ($list as $item) {
            $put = ['tableCheckAll' => '<input type="checkbox" class="ace ace-checkbox-2" name="tableCheckAll[]" value="'.$item['key'].'"><span class="lbl"></span>'];

            $put['name'] = '<i class="fa fa-folder"></i> '.array_get($item, 'title.en');
            $put['key'] = $item['key'];
            $put['description'] = array_get($item, 'description.en');
            $put['image'] = '<img src="'.$item['image_path'].'" alt="" height="140" />';

            $put['tableManageItem'] = $this->getListButton($item);

            $content[] = $put;
        }

        return [
            'gridId'               => $this->getGridId(),
            'draw'                 => $draw,
            'iTotalRecords'        => count($list),
            'iTotalDisplayRecords' => count($list),
            'data'                 => $content,
        ];
    }

    public function getListButton($item)
    {
        return '
                <div class="hidden-phone visible-lg btn-group">
				
                    <button class="btn btn-xs btn-info" 
                        onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \''
                .$this->getRouterView(['id' => $item['key']]).'\'});">
                        <i class="ace-icon glyphicon glyphicon-eye-open bigger-110"></i>
						View
						<i class="ace-icon fa fa-arrow-circle-o-right icon-on-right"></i>
                    </button>
                </div>';
    }

    public function view($id)
    {
        $packageInfo = (array) json_decode(file_get_contents('http://telenok.local/package/view/'.$id.'/json'), true);

        return [
            'tabKey'     => $this->getTabKey().'-new-'.str_random(),
            'tabLabel'   => 'View package',
            'tabContent' => view($this->getPresentationModelView(), array_merge([
                'controller'  => $this,
                'packageInfo' => $packageInfo,
                            ], $this->getAdditionalViewParam()))->render(),
        ];
    }

    public function getRouterView($param = [])
    {
        return route("telenok.module.{$this->getKey()}.view", $param);
    }

    private function getFolderContent($dir)
    {
        $finder = \Symfony\Component\Finder\Finder::create()
                ->ignoreVCS(false)
                ->ignoreDotFiles(false)
                ->depth(0)
                ->in($dir);

        return iterator_to_array($finder);
    }

    public function getPackageRandomKey($packageId, $versionId)
    {
        return substr(md5($packageId.$versionId), 0, 10);
    }

    public function installPackageStatus()
    {
        if (file_exists($file = storage_path('telenok/composer/log'))) {
            return (array) json_decode(file_get_contents($file), true);
        } else {
            return [];
        }
    }

    public function appendLogFile($key, $status)
    {
        $file = storage_path('telenok/composer/log');

        $content = (array) json_decode(file_get_contents($file), true);

        $content[$key] = $status;

        file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    public function initLogFile()
    {
        $file = storage_path('telenok/composer/log');

        if (!file_exists($file)) {
            touch($file);
        } elseif (($sec = time() - filemtime($file)) < 300) {
            abort(500, 'Sorry, other installation process still in progress. Please, wait about '.$sec.'seconds ');
        } else {
            file_put_contents($file, '', LOCK_EX);
        }
    }

    public function installPackage($packageId, $versionId)
    {
        $this->initLogFile();

        try {
            $packageRandomKey = $this->getPackageRandomKey($packageId, $versionId);

            // make composer.json copy
            $this->appendLogFile('create composer.json', $this->LL('Create temporary copy of composer.json'));

            $composerPath = base_path('composer.json');
            $composerTmpPath = base_path('composer.tmp.json');

            file_put_contents($composerTmpPath, file_get_contents($composerPath), LOCK_EX);

            // download package
            $this->appendLogFile('download package', $this->LL('Download package'));

            $url = 'http://telenok.local/account/package/download/'.urlencode($packageId).'/'.urlencode($versionId);
            $url .= '?laravelVersion='.urlencode(app()->version());

            \App\Vendor\Telenok\Core\Model\Web\Domain::active()->get()->each(function ($item) use (&$url) {
                $url .= '&domain[]='.urlencode($item->domain);
            });

            $directory = storage_path('telenok/composer/'.$packageRandomKey);
            $directoryUnzipped = $directory.'/unzipped';

            $packagePath = $directory.'/'.$packageRandomKey.'.zip';

            \File::makeDirectory($directory, 0775, true, true);
            \File::makeDirectory($directoryUnzipped, 0775, true, true);

            file_put_contents($packagePath, file_get_contents($url), LOCK_EX);

            // extract zip file
            $this->appendLogFile('unzip package', $this->LL('Unzip package'));

            $zip = new \ZipArchive();

            if ($zip->open($packagePath) === true) {
                $zip->extractTo($directoryUnzipped);
                $zip->close();
            } else {
                throw \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
            }

            $this->appendLogFile('processing', $this->LL('Package file processing'));

            $contentDir = $this->getFolderContent($directoryUnzipped);

            if (count($contentDir) == 1 && is_dir(reset($contentDir))) {
                $contentDir = $this->getFolderContent((string) reset($contentDir));

                $composerJsonPath = collect($contentDir)->keys()->first(function ($value, $key) {
                    if (strpos($value, 'composer.json') !== false) {
                        return true;
                    } else {
                        return false;
                    }
                });

                if (!$composerJsonPath) {
                    throw new \Exception('Cant find composer.json');
                }

                //modify composer.json
                $this->appendLogFile('modify', $this->LL('Modify composer.json'));

                $jsonArray = json_decode(file_get_contents($composerPath), true);
                $jsonPackageArray = json_decode(file_get_contents($composerJsonPath), true);

                if (!($packageName = array_get($jsonPackageArray, 'name'))) {
                    throw new \Exception('Empty package name');
                }

                if (is_array(array_get($jsonArray, 'repositories'))) {
                    foreach ($jsonArray['repositories'] as &$v) {
                        if ($v['package']['name'] == $packageName) {
                            $v['package']['version'] = 'dev-master';
                            $v['package']['dist']['url'] = $packagePath;
                        }
                    }
                } else {
                    $jsonArray['repositories'][] = [
                        'type'    => 'package',
                        'package' => [
                            'name'    => $packageName,
                            'version' => 'dev-master',
                            'dist'    => [
                                'url'  => $packagePath,
                                'type' => 'zip',
                            ],
                        ],
                    ];
                }

                $this->appendLogFile('store composer.json', $this->LL('Store new version of composer.json'));

                file_put_contents($composerPath, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);

                $this->appendLogFile('run', $this->LL('Run composer'));

                $input = new \Symfony\Component\Console\Input\ArrayInput([
                    'command'       => 'require',
                    '--working-dir' => base_path(),
                    'packages'      => [$packageName],
                ]);

                ob_start();

                $out = new \Symfony\Component\Console\Output\NullOutput();
                $application = new \Composer\Console\Application();
                $application->setAutoExit(false);
                $exitCode = $application->run($input, $out);

                ob_end_clean();

                if ($exitCode !== 0) {
                    throw new \Exception('Error during installing package. Sorry, try again.');
                }

                \File::deleteDirectory($directoryUnzipped);
                \File::delete(storage_path('telenok/composer/log'));
            } else {
                throw \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Package archive contains wrong amount of folders');
            }

            return ['finished' => $this->LL('Finished')];
        } catch (\Exception $ex) {
            \File::delete(storage_path('telenok/composer/log'));

            return [
                'exception' => $ex->getMessage(),
            ];
        }
    }

    public function create()
    {
        try {
            $modelType = $this->getRequest()->input('modelType');
            $currentDirectory = realpath($this->getRequest()->input('currentDirectory'));

            if (strstr($currentDirectory, base_path()) === false) {
                throw new \Exception($this->LL('error.access-denied-over-base-directory'));
            }

            $tabKey = str_random();

            return [
                'tabKey'     => $this->getTabKey().'-new-'.$tabKey,
                'tabLabel'   => $this->LL('list.create.'.(in_array($modelType, ['file', 'directory'], true) ? $modelType : '')),
                'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller'            => $this,
                    'currentDirectory'      => addslashes($currentDirectory),
                    'modelType'             => $modelType,
                    'model'                 => null,
                    'tabKey'                => $tabKey,
                    'modelCurrentDirectory' => new \SplFileInfo($currentDirectory),
                    'routerParam'           => $this->getRouterParam('create'),
                    'uniqueId'              => str_random(),
                                ], $this->getAdditionalViewParam()))->render(),
            ];
        } catch (\Exception $ex) {
            return [
                'exception' => $ex->getMessage(),
            ];
        }
    }

    public function edit($id = 0)
    {
        $id = $id ?: $this->getRequest()->input('id');

        try {
            $model = new \SplFileInfo($id);

            if (strstr($model->getPath(), base_path()) === false) {
                throw new \Exception($this->LL('error.access-denied-over-base-directory'));
            }

            if ($model->isFile()) {
                $modelType = 'file';
            } elseif ($model->isDir()) {
                $modelType = 'directory';
            }

            $tabKey = str_random();

            return [
                'tabKey'     => $this->getTabKey().'-edit-'.$tabKey,
                'tabLabel'   => $this->LL('list.edit.'.$modelType),
                'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller'            => $this,
                    'currentDirectory'      => addslashes($model->getPath()),
                    'modelType'             => $modelType,
                    'model'                 => $model,
                    'tabKey'                => $tabKey,
                    'modelCurrentDirectory' => new \SplFileInfo($model->getPath()),
                    'routerParam'           => $this->getRouterParam('edit'),
                    'uniqueId'              => str_random(),
                                ], $this->getAdditionalViewParam()))->render(),
            ];
        } catch (\Exception $ex) {
            return [
                'exception' => $ex->getMessage(),
            ];
        }
    }

    public function store($id = null)
    {
        try {
            $input = $this->getRequest();

            $modelType = $input->input('modelType');
            $name = trim($input->input('name'));

            $currentDirectory = new \SplFileInfo($this->getRequest()->input('directory'));

            if (strstr($currentDirectory->getRealPath(), base_path()) === false) {
                throw new \Exception($this->LL('error.access-denied-over-base-directory'));
            }

            $validator = app('validator')->make(
                ['name' => $name],
                ['name' => ['required', 'regex:/^[\w .-]+$/u']]
            );

            if ($validator->fails()) {
                throw (new \Telenok\Core\Support\Exception\Validator())->setMessageError($validator->messages());
            }

            $modelPath = $currentDirectory->getRealPath().DIRECTORY_SEPARATOR.$name;

            if (file_exists($modelPath)) {
                throw new \Exception($this->LL('error.file.exists'));
            }

            if ($modelType == 'directory') {
                \File::makeDirectory($modelPath, 0775, true, true);
            } elseif ($modelType == 'file') {
                file_put_contents($modelPath, $this->getRequest()->input('content', ''), LOCK_EX);
            } else {
                throw new \Exception($this->LL('error.create.unknown-file-type'));
            }

            return [
                'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller'            => $this,
                    'currentDirectory'      => addslashes($currentDirectory->getRealPath()),
                    'success'               => true,
                    'modelType'             => $modelType,
                    'model'                 => new \SplFileInfo($modelPath),
                    'modelCurrentDirectory' => $currentDirectory,
                    'routerParam'           => $this->getRouterParam('update'),
                    'uniqueId'              => str_random(),
                                ], $this->getAdditionalViewParam()))->render(),
            ];
        } catch (\Telenok\Core\Support\Exception\Validator $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($id = null)
    {
        try {
            $input = $this->getRequest();

            $modelType = $input->input('modelType');
            $modelPath = $input->input('modelPath');
            $directory = trim($input->ginputet('directory'));
            $name = trim($input->input('name'));

            $currentDirectory = new \SplFileInfo($directory);
            $model = new \SplFileInfo($modelPath);

            if (strstr($currentDirectory->getRealPath(), base_path()) === false || strstr($model->getPath(), base_path()) === false) {
                throw new \Exception($this->LL('error.access-denied-over-base-directory'));
            }

            $validator = app('validator')->make(
                    [
                'name' => $name,
                    ], [
                'name' => ['required', 'regex:/^[\w .-]+$/u'],
                    ]
            );

            if ($validator->fails()) {
                throw (new \Telenok\Core\Support\Exception\Validator())->setMessageError($validator->messages());
            }

            if ($modelType == 'directory') {
                $pathNew = $currentDirectory->getPathname().DIRECTORY_SEPARATOR.$name;

                \File::move($model->getRealPath(), $pathNew);
            } elseif ($modelType == 'file') {
                $pathNew = $currentDirectory->getPathname().DIRECTORY_SEPARATOR.$name;

                if (strlen($this->getRequest()->input('content', '')) && \File::size($modelPath) < $this->getMaxSizeToView()) {
                    file_put_contents($model->getRealPath(), $this->getRequest()->input('content'), LOCK_EX);
                }

                if ($model->getRealPath() != $pathNew) {
                    \File::move($model->getRealPath(), $pathNew);
                }
            } else {
                throw new \Exception($this->LL('error.create.unknown-file-type'));
            }

            return [
                'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller'            => $this,
                    'currentDirectory'      => addslashes($currentDirectory->getRealPath()),
                    'success'               => true,
                    'modelType'             => $modelType,
                    'model'                 => new \SplFileInfo($pathNew),
                    'modelCurrentDirectory' => $currentDirectory,
                    'routerParam'           => $this->getRouterParam('update'),
                    'uniqueId'              => str_random(),
                                ], $this->getAdditionalViewParam()))->render(),
            ];
        } catch (\Telenok\Core\Support\Exception\Validator $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id = null, $force = false)
    {
        try {
            $model = new \SplFileInfo(strlen($id) ? $id : $this->getRequest()->input('id'));

            if (strstr($model->getPath(), base_path()) === false) {
                throw new \Exception($this->LL('error.access-denied-over-base-directory'));
            }

            $name = $model->getFilename();

            if (preg_match('/^_delme/', $name) || $force) {
                if ($model->isDir()) {
                    \File::deleteDirectory($model->getRealPath());
                } else {
                    \File::delete($model->getRealPath());
                }
            } else {
                \File::move($model->getRealPath(), $model->getPath().DIRECTORY_SEPARATOR.'_delme'.date('YmdHis').'_'.$name);
            }

            return ['success' => 1];
        } catch (\Telenok\Core\Support\Exception\Validator $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function editList($id = null)
    {
        $input = $this->getRequest();

        $ids = $input->input('tableCheckAll');

        if (empty($ids)) {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $content = [];
        $modelType = 'file';

        foreach ($ids as $id_) {
            try {
                $model = new \SplFileInfo($id_);

                if (strstr($model->getPath(), base_path()) === false) {
                    throw new \Exception($this->LL('error.access-denied-over-base-directory'));
                }

                if ($model->isFile()) {
                    $modelType = 'file';
                } elseif ($model->isDir()) {
                    $modelType = 'directory';
                }

                $tabKey = str_random();

                $content[] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller'            => $this,
                    'currentDirectory'      => addslashes($model->getPath()),
                    'modelType'             => $modelType,
                    'model'                 => $model,
                    'tabKey'                => $tabKey,
                    'modelCurrentDirectory' => new \SplFileInfo($model->getPath()),
                    'routerParam'           => $this->getRouterParam('edit'),
                    'uniqueId'              => str_random(),
                                ], $this->getAdditionalViewParam()))->render();
            } catch (\Exception $ex) {
                return [
                    'exception' => $ex->getMessage(),
                ];
            }
        }

        return [
            'tabKey'     => $this->getTabKey().'-edit-'.md5(implode('', $ids)),
            'tabLabel'   => $this->LL('list.edit.'.$modelType),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content),
        ];
    }

    public function deleteList($id = null, $ids = [])
    {
        $ids = empty($ids) ? (array) $this->getRequest()->input('tableCheckAll') : $ids;

        if (empty($ids)) {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $error = false;

        try {
            foreach ($ids as $id_) {
                $this->delete($id_);
            }
        } catch (\Exception $e) {
            $error = true;
        }

        if ($error) {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        } else {
            return \Response::json(['success' => 1]);
        }
    }

    public function getRouterParam($action = '', $filePath = null, $tabKey = null)
    {
        switch ($action) {
            case 'create':
                return [$this->getRouterStore(['saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', false), 'tabKey' => $tabKey])];
                break;

            case 'edit':
                return [$this->getRouterUpdate(['id' => $filePath, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey])];
                break;

            case 'store':
                return [$this->getRouterUpdate(['saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey])];
                break;

            case 'update':
                return [$this->getRouterUpdate(['id' => $filePath, 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'tabKey' => $tabKey])];
                break;

            default:
                return [];
                break;
        }
    }

    public function getWizardListContent111()
    {
        return [
            'content' => view('core::module/files-browser.wizard', [
                'controller' => $this,
                'route'      => $this->getRouterEdit(),
                'uniqueId'   => str_random(),
            ])->render(),
        ];
    }

    public function getTreeList111()
    {
        $basePath = base_path();
        $basePathLength = \Str::length($basePath);

        $input = $this->getRequest();

        $id = $basePath.$input->input('id');

        $listTree = [];

        foreach (\Symfony\Component\Finder\Finder::create()->ignoreDotFiles(true)->ignoreVCS(true)->directories()->in($id)->depth(0) as $dir) {
            $path = $dir->getPathname();

            $listTree[] = [
                'data'     => $dir->getFilename(),
                'metadata' => ['path' => substr($dir->getPathname(), $basePathLength, \Str::length($path) - $basePathLength)],
                'state'    => 'closed',
                'children' => [],
            ];
        }

        if (!$input->input('id')) {
            $listTree = [
                'data' => [
                    'title' => 'Root node',
                    'attr'  => ['id' => 'root-not-delete'],
                ],
                'state'    => 'open',
                'children' => $listTree,
            ];
        }

        return $listTree;
    }

    public function processExternalEvent($controller)
    {
        if ($this->getRequest()->input('backend_external_event') == 'install_package' && ($package = $this->getRequest()->input('package_key'))) {
            $controller->addJsCode('
				<script>
					telenok.addModule(
						"'.$this->getKey().'", 
						"'.route('telenok.module.installer-manager.action.param').'", 
						function(moduleKey) 
						{
							telenok.processModuleContent(moduleKey);
							
							telenok.getPresentation("tree-tab-object-installer-manager").addTabByURL({
								url : "'.route('telenok.module.installer-manager.view', ['id' => $package]).'"
							});
						}
					);
				</script>
			');
        }
    }
}
