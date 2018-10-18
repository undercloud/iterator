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

class SortIterator extends ArrayIterator
{
	/**
	 * @param Traversable $traversable instance
	 * @param callable    $sort        function
	 */
	public function __construct(Traversable $traversable, callable $sort)
	{
		$array = iterator_to_array(
			$traversable instanceof IteratorAggregate
			? $traversable->getIterator()
			: $traversable
		);
		
		usort($array, $sort);

		parent::__construct($array);
	}
}
