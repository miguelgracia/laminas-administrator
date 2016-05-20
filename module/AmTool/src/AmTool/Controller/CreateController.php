<?php

namespace AmTool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use Zend\View\Model\ConsoleModel;
use AmTool\Model\Skeleton;
use AmTool\Model\Utility;
use Zend\Console\ColorInterface as Color;
use Zend\Code\Generator;
use Zend\Code\Reflection;
use Zend\Filter\Word\CamelCaseToDash as CamelCaseToDashFilter;

class CreateController extends AbstractActionController
{

    public function projectAction()
    {
        if (!extension_loaded('zip')) {
            return $this->sendError('You need to install the ZIP extension of PHP');
        }
        if (!extension_loaded('openssl')) {
            return $this->sendError('You need to install the OpenSSL extension of PHP');
        }
        $console = $this->serviceLocator->get('console');
        $tmpDir  = sys_get_temp_dir();
        $request = $this->getRequest();
        $path    = rtrim($request->getParam('path'), '/');

        if (file_exists($path)) {
            return $this->sendError (
                "The directory $path already exists. You cannot create a ZF2 project here."
            );
        }

        $commit = Skeleton::getLastCommit();
        if (false === $commit) { // error on github connection
            $tmpFile = Skeleton::getLastZip($tmpDir);
            if (empty($tmpFile)) {
                return $this->sendError('I cannot access the API of github.');
            }
            $console->writeLine(
                "Warning: I cannot connect to github, I will use the last download of ZF2 Skeleton.",
                 Color::GRAY
            );
        } else {
            $tmpFile = Skeleton::getTmpFileName($tmpDir, $commit);
        }

        if (!file_exists($tmpFile)) {
            if (!Skeleton::getSkeletonApp($tmpFile)) {
                return $this->sendError('I cannot access the ZF2 skeleton application in github.');
            }
        }

        $zip = new \ZipArchive;
        if ($zip->open($tmpFile)) {
            $stateIndex0 = $zip->statIndex(0);
            $tmpSkeleton = $tmpDir . '/' . rtrim($stateIndex0['name'], "/");
            if (!$zip->extractTo($tmpDir)) {
                return $this->sendError("Error during the unzip of $tmpFile.");
            }
            $result = Utility::copyFiles($tmpSkeleton, $path);
            if (file_exists($tmpSkeleton)) {
                Utility::deleteFolder($tmpSkeleton);
            }
            $zip->close();
            if (false === $result) {
                return $this->sendError("Error during the copy of the files in $path.");
            }
        }
        if (file_exists("$path/composer.phar")) {
            exec("php $path/composer.phar self-update");
        } else {
            if (!file_exists("$tmpDir/composer.phar")) {
                if (!file_exists("$tmpDir/composer_installer.php")) {
                    file_put_contents(
                        "$tmpDir/composer_installer.php",
                        '?>' . file_get_contents('https://getcomposer.org/installer')
                    );
                }
                exec("php $tmpDir/composer_installer.php --install-dir $tmpDir");
            }
            copy("$tmpDir/composer.phar", "$path/composer.phar");
        }
        chmod("$path/composer.phar", 0755);
        $console->writeLine("ZF2 skeleton application installed in $path.", Color::GREEN);
        $console->writeLine("In order to execute the skeleton application you need to install the ZF2 library.");
        $console->writeLine("Execute: \"composer.phar install\" in $path");
        $console->writeLine("For more info in $path/README.md");
    }

    public function controllerAction()
    {
        $console = $this->serviceLocator->get('console');
        $tmpDir  = sys_get_temp_dir();
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $module  = $request->getParam('module');
        $path    = $request->getParam('path', '.');

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            return $this->sendError(
                "The path $path doesn't contain a ZF2 application. I cannot create a module here."
            );
        }
        if (file_exists("$path/module/$module/src/$module/Controller/$name")) {
            return $this->sendError(
                "The controller $name already exists in module $module."
            );
        }

        $ucName     = ucfirst($name);
        $ctrlPath   = $path . '/module/' . $module . '/src/' . $module . '/Controller/' . $ucName.'Controller.php';
        $controller = $ucName . 'Controller';

        $code = new Generator\ClassGenerator();
        $code->setNamespaceName(ucfirst($module) . '\Controller')
             ->addUse('Zend\Mvc\Controller\AbstractActionController')
             ->addUse('Zend\View\Model\ViewModel');

