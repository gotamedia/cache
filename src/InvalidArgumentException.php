<?php

/**
 * Copyright (c) 2019 Gota Media
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atoms\Cache;

use Exception;
use Psr\Cache\InvalidArgumentException as PsrInvalidArgumentException;

/**
 * A cache specific exception to be thrown if invalid arguments are given.
 */
class InvalidArgumentException extends Exception implements PsrInvalidArgumentException
{
}
