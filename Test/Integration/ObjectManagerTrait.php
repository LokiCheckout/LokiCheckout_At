<?php
declare(strict_types=1);

namespace LokiCheckout\At\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;

trait ObjectManagerTrait
{
    /**
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    private function get(string $className): object
    {
        $object = Bootstrap::getObjectManager()->get($className);
        $this->assertInstanceOf($className, $object);

        return $object;
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    private function create(string $className): object
    {
        $object = Bootstrap::getObjectManager()->create($className);
        $this->assertInstanceOf($className, $object);

        return $object;
    }
}