        $code->setName($controller)
             ->addMethods(array(
                new Generator\MethodGenerator(
                    'indexAction',
                    array(),
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    'return new ViewModel();'
                ),
             ))
             ->setExtendedClass('AbstractActionController');

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($code),
            )
        );

        $filter = new CamelCaseToDashFilter();
        $viewfolder = strtolower($filter->filter($module));

        $dir = $path . "/module/$module/view/$viewfolder/" . strtolower($filter->filter($name));
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $phtml = false;
        $phtmlPath = $dir . "/index.phtml";
        if (file_put_contents($phtmlPath, 'Action "index", controller "'.$ucName.'", module "'.$module.'".')) {
            $phtml = true;
        }

        if (file_put_contents($ctrlPath, $file->generate()) && $phtml == true) {
            $console->writeLine("The controller $name has been created in module $module.", Color::GREEN);
        } else {
            $console->writeLine("There was an error during controller creation.", Color::RED);
        }
    }

    public function methodAction()
    {
        $console        = $this->serviceLocator->get('console');
        $request        = $this->getRequest();
        $action         = $request->getParam('name');
        $controller     = $request->getParam('controllerName');
        $module         = $request->getParam('module');
        $path           = $request->getParam('path', '.');
        $ucController   = ucfirst($controller);
        $controllerPath = sprintf('%s/module/%s/src/%s/Controller/%sController.php', $path, $module, $module, $ucController);
        $class          = sprintf('%s\\Controller\\%sController', $module, $ucController);


        $console->writeLine("Creating action '$action' in controller '$module\\Controller\\$controller'.", Color::YELLOW);

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            return $this->sendError(
                "The path $path doesn't contain a ZF2 application. I cannot create a controller action."
            );
        }
        if (!file_exists($controllerPath)) {
            return $this->sendError(
                "The controller $controller does not exists in module $module. I cannot create a controller action."
            );
        }

        $fileReflection  = new Reflection\FileReflection($controllerPath, true);
        $classReflection = $fileReflection->getClass($class);

        $classGenerator = Generator\ClassGenerator::fromReflection($classReflection);
        $classGenerator->addUse('Zend\Mvc\Controller\AbstractActionController')
                       ->addUse('Zend\View\Model\ViewModel')
                       ->setExtendedClass('AbstractActionController');

        if ($classGenerator->hasMethod($action . 'Action')) {
            return $this->sendError(
                "The action $action already exists in controller $controller of module $module."
            );
        }

        $classGenerator->addMethods(array(
            new Generator\MethodGenerator(
                $action . 'Action',
                array(),
                Generator\MethodGenerator::FLAG_PUBLIC,
                'return new ViewModel();'
            ),
        ));

        $fileGenerator = new Generator\FileGenerator(
            array(
                'classes'  => array($classGenerator),
            )
        );

        $filter    = new CamelCaseToDashFilter();
        $phtmlPath = sprintf(
            '%s/module/%s/view/%s/%s/%s.phtml',
            $path,
            $module,
            strtolower($filter->filter($module)),
            strtolower($filter->filter($controller)),
            strtolower($filter->filter($action))
        );
        if (!file_exists($phtmlPath)) {
            $contents = sprintf("Module: %s\nController: %s\nAction: %s", $module, $controller, $action);
            if (file_put_contents($phtmlPath, $contents)) {
                $console->writeLine(sprintf("Created view script at %s", $phtmlPath), Color::GREEN);
            } else {
                $console->writeLine(sprintf("An error occurred when attempting to create view script at location %s", $phtmlPath), Color::RED);
            }
        }

        if (file_put_contents($controllerPath, $fileGenerator->generate())) {
            $console->writeLine(sprintf('The action %s has been created in controller %s\\Controller\\%s.', $action, $module, $controller), Color::GREEN);
        } else {
            $console->writeLine("There was an error during action creation.", Color::RED);
        }
    }

    public function adminModuleAction()
    {
        $this->moduleAction();

        $request = $this->getRequest();
        $currentParams = $request->getParams();

        $moduleName = $request->getParam('name');

        $currentParams->set('module',$moduleName);

        $currentParams->set('name', $moduleName);

        $request->setParams($currentParams);

        $this->adminControllerAction();
        $this->adminFormAction();
        $this->adminModelAction();
        $this->adminTableAction();

    }

    public function moduleAction()
    {
        $console = $this->serviceLocator->get('console');
        $tmpDir  = sys_get_temp_dir();
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $path    = rtrim($request->getParam('path'), '/');

        if (empty($path)) {
            $path = '.';
        }

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            return $this->sendError(
                "The path $path doesn't contain a ZF2 application. I cannot create a module here."
            );
        }
        if (file_exists("$path/module/$name")) {
            return $this->sendError(
                "The module $name already exists."
            );
        }

        $filter = new CamelCaseToDashFilter();
        $viewfolder = strtolower($filter->filter($name));

        $name = ucfirst($name);
        mkdir("$path/module/$name/config", 0777, true);
        mkdir("$path/module/$name/src/$name/Controller", 0777, true);
        mkdir("$path/module/$name/src/$name/Form", 0777, true);
        mkdir("$path/module/$name/view/$viewfolder", 0777, true);
        mkdir("$path/module/$name/view/$viewfolder/$viewfolder-module", 0777, true);

        // Create the Module.php
        file_put_contents("$path/module/$name/Module.php", Skeleton::getModule($name));

        // Create the module.config.php
        file_put_contents("$path/module/$name/config/module.config.php", Skeleton::getModuleConfig($name));

        // Add the module in application.config.php
        $application = require "$path/config/application.config.php";
        if (!in_array($name, $application['modules'])) {
            $application['modules'][] = $name;
            copy ("$path/config/application.config.php", "$path/config/application.config.old");
            $content = <<<EOD
<?php

EOD;

            $content .= 'return '. Skeleton::exportConfig($application) . ";\n";
            file_put_contents("$path/config/application.config.php", $content);
        }
        if ($path === '.') {
            $console->writeLine("The module $name has been created", Color::GREEN);
        } else {
            $console->writeLine("The module $name has been created in $path", Color::GREEN);
        }
    }

    public function adminModelAction()
    {
        $console = $this->serviceLocator->get('console');
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $module  = $request->getParam('module');
        $path    = $request->getParam('path', '.');

        $modelPath = "$path/module/$name/src/$name/Model";

        if (!is_dir($modelPath)) {
            mkdir($modelPath, 0777, true);
        }

        $name = preg_replace("/^Am/","",$name);

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            $console->writeLine("The path $path doesn't contain a ZF2 application. I cannot create a module here.", Color::RED);
            return;
        }
        if (file_exists("$path/module/$module/src/$module/Model/{$name}Model.php")) {
            $console->writeLine("The model $name already exists in module $module.", Color::RED);
            return;
        }

        $ucName     = ucfirst($name);
        $ctrlPath   = $path . '/module/' . $module . '/src/' . $module . '/Model/' . $ucName.'Model.php';
        $model = $ucName . 'Model';

        $code = new Generator\ClassGenerator();
        $code->setNamespaceName(ucfirst($module) . '\Model')
            ->addUse('Administrator\Model\AdministratorModel');

        $code->setName($model)
            ->setExtendedClass('AdministratorModel');

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($code),
            )
        );

        if (file_put_contents($ctrlPath, $file->generate())) {
            $console->writeLine("The model $name has been created in module $module.", Color::GREEN);
        } else {
            $console->writeLine("There was an error during model creation.", Color::RED);
        }
    }

    public function adminTableAction()
    {
        $console = $this->serviceLocator->get('console');
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $module  = $request->getParam('module');
        $path    = $request->getParam('path', '.');

        $modelPath = "$path/module/$name/src/$name/Model";

        if (!is_dir($modelPath)) {
            mkdir($modelPath, 0777, true);
        }

        $name = preg_replace("/^Am/","",$name);

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            $console->writeLine("The path $path doesn't contain a ZF2 application. I cannot create a module here.", Color::RED);
            return;
        }
        if (file_exists("$path/module/$module/src/$module/Model/{$name}Table.php")) {
            $console->writeLine("The table $name already exists in module $module.", Color::RED);
            return;
        }

        $ucName     = ucfirst($name);
        $ctrlPath   = $path . '/module/' . $module . '/src/' . $module . '/Model/' . $ucName.'Table.php';
        $table = $ucName . 'Table';

        $code = new Generator\ClassGenerator();
        $code->setNamespaceName(ucfirst($module) . '\Model')
            ->addUse('Administrator\Model\AdministratorTable');

        $code->setName($table)
            ->addProperty('table','',Generator\PropertyGenerator::FLAG_PROTECTED)
            ->setExtendedClass('AdministratorTable');

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($code),
            )
        );

        if (file_put_contents($ctrlPath, $file->generate())) {
            $console->writeLine("The table $name has been created in module $module.", Color::GREEN);
        } else {
            $console->writeLine("There was an error during table creation.", Color::RED);
        }
    }

    public function adminFormAction()
    {
        $console = $this->serviceLocator->get('console');
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $module  = $request->getParam('module');
        $path    = $request->getParam('path', '.');

        $formPath = "$path/module/$name/src/$name/Form";

        if (!is_dir($formPath)) {
            mkdir($formPath, 0777, true);
        }

        $name = preg_replace("/^Am/","",$name);

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            $console->writeLine("The path $path doesn't contain a ZF2 application. I cannot create a module here.", Color::RED);
            return;
        }
        if (file_exists("$path/module/$module/src/$module/Form/{$name}Form.php")) {
            $console->writeLine("The form $name already exists in module $module.", Color::RED);
            return;
        }

        $ucName     = ucfirst($name);
        $ctrlPath   = $path . '/module/' . $module . '/src/' . $module . '/Form/' . $ucName.'Form.php';
        $form = $ucName . 'Form';

        $code = new Generator\ClassGenerator();
        $code->setNamespaceName(ucfirst($module) . '\Form')
            ->addUse('Zend\Form\Form');

        $code->setName($form)
            ->setExtendedClass('Form');

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($code),
            )
        );

        if (file_put_contents($ctrlPath, $file->generate())) {
            $console->writeLine("The form $name has been created in module $module.", Color::GREEN);
        } else {
            $console->writeLine("There was an error during form creation.", Color::RED);
        }
    }

    public function adminControllerAction()
    {
        $console = $this->serviceLocator->get('console');

        $request = $this->getRequest();
        $name    = $request->getParam('name') . 'Module';
        $module  = $request->getParam('module');
        $path    = $request->getParam('path', '.');

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            $console->writeLine("The path $path doesn't contain a ZF2 application. I cannot create a module here.", Color::RED);
            return;
        }
        if (file_exists("$path/module/$module/src/$module/Controller/{$name}Controller.php")) {
            $console->writeLine("The controller $name already exists in module $module.", Color::RED);
            return;
        }

        $ucName     = ucfirst($name);
        $ctrlPath   = $path . '/module/' . $module . '/src/' . $module . '/Controller/' . $ucName.'Controller.php';
        $controller = $ucName . 'Controller';

        $code = new Generator\ClassGenerator();
        $code->setNamespaceName(ucfirst($module) . '\Controller')
            ->addUse('Administrator\Controller\AuthController')
            ->addUse('Zend\View\Model\ViewModel');

        $tableProperty = strtolower(preg_replace("/^Am/","",$module)) . 'Table';

        $code->setName($controller)
            ->addProperty($tableProperty,null,Generator\PropertyGenerator::FLAG_PROTECTED)
            ->addMethods(array(
                new Generator\MethodGenerator(
                    'setControllerVars',
                    array(),
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    "\$this->{$tableProperty} = \$this->sm->get('{$module}\\Model\\".ucfirst($tableProperty)."');
\$this->formService  = \$this->sm->get('Administrator\\Service\\AdministratorFormService')->setTable(\$this->{$tableProperty});"
                ),
                new Generator\MethodGenerator(
                    'indexAction',
                    array(),
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    'return new ViewModel();'
                ),
                new Generator\MethodGenerator(
                    'addAction',
                    array(),
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    'return new ViewModel();'
                ),
                new Generator\MethodGenerator(
                    'editAction',
                    array(),
                    Generator\MethodGenerator::FLAG_PUBLIC,
                    'return new ViewModel();'
                ),
            ))
            ->setExtendedClass('AuthController');

        $file = new Generator\FileGenerator(
            array(
                'classes'  => array($code),
            )
        );

        $filter = new CamelCaseToDashFilter();
        $viewfolder = strtolower($filter->filter($module));

        $dir = $path . "/module/$module/view/$viewfolder/" . strtolower($filter->filter($name));
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $phtml = array();
        $phtmlPath = $dir . "/index.phtml";
        if (file_put_contents($phtmlPath, Skeleton::getIndexView())) {
            $phtml[] = true;
        }

        $phtmlPath = $dir . "/add.phtml";
        if (file_put_contents($phtmlPath, Skeleton::getAddView())) {
            $phtml[] = true;
        }

        $phtmlPath = $dir . "/edit.phtml";
        if (file_put_contents($phtmlPath, Skeleton::getEditView())) {
            $phtml[] = true;
        }

        if (file_put_contents($ctrlPath, $file->generate()) && count($phtml) == 3) {
            $console->writeLine("The controller $name has been created in module $module.", Color::GREEN);
        } else {
            $console->writeLine("There was an error during controller creation.", Color::RED);
        }
    }

    /**
     * Send an error message to the console
     *
     * @param  string $msg
     * @return ConsoleModel
     */
    protected function sendError($msg)
    {
        $m = new ConsoleModel();
        $m->setErrorLevel(2);
        $m->setResult($msg . PHP_EOL);
        return $m;
    }
}
