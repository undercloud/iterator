<?php
namespace Undercloud\Iterator;

/**
 * SPL Iterator extends
 *
 * @package  Iterator
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/iterator
 */

use Traversable;
use IteratorAggregate;

class RandomAccessIterator extends ArrayIterator
{
	/**
	 * @param Traversable $traversable instance
	 */
	public function __construct(Traversable $traversable)
	{
		$array = iterator_to_array(
			$traversable instanceof IteratorAggregate
			? $traversable->getIterator()
			: $traversable
		);

		uksort($array, function() {
			return mt_rand() > mt_rand();
		});

		parent::__construct($array);
	}
}
