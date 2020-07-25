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



namespace Magenuts\Core\Api\Service;

interface ValidationServiceInterface
{
    /**
     * Run validation process.
     *
     * @param string[] $modules - name of modules to run validation for. E.g. Magenuts_Email
     *
     * @return array - result of validation
     */
    public function runValidation(array $modules = []);

    /**
     * Get list of available validators.
     *
     * @return ValidatorInterface[]
     */
    public function getValidators();
}
