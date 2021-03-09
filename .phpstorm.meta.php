<?php
declare(strict_types=1);

namespace PHPSTORM_META {
    override(\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(0), type(0));
    override(\TYPO3\CMS\Extbase\Object\ObjectManager::get(0), type(0));
    override(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface::get(0), type(0));

    override(
        \TYPO3\TestingFramework\Core\BaseTestCase::getAccessibleMock(0),
        map([
            '@&\PHPUnit\Framework\MockObject\MockObject&\TYPO3\TestingFramework\Core\AccessibleObjectInterface',
        ])
    );

    override(
        \PHPUnit\Framework\TestCase::createMock(0),
        map([
            '@&\PHPUnit\Framework\MockObject\MockObject',
        ])
    );

    override(
        \PHPUnit\Framework\TestCase::createStub(0),
        map([
            '@&\PHPUnit\Framework\MockObject\Stub',
        ])
    );

    override(
        \PHPUnit\Framework\TestCase::createConfiguredMock(0),
        map([
            '@&\PHPUnit\Framework\MockObject\MockObject',
        ])
    );

    override(
        \PHPUnit\Framework\TestCase::createPartialMock(0),
        map([
            '@&\PHPUnit\Framework\MockObject\MockObject',
        ])
    );

    override(
        \PHPUnit\Framework\TestCase::createTestProxy(0),
        map([
            '@&\PHPUnit\Framework\MockObject\MockObject',
        ])
    );

    override(
        \PHPUnit\Framework\TestCase::getMockForAbstractClass(0),
        map([
            '@&\PHPUnit\Framework\MockObject\MockObject',
        ])
    );
}
