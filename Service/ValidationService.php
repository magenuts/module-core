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



namespace Magenuts\Core\Service;

use Magenuts\Core\Api\Service\ValidationServiceInterface;
use Magenuts\Core\Api\Service\ValidatorInterface;
use Magenuts\Core\Model\ModuleFactory;
use Magenuts\Core\Model\Module;

class ValidationService implements ValidationServiceInterface
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * @var ModuleFactory
     */
    private $moduleFactory;

    /**
     * ValidationService constructor.
     * @param ModuleFactory $moduleFactory
     * @param array $validators
     */
    public function __construct(
        ModuleFactory $moduleFactory,
        array $validators = []
    ) {
        $this->moduleFactory = $moduleFactory;
        $this->validators = $validators;
    }

    /**
     * Validation run scenario:
     * 1. Run all validations if no modules passed.
     * 2. Run validation for every module dependency @see \Magenuts\Core\Api\Service\ValidatorInterface::getModules()
     * 3. Run validation if a validator's module name matches a passed module name.
     *
     * {@inheritdoc}
     */
    public function runValidation(array $modules = [])
    {
        $merged = [];
        foreach ($this->validators as $validator) {
            if ($this->canValidate($validator->getModuleName(), $modules) || count($modules) == 0) {
                $result = $validator->validate();

                $merged = array_merge($merged, $result);
            }
        }

        return $merged;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @param string $validatorModuleName
     * @param array $requestedModules
     * @return bool
     */
    private function canValidate($validatorModuleName, array $requestedModules)
    {
        if (empty($requestedModules) || in_array($validatorModuleName, $requestedModules)) {
            return true;
        }
        foreach ($requestedModules as $moduleName) {
            /** @var Module $module */
            $module = $this->moduleFactory->create()->load($moduleName);
            $requiredModules = $module->getRequiredModuleNames($moduleName);
            if (in_array($validatorModuleName, $requiredModules)) {
                return true;
            }
        }

        return false;
    }
}
