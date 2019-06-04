<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache;

use DateInterval;
use DateTimeInterface;
use Psr\Cache\CacheItemInterface;

/**
 * An implementation of a cache item.
 */
class CacheItem implements CacheItemInterface
{
    /**
     * @var int
     */
    private const DAYS = 60 * 60 * 24;

    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $hasValue;

    /**
     * @var \DateTimeInterface|null
     */
    private $expiration;

    /**
     * Creates a new cache item instance.
     *
     * @param string $key
     * @param mixed|null $value
     */
    public function __construct(string $key, $value = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->hasValue = func_num_args() > 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->isHit() ? $this->value : null;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiration(): ?DateTimeInterface
    {
        return $this->expiration;
    }

    /**
     * {@inheritDoc}
     */
    public function isHit()
    {
        if (!$this->hasValue) {
            return false;
        }

        if (!is_null($this->expiration) && time() > $this->expiration->getTimestamp()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function set($value)
    {
        $this->value = $value;
        $this->hasValue = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAt($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAfter($time)
    {
        $expirationInSeconds = !is_null($time) ? $this->getNumberOfSecondsInInterval($time) : null;
        $this->expiration = !is_null($expirationInSeconds) ? date_create("+ {$expirationInSeconds} seconds") : null;

        return $this;
    }

    /**
     * Determines the number of seconds in a given interval.
     *
     * @param \DateInterval|int $interval
     * @return int
     */
    private function getNumberOfSecondsInInterval($interval): int
    {
        if (!$interval instanceof DateInterval) {
            return (int)$interval;
        }

        return $this->getNumberOfDaysInInterval($interval) * self::DAYS + $interval->s;
    }

    /**
     * Determines the number of days in a given interval.
     *
     * @param \DateInterval $interval
     * @return int
     */
    private function getNumberOfDaysInInterval(DateInterval $interval): int
    {
        if ($interval->days === false) {
            $interval = date_create()->diff(date_create()->add($interval));
        }

        return $interval->days;
    }
}
