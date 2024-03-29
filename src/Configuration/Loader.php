<?php
declare(strict_types=1);

/*
 * (c) 2023 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the MIT license
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://opensource.org/licenses/MIT>
 */

namespace GrossbergerGeorg\RunScript\Configuration;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class Loader
{
    public function __construct(
        private readonly LanguageServiceFactory $languageServiceFactory,
    ) {
    }

    /**
     * @return array|Script[]
     */
    public function loadAll(): array
    {
        $scripts = [];
        $languageService = $this->languageServiceFactory->createFromUserPreferences($this->getBackendUser());

        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script'] ?? [] as $key => $config) {
            $icon = $config['icon'] ?? 'runscript-terminal';
            $script = $config['script'];

            if (strncmp($script, 'EXT:', 4) === 0) {
                $script = GeneralUtility::getFileAbsFileName($script);
            }

            $label = $config['label'];

            if (strncmp($label, 'LLL:', 4) === 0) {
                $label = $languageService->sL($label);
            }

            $reload = (int) ($config['reloadBackend'] ?? 0);

            $scripts[] = GeneralUtility::makeInstance(
                Script::class,
                $icon,
                $key,
                $label,
                $script,
                $reload
            );
        }

        return $scripts;
    }

    /**
     * @return array|Script[]
     */
    public function getForCurrentUser(): array
    {
        $allowAll = !($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['run_script']['disableForAdmins'] ?? false) && $this->getBackendUser()->isAdmin();
        $allowed = GeneralUtility::trimExplode(',', $this->getBackendUser()->getTSConfig()['tx_runscript.']['allowed'] ?? '', true);

        if (!$allowed && !$allowAll) {
            return [];
        }

        $available = $this->loadAll();

        if ($available && !$allowAll) {
            $available = array_values(array_filter($available, function (Script $script) use ($allowed) {
                return in_array($script->key, $allowed);
            }));
        }

        return $available;
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
