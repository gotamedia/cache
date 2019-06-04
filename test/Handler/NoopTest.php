<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache\Handler;

use Atoms\Cache\CacheItem;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Atoms\Cache\Handler\Noop
 */
class NoopTest extends TestCase
{
    /**
     * @var \Atoms\Cache\Handler\Noop
     */
    private $handler;

    /**
     * @before
     */
    protected function setUp()
    {
        parent::setUp();

        $this->handler = new Noop();
    }

    /**
     * @test
     */
    public function shouldImplementCacheItemPoolHandler(): void
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->handler);
    }

    /**
     * @test
     * @covers ::getItem
     */
    public function shouldReturnEmptyCacheItemForKey(): void
    {
        $cacheItem = $this->handler->getItem('key');

        $this->assertEquals('key', $cacheItem->getKey());
        $this->assertFalse($cacheItem->isHit());
        $this->assertNull($cacheItem->get());
    }

    /**
     * @test
     * @covers ::getItems
     */
    public function shouldReturnEmptyCacheItemsForKeys(): void
    {
        $keys = [
            'key',
            'another_key'
        ];
        $cacheItems = $this->handler->getItems($keys);

        $this->assertCount(count($keys), array_keys($cacheItems));

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $cacheItems);
            $this->assertFalse($cacheItems[$key]->isHit());
        }
    }

    /**
     * @test
     * @covers ::hasItem
     */
    public function shouldNotHaveAnyItems(): void
    {
        $this->assertFalse($this->handler->hasItem('key'));
    }

    /**
     * @test
     * @covers ::clear
     */
    public function shouldClearCacheItemPool(): void
    {
        $this->assertTrue($this->handler->clear());
    }

    /**
     * @test
     * @covers ::deleteItem
     */
    public function shouldDeleteItemByKey(): void
    {
        $this->assertTrue($this->handler->deleteItem('key'));
    }

    /**
     * @test
     * @covers ::deleteItems
     */
    public function shouldDeleteItemsByKeys(): void
    {
        $this->assertTrue($this->handler->deleteItems(['key']));
    }

    /**
     * @test
     * @covers ::save
     */
    public function shouldSaveCacheItem(): void
    {
        $this->assertTrue($this->handler->save(
            $this->getMockBuilder(CacheItem::class)->disableOriginalConstructor()->getMock()
        ));
    }

    /**
     * @test
     * @covers ::saveDeferred
     */
    public function shouldSaveCacheItemDeferred(): void
    {
        $this->assertTrue($this->handler->saveDeferred(
            $this->getMockBuilder(CacheItem::class)->disableOriginalConstructor()->getMock()
        ));
    }

    /**
     * @test
     * @covers ::commit
     */
    public function shouldCommitDeferredCacheItems(): void
    {
        $this->assertTrue($this->handler->commit());
    }
}
