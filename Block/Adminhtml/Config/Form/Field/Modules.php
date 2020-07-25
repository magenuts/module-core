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



namespace Magenuts\Core\Block\Adminhtml\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magenuts\Core\Api\Service\ValidationServiceInterface;
use Magenuts\Core\Model\Module;
use Magenuts\Core\Model\ModuleFactory;

class Modules extends Field
{
    /**
     * @var ModuleFactory
     */
    protected $moduleFactory;

    /**
     * @var ValidationServiceInterface
     */
    private $validationService;

    /**
     * Modules constructor.
     * @param ValidationServiceInterface $validationService
     * @param ModuleFactory $moduleFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ValidationServiceInterface $validationService,
        ModuleFactory $moduleFactory,
        Context $context,
        array $data = []
    ) {
        $this->validationService = $validationService;
        $this->moduleFactory     = $moduleFactory;

        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('config/form/field/modules.phtml');
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isMarketplace()
    {
        $flag = true;

        /** mp comment start */

        $flag = false;

        /** mp comment end */

        return $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return \Magenuts\Core\Model\Module[]
     */
    public function getModules()
    {
        $modules = [];

        foreach ($this->moduleFactory->create()->getInstalledModules() as $moduleName) {
            $module = $this->moduleFactory->create()
                ->load($moduleName);

            if ($module->getModuleName() || $module->getName()) {
                $modules[] = $module;
            }
        }

        usort($modules, function ($a, $b) {
            return strcmp($b->getName(), $a->getName());
        });

        return $modules;
    }

    /**
     * Check whether validator available for that module or not.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function isValidationAvailable(Module $module)
    {
        foreach ($this->validationService->getValidators() as $validator) {
            if ($module->getModuleName() == $validator->getModuleName()
                || in_array($validator->getModuleName(), $module->getRequiredModuleNames($module->getModuleName()))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get validation URL for given module.
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getUrl('mstcore/validator/');
    }
}
