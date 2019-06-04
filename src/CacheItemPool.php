<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache;

use Atoms\Cache\Handler\HandlerInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * An implementation of a cache item pool.
 */
class CacheItemPool implements CacheItemPoolInterface
{
    /**
     * @var string
     */
    private const INVALID_KEY_CHARACTERS = '{}()/\\@:';

    /**
     * @var \Atoms\Cache\Handler\HandlerInterface
     */
    private $handler;

    /**
     * Creates a new cache item pool instance.
     *
     * @param \Atoms\Cache\Handler\HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritDoc}
     */
    public function getItem($key)
    {
        return $this->handler->getItem($this->validateKey($key));
    }

    /**
     * {@inheritDoc}
     */
    public function getItems(array $keys = array()): array
    {
        return $this->handler->getItems(array_map([$this, 'validateKey'], $keys));
    }

    /**
     * {@inheritDoc}
     */
    public function hasItem($key)
    {
        return $this->handler->hasItem($this->validateKey($key));
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        return $this->handler->clear();
    }

    /**
     * {@inheritDoc}
     */
    public function deleteItem($key)
    {
        return $this->handler->deleteItem($this->validateKey($key));
    }

    /**
     * {@inheritDoc}
     */
    public function deleteItems(array $keys)
    {
        return $this->handler->deleteItems(array_map([$this, 'validateKey'], $keys));
    }

    /**
     * {@inheritDoc}
     */
    public function save(CacheItemInterface $item)
    {
        if (!$item instanceof CacheItem) {
            return false;
        }

        return $this->handler->save($item);
    }

    /**
     * {@inheritDoc}
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        if (!$item instanceof CacheItem) {
            return false;
        }

        return $this->handler->saveDeferred($item);
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
         return $this->handler->commit();
    }

    /**
     * Validates the given cache key throwing an exception if invalid.
     *
     * @param mixed $key
     * @return string
     * @throws \Atoms\Cache\InvalidArgumentException
     */
    private function validateKey($key): string
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('Expected a string');
        }

        if (strlen(str_replace(str_split(self::INVALID_KEY_CHARACTERS), '', $key)) !== strlen($key)) {
            throw new InvalidArgumentException('The key cannot contain any of: {}()/\\@:');
        }

        return $key;
    }
}
