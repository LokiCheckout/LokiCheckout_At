<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration\Config\XmlConfig\Value;

use LokiCheckout\Core\Config\XmlConfig;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class GridLayoutTest extends TestCase
{
    public function testGetGridByCountryId(): void
    {
        $xmlConfig = Bootstrap::getObjectManager()->get(XmlConfig::class);
        $grid = $xmlConfig->getGridLayout('default_grid')->getGridByCountryId('AT');

        $this->assertSame('austria', $grid->getName());
        $this->assertSame('default', $grid->getParent());
    }
}
