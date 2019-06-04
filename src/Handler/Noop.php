<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache\Handler;

use Atoms\Cache\CacheItem;

/**
 * A noop implementation of a cache item pool handler.
 */
class Noop implements HandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getItem(string $key): CacheItem
    {
        return new CacheItem($key);
    }

    /**
     * {@inheritDoc}
     */
    public function getItems(array $keys): array
    {
        return array_reduce($keys, function (array $cacheItems, string $key) {
            $cacheItems[$key] = new CacheItem($key);

            return $cacheItems;
        }, []);
    }

    /**
     * {@inheritDoc}
     */
    public function hasItem(string $key): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteItem(string $key): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteItems(array $keys): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function save(CacheItem $item): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function saveDeferred(CacheItem $item): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): bool
    {
        return true;
    }
}
