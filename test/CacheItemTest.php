<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache;

use DateInterval;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;

/**
 * @coversDefaultClass \Atoms\Cache\CacheItem
 */
class CacheItemTest extends TestCase
{
    private const MINUTES = 60;
    private const HOURS = 60 * self::MINUTES;
    private const DAYS = 24 * self::HOURS;

    /**
     * @var string
     */
    private $key = 'key';

    /**
     * @var string
     */
    private $value = 'value';

    /**
     * @var \Atoms\Cache\CacheItem
     */
    private $cacheItem;

    /**
     * @before
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cacheItem = new CacheItem($this->key, $this->value);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldImplementPsrCacheItemInterface(): void
    {
        $this->assertInstanceOf(CacheItemInterface::class, $this->cacheItem);
    }

    /**
     * @test
     * @covers ::getKey
     * @covers ::__construct
     */
    public function shouldReturnKey(): void
    {
        $this->assertEquals($this->key, $this->cacheItem->getKey());
    }

    /**
     * @test
     * @covers ::get
     * @covers ::__construct
     */
    public function shouldReturnValue(): void
    {
        $this->assertEquals($this->value, $this->cacheItem->get());
    }

    /**
     * @test
     * @covers ::get
     */
    public function shouldReturnNullIfNoHit(): void
    {
        $this->assertNull($this->cacheItem->expiresAt(date_create('-1 days'))->get());
    }

    /**
     * @test
     * @covers ::set
     */
    public function shouldSetValue(): void
    {
        $newValue = 'new-value';

        $this->assertEquals($newValue, $this->cacheItem->set($newValue)->get());
    }

    /**
     * @test
     * @covers ::isHit
     */
    public function shouldBeHitIfNoExpiration(): void
    {
        $this->assertTrue($this->cacheItem->isHit());
        $this->assertTrue($this->cacheItem->expiresAt(date_create('-1 days'))->expiresAt(null)->isHit());
    }

    /**
     * @test
     * @covers ::isHit
     */
    public function shouldBeHitIfNotExpired(): void
    {
        $this->assertTrue($this->cacheItem->expiresAt(date_create('+1 day'))->isHit());
    }

    /**
     * @test
     * @covers ::isHit
     */
    public function shouldNotBeHitIfNoValue(): void
    {
        $this->assertFalse((new CacheItem('key'))->isHit());
    }

    /**
     * @test
     * @covers ::set
     */
    public function shouldBeHitIfValueIsExplicitlyNull(): void
    {
        $this->assertTrue((new CacheItem('key', null))->isHit());
        $this->assertTrue((new CacheItem('key'))->set(null)->isHit());
    }

    /**
     * @test
     * @covers ::isHit
     * @covers ::expiresAt
     */
    public function shouldNotBeHitIfExpired(): void
    {
        $this->assertFalse($this->cacheItem->expiresAt(date_create('-1 day'))->isHit());
    }

    /**
     * @test
     * @group time-sensitive
     * @covers ::expiresAfter
     * @covers ::getNumberOfSecondsInInterval
     * @covers ::getNumberOfDaysInInterval
     */
    public function expiresAfterShouldAcceptSeconds(): void
    {
        $this->cacheItem->expiresAfter(1);
        $this->assertTrue($this->cacheItem->isHit());

        sleep(2);

        $this->assertFalse($this->cacheItem->isHit());
    }

    /**
     * @test
     * @group time-sensitive
     * @covers ::expiresAfter
     * @covers ::getNumberOfSecondsInInterval
     * @covers ::getNumberOfDaysInInterval
     */
    public function expiresAfterShouldAcceptDateIntervalWithDays(): void
    {
        $this->cacheItem->expiresAfter(date_create()->diff(date_create('40 days 1 second')));

        sleep(40 * self::DAYS);

        $this->assertTrue($this->cacheItem->isHit());

        sleep(2);

        $this->assertFalse($this->cacheItem->isHit());
    }

    /**
     * @test
     * @group time-sensitive
     * @covers ::expiresAfter
     * @covers ::getNumberOfSecondsInInterval
     * @covers ::getNumberOfDaysInInterval
     */
    public function expiresAfterShouldAcceptDateIntervalWithoutDays(): void
    {
        $this->cacheItem->expiresAfter(new DateInterval('P40DT1S'));

        sleep(40 * self::DAYS);

        $this->assertTrue($this->cacheItem->isHit());

        sleep(2);

        $this->assertFalse($this->cacheItem->isHit());
    }

    /**
     * @test
     * @covers ::getExpiration
     */
    public function shouldReturnExpiration(): void
    {
        $this->assertNull($this->cacheItem->getExpiration());

        $now = date_create();

        $this->assertEquals($now, $this->cacheItem->expiresAt($now)->getExpiration());
    }
}
