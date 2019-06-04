<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache;

use Atoms\Cache\Handler\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @coversDefaultClass \Atoms\Cache\CacheItemPool
 */
class CacheItemPoolTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Atoms\Cache\Handler\HandlerInterface
     */
    private $handlerMock;

    /**
     * @var \Atoms\Cache\CacheItemPool
     */
    private $cacheItemPool;

    /**
     * @before
     */
    protected function setUp()
    {
        parent::setUp();

        $this->handlerMock = $this->getMockBuilder(HandlerInterface::class)
                                  ->getMock();
        $this->cacheItemPool = new CacheItemPool($this->handlerMock);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldImplementPsrCacheItemPoolInterface(): void
    {
        $this->assertInstanceOf(CacheItemPoolInterface::class, $this->cacheItemPool);
    }

    /**
     * @test
     * @covers ::getItem
     * @covers ::validateKey
     */
    public function shouldGetItemByKey(): void
    {
        $cacheItemMock = $this->getMockBuilder(CacheItem::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->handlerMock->expects($this->once())
                          ->method('getItem')
                          ->with($this->equalTo('key'))
                          ->willReturn($cacheItemMock);

        $this->assertSame($cacheItemMock, $this->cacheItemPool->getItem('key'));
    }

    /**
     * @test
     * @covers ::getItem
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     * @expectedExceptionMessageRegExp /string/
     */
    public function getItemShouldThrowIfKeyIsNotString(): void
    {
        $this->cacheItemPool->getItem(0);
    }

    /**
     * @test
     * @covers ::getItem
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function getItemShouldThrowIfKeyIsNotValid(): void
    {
        $this->cacheItemPool->getItem('inv@lid-key');
    }

    /**
     * @test
     * @covers ::getItems
     * @covers ::validateKey
     */
    public function shouldGetItemsByKeys(): void
    {
        $cacheItemMocks = [$this->getMockBuilder(CacheItem::class)->disableOriginalConstructor()->getMock()];
        $this->handlerMock->expects($this->once())
                          ->method('getItems')
                          ->with($this->equalTo(['key']))
                          ->willReturn($cacheItemMocks);

        $this->assertEquals($cacheItemMocks, $this->cacheItemPool->getItems(['key']));
    }

    /**
     * @test
     * @covers ::getItems
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     * @expectedExceptionMessageRegExp /string/
     */
    public function getItemsShouldThrowIfKeyIsNotString(): void
    {
        $this->cacheItemPool->getItems([0]);
    }

    /**
     * @test
     * @covers ::getItems
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function getItemsShouldThrowIfKeyIsNotValid(): void
    {
        $this->cacheItemPool->getItems(['inv@lid-key']);
    }

    /**
     * @test
     * @covers ::hasItem
     * @covers ::validateKey
     */
    public function shouldDetermineIfHasItemByKey(): void
    {
        $this->handlerMock->expects($this->once())
                          ->method('hasItem')
                          ->with($this->equalTo('key'))
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->hasItem('key'));
    }

    /**
     * @test
     * @covers ::hasItem
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     * @expectedExceptionMessageRegExp /string/
     */
    public function hasItemShouldThrowIfKeyIsNotString(): void
    {
        $this->cacheItemPool->hasItem(0);
    }

    /**
     * @test
     * @covers ::hasItem
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function hasItemShouldThrowIfKeyIsNotValid(): void
    {
        $this->cacheItemPool->hasItem('inv@lid-key');
    }

    /**
     * @test
     * @covers ::clear
     */
    public function shouldClearCacheItemPool(): void
    {
        $this->handlerMock->expects($this->once())
                          ->method('clear')
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->clear());
    }

    /**
     * @test
     * @covers ::deleteItem
     * @covers ::validateKey
     */
    public function shouldDeleteItemByKey(): void
    {
        $this->handlerMock->expects($this->once())
                          ->method('deleteItem')
                          ->with($this->equalTo('key'))
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->deleteItem('key'));
    }

    /**
     * @test
     * @covers ::deleteItem
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     * @expectedExceptionMessageRegExp /string/
     */
    public function deleteItemShouldThrowIfKeyIsNotString(): void
    {
        $this->cacheItemPool->deleteItem(0);
    }

    /**
     * @test
     * @covers ::deleteItem
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function deleteItemShouldThrowIfKeyIsNotValid(): void
    {
        $this->cacheItemPool->deleteItem('inv@lid-key');
    }

    /**
     * @test
     * @covers ::deleteItems
     * @covers ::validateKey
     */
    public function shouldDeleteItemsByKeys(): void
    {
        $this->handlerMock->expects($this->once())
                          ->method('deleteItems')
                          ->with($this->equalTo(['key']))
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->deleteItems(['key']));
    }

    /**
     * @test
     * @covers ::deleteItems
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     * @expectedExceptionMessageRegExp /string/
     */
    public function deleteItemsShouldThrowIfKeyIsNotString(): void
    {
        $this->cacheItemPool->deleteItems([0]);
    }

    /**
     * @test
     * @covers ::deleteItems
     * @covers ::validateKey
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function deleteItemsShouldThrowIfKeyIsNotValid(): void
    {
        $this->cacheItemPool->deleteItems(['inv@lid-key']);
    }

    /**
     * @test
     * @covers ::save
     */
    public function shouldSaveCacheItem(): void
    {
        $cacheItemMock = $this->getMockBuilder(CacheItem::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->handlerMock->expects($this->once())
                          ->method('save')
                          ->with($this->equalTo($cacheItemMock))
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->save($cacheItemMock));
    }

    /**
     * @test
     * @covers ::save
     */
    public function saveShouldFailIfNotConcreteCacheItem(): void
    {
        $this->assertFalse($this->cacheItemPool->save($this->getMockBuilder(CacheItemInterface::class)->getMock()));
    }

    /**
     * @test
     * @covers ::saveDeferred
     */
    public function shouldSaveCacheItemDeferred(): void
    {
        $cacheItemMock = $this->getMockBuilder(CacheItem::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->handlerMock->expects($this->once())
                          ->method('saveDeferred')
                          ->with($this->equalTo($cacheItemMock))
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->saveDeferred($cacheItemMock));
    }

    /**
     * @test
     * @covers ::saveDeferred
     */
    public function saveDeferredShouldFailIfNotConcreteCacheItem(): void
    {
        $this->assertFalse($this->cacheItemPool->saveDeferred(
            $this->getMockBuilder(CacheItemInterface::class)->getMock()
        ));
    }

    /**
     * @test
     * @covers ::commit
     */
    public function shouldCommitCacheItemPoolDeferredItems(): void
    {
        $this->handlerMock->expects($this->once())
                          ->method('commit')
                          ->willReturn(true);

        $this->assertTrue($this->cacheItemPool->commit());
    }
}
