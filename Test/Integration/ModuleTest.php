<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration;

use LokiCheckout\Core\Config\LokiCheckoutModules;
use Magento\Framework\Module\ModuleList;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    use ObjectManagerTrait;

    public function testModuleIsEnabledAfterCore(): void
    {
        $moduleNames = $this->get(ModuleList::class)->getNames();
        $this->assertContains('LokiCheckout_At', $moduleNames);
        $this->assertGreaterThan(
            array_search('LokiCheckout_Core', $moduleNames, true),
            array_search('LokiCheckout_At', $moduleNames, true)
        );
    }

    public function testModuleIsRegisteredWithLokiCheckout(): void
    {
        $lokiCheckoutModules = $this->get(LokiCheckoutModules::class);
        $this->assertContains('LokiCheckout_At', $lokiCheckoutModules->getModules());
        $this->assertContains('LokiCheckout_At', $lokiCheckoutModules->getActiveModules());
        $this->assertTrue($lokiCheckoutModules->hasModule('LokiCheckout_At'));
    }
}
