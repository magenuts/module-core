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



namespace Magenuts\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;
use Magento\Store\Model\StoreManagerInterface;
use Magenuts\Core\Model\Config;
use Magenuts\Core\Model\Module;

class CompileLessObserver implements ObserverInterface
{
    /**
     * @var string
     */
    private $viewSourceBasePath;

    /**
     * @var Module
     */
    private $module;

    /**
     * @var Reader
     */
    private $moduleReader;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * CompileLessObserver constructor.
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param Module $module
     * @param Reader $moduleReader
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        Module $module,
        Reader $moduleReader
    ) {
        $this->config       = $config;
        $this->storeManager = $storeManager;
        $this->module       = $module;
        $this->moduleReader = $moduleReader;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        $layout = $observer->getData('layout');

        if ($this->config->isLessCompilationEnabled()) {
            /** @var \Magento\Framework\View\Page\Config\Structure $pageConfig */
            $pageConfig = $layout->getReaderContext()->getPageConfigStructure();
            $this->prepareAssetData();

            if ($this->isPreprocessed()) {
                $pageConfig->addAssets(
                    'Magenuts_Core::css/source/include_all_modules.css',
                    [
                        'content_type' => 'css',
                        'src'          => 'Magenuts_Core::css/source/include_all_modules.css',
                    ]
                );
            }
        }
    }

    /**
     * @return bool
     */
    private function isPreprocessed()
    {
        $this->viewSourceBasePath = $this->moduleReader->getModuleDir(Dir::MODULE_VIEW_DIR, 'Magenuts_Core') . '/frontend/web/css/source/';

        if (!file_exists($this->viewSourceBasePath . 'processedModules.json')) {
            return false;
        }

        if (!empty(file_get_contents($this->viewSourceBasePath . 'processedModules.json'))) {
            $processedModules = json_decode(file_get_contents($this->viewSourceBasePath . 'processedModules.json'), true);
        } else {
            return false;
        }

        foreach ($processedModules as $name => $version) {
            if ($this->module->load($name)->getInstalledVersion() != $version) {
                return false;
            }
        }

        $processedModulesDiff = array_diff(array_column($processedModules, 0), $this->module->getInstalledModules());
        $processedData        = file_exists($this->viewSourceBasePath . 'include_all_modules.less');

        if (!$processedData || count($processedModulesDiff) > 0) {
            return false;
        }

        return true;
    }

    private function prepareAssetData()
    {
        if (!$this->isPreprocessed()) {
            $modulesToImport = [];

            $filesToImport = [];

            foreach ($this->module->getInstalledModules() as $name) {
                $modulesToImport[$name] = $this->module->load($name)->getInstalledVersion();
                $moduleViewSourcePath   = $this->moduleReader->getModuleDir(Dir::MODULE_VIEW_DIR, $name) . '/frontend/web/css/source/';

                if (file_exists($moduleViewSourcePath . '_module.less')) {
                    $filesToImport[] = '@import "' . $name . '::css/source/_module.less"';
                }
            }

            if (!empty($filesToImport)) {
                try {
                    file_put_contents($this->viewSourceBasePath . 'processedModules.json', json_encode($modulesToImport));

                    $import = file_get_contents($this->viewSourceBasePath . '_utilities.less') . "\n" . implode(';' . "\n", $filesToImport) . ';';
                    file_put_contents($this->viewSourceBasePath . 'include_all_modules.less', $import);
                } catch (\Exception $e) {

                }
            }
        }
    }
}
