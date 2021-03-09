<?php
declare(strict_types=1);

/*
 * (c) 2021 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the Mozilla Public License 2.0
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://www.mozilla.org/en-US/MPL/2.0/>
 */

namespace GrossbergerGeorg\RunScript\Toolbar;

use GrossbergerGeorg\RunScript\Configuration\Loader;
use GrossbergerGeorg\RunScript\Configuration\Script;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
abstract class AjaxRequestHandler
{
    /**
     * @var LanguageService
     */
    protected $languageService;

    /**
     * @var Loader
     */
    protected $configurationLoader;

    public function process(Request $request): Response
    {
        $key = $request->getQueryParams()['script'] ?? '';

        if (!$key) {
            $result = ResultMessage::error($this->ll('error.noscript', 'No script defined'));
        } else {
            $script = null;

            foreach ($this->configurationLoader->getForCurrentUser() as $availableScript) {
                if ($availableScript->getKey() === $key) {
                    $script = $availableScript;
                    break;
                }
            }

            if ($script instanceof Script) {
                $result = $this->handleScript($script);
            } else {
                $result = ResultMessage::error($this->ll('error.invalidscript', 'No such script is available'));
            }
        }

        return new JsonResponse($result->jsonSerialize());
    }

    protected function ll(string $key, string $default): string
    {
        return $this->languageService->sL('LLL:EXT:run_scripts/Resources/Private/Language/locallang.xlf:' . $key) ?: $default;
    }

    abstract protected function handleScript(Script $script): ResultMessage;
}
