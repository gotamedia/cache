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
 * Describes an interface for a cache item pool handler.
 */
interface HandlerInterface
{
    /**
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return a CacheItem object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     * @return \Atoms\Cache\CacheItem
     */
    public function getItem(string $key): CacheItem;

    /**
     * Returns a traversable set of cache items.
     *
     * @param string[] $keys
     * @return array
     */
    public function getItems(array $keys): array;

    /**
     * Confirms if the cache contains specified cache item.
     *
     * @note This method MAY avoid retrieving the cached value for performance reasons.
     *       This could result in a race condition with CacheItemInterface::get(). To avoid
     *       such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     * @return bool
     */
    public function hasItem(string $key): bool;

    /**
     * Deletes all items in the pool.
     *
     * @return bool
     */
    public function clear(): bool;

    /**
     * Removes the item from the pool.
     *
     * @param string $key
     * @return bool
     */
    public function deleteItem(string $key): bool;

    /**
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     * @return bool
     */
    public function deleteItems(array $keys): bool;

    /**
     * Persists a cache item immediately.
     *
     * @param \Atoms\Cache\CacheItem $item
     * @return bool
     */
    public function save(CacheItem $item): bool;

    /**
     * Sets a cache item to be persisted later.
     *
     * @param \Atoms\Cache\CacheItem $item
     * @return bool
     */
    public function saveDeferred(CacheItem $item): bool;

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     */
    public function commit(): bool;
}
