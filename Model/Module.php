<?php
/**
 * Magenuts
 *
 * This source file is subject to the Magenuts Software License, which is available at https://magenuts.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magenuts.com for more information.
 *
 * @category  Magenuts
 * @package   magenuts/module-core
 * @version   1.0.0
 * @copyright Copyright (C) 2020 Magenuts (https://magenuts.com/)
 */



namespace Magenuts\Core\Model;

use Magento\Framework\Module\Dir\Reader as DirReader;
use Magento\Framework\Module\FullModuleList;

class Module
{
    /**
     * @var array
     */
    private static $modules = null;

    /**
     * @var FullModuleList
     */
    protected $fullModuleList;

    /**
     * @var DirReader
     */
    protected $dirReader;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $group;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $installedVersion;

    /**
     * @var string
     */
    protected $latestVersion;

    /**
     * @var string
     */
    protected $url;

    /**
     * Module constructor.
     * @param FullModuleList $fullModuleList
     * @param DirReader $dirReader
     */
    public function __construct(
        FullModuleList $fullModuleList,
        DirReader $dirReader
    ) {
        $this->fullModuleList = $fullModuleList;
        $this->dirReader      = $dirReader;
    }

    /**
     * @return array
     */
    public function getAllModules()
    {
        if (self::$modules == null) {
            $framework = $this->getFrameworkVersion();

            try {
                self::$modules = \Zend_Json::decode(file_get_contents(
                    'http://magenuts.com/pc/modules/?framework=' . $framework
                ));
            } catch (\Exception $e) {
                self::$modules = [];
            }

            if (!is_array(self::$modules)) {
                self::$modules = [];
            }
        }

        return self::$modules;
    }

    /**
     * @return array
     */
    public function getInstalledModules()
    {
        $modules = [];
        foreach ($this->fullModuleList->getAll() as $module) {
            if (substr($module['name'], 0, strlen('Magenuts_')) == 'Magenuts_') {
                $modules[] = $module['name'];
            }
        }

        return $modules;
    }

    /**
     * @param string $moduleName
     *
     * @return $this
     */
    public function load($moduleName)
    {
        $this->moduleName = $moduleName;

        $modules = $this->getAllModules();

        if (array_key_exists(strtolower($moduleName), $modules)) {
            $m = $modules[strtolower($moduleName)];

            $this->name          = $m['name'];
            $this->latestVersion = $m['version'];
            $this->url           = $m['url'];
        }

        $composer = $this->getComposerInformation($moduleName);

        if ($composer) {
            $this->installedVersion = $composer['version'];

            $this->group = $composer['description'];

            if (isset($composer['group'])) {
                $this->group = $composer['group'];
            }
        }

        return $this;
    }

    /**
     * @param string $moduleName
     *
     * @return array|false
     */
    public function getComposerInformation($moduleName)
    {
        try {
            $dir = $this->dirReader->getModuleDir("", $moduleName);
        } catch (\Exception $e) {
            return false;
        }

        if (file_exists($dir . '/composer.json')) {
            return \Zend_Json::decode(file_get_contents($dir . '/composer.json'));
        }

        if (file_exists($dir . '/../../composer.json')) {
            return \Zend_Json::decode(file_get_contents($dir . '/../../composer.json'));
        }

        return false;
    }

    /**
     * @return string|bool
     */
    public function getFrameworkVersion()
    {
        $backend = $this->dirReader->getModuleDir("", "Magento_Backend");
        $fw      = dirname($backend) . '/framework';
        if (file_exists($fw . '/composer.json')) {
            $json = \Zend_Json::decode(file_get_contents($fw . '/composer.json'));

            if (isset($json['version'])) {
                return $json['version'];
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @return string
     */
    public function getInstalledVersion()
    {
        return $this->installedVersion;
    }

    /**
     * @return string
     */
    public function getLatestVersion()
    {
        return $this->latestVersion;
    }

    /**
     * @return bool
     */
    public function isMetaPackage()
    {
        if ($this->moduleName == 'Magenuts_SearchUltimate'
            || $this->moduleName == 'Magenuts_SearchElasticUltimate') {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Recursively retrieve names of the required Magenuts modules for given $moduleName.
     *
     * @param string $moduleName
     *
     * @return string[]
     */
    public function getRequiredModuleNames($moduleName)
    {
        return $this->requiredModuleNames($moduleName, []);
    }

    /**
     * @param string $moduleName
     * @param array  $modules
     *
     * @return array
     */
    public function requiredModuleNames($moduleName, array $modules)
    {
        $composerInfo = $this->getComposerInformation($moduleName);
        if (!isset($composerInfo['require'])) {
            return $modules;
        }

        foreach (array_keys($composerInfo['require']) as $package) {
            if (strpos($package, "magenuts/") === false) {
                continue;
            }
            foreach ($this->getInstalledModules() as $installedModule) {
                if (in_array($installedModule, $modules)) {
                    continue;
                }
                $installedModuleInfo = $this->getComposerInformation($installedModule);
                if (!$installedModuleInfo) {
                    continue;
                }
                if ($package == $installedModuleInfo['name']) {
                    $modules[] = $installedModule;
                    $modules   = $this->requiredModuleNames($installedModule, $modules);
                }
            }
        }

        return $modules;
    }
}
