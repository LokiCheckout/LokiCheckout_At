<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration\Config\XmlConfig\Value\GridLayout;

use LokiCheckout\At\Test\Integration\ObjectManagerTrait;
use LokiCheckout\Core\Config\XmlConfig;
use Magento\TestFramework\Fixture\Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    use ObjectManagerTrait;

    #[Cache('config', false)]
    public function testCountryIdSpansFullWidth(): void
    {
        $gridLayout = $this->get(XmlConfig::class)->getGridLayout('default_grid');
        $this->assertContains('austria', $gridLayout->getGridNames());

        $block = $gridLayout->getGridByName('austria')->getBlock('country_id');
        $this->assertSame(12, $block->getColSpan());
        $this->assertSame(12, $block->getMdColSpan());
    }

    #[Cache('config', false)]
    #[DataProvider('getInheritedBlockAliases')]
    public function testBlocksAreInheritedFromDefaultGrid(string $blockAlias): void
    {
        $gridLayout = $this->get(XmlConfig::class)->getGridLayout('default_grid');
        $austrianBlock = $gridLayout->getGridByName('austria')->getBlock($blockAlias);
        $defaultBlock = $gridLayout->getGridByName('default')->getBlock($blockAlias);

        $this->assertSame($defaultBlock->getColSpan(), $austrianBlock->getColSpan());
        $this->assertSame($defaultBlock->getMdColSpan(), $austrianBlock->getMdColSpan());
        $this->assertSame($defaultBlock->getSortOrder(), $austrianBlock->getSortOrder());
    }

    /**
     * @return array<int, array{string}>
     */
    public static function getInheritedBlockAliases(): array
    {
        return [
            ['street0'],
            ['postcode'],
            ['city'],
        ];
    }
}
