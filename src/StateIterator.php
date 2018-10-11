<?php
error_reporting(-1);
ini_set('display_errors', 'On');
/**
 * 
 */
class StateIterator implements SeekableIterator
{
	/**
	 * @var Iterator
	 */
	private $iterator;

	/**
	 * @var integer
	 */
	private $index = 0;

	/**
	 * @var mixed
	 */
	private $cacheKey;

	/**
	 * @var mixed
	 */
	private $cacheCurrent;

	/**
	 * @var boolean
	 */
	private $cacheValid;

	/**
	 * @var mixed
	 */
	private $position = false;

	/**
	 * @param Iterator $iterator instance
	 */
	public function __construct(Iterator $iterator)
	{
		$this->iterator = $iterator;
		$this->rewind();
	}

	/**
	 * Seek 
	 * 
	 * @param int $position value
	 * 
	 * @return
	 */
	public function seek($position)
	{
		$this->position = (int) $position;
	}

	/**
	 * Get current iterator index
	 *
	 * @return integer
	 */
	public function index()
	{
		return $this->index;
	}

	/**
	 * Rewind iterator
	 *
	 * @return void
	 */
	public function rewind()
	{
		$this->iterator->rewind();

		$this->index = 0;
		$this->cacheCurrent = $this->iterator->current();
		$this->cacheKey = $this->iterator->key();
		$this->cacheValid = $this->iterator->valid();
		
		if (!($this->iterator instanceof NoRewindIterator)) {
			$this->iterator->next();	
		}

		if (false !== $this->position) {
			for ($i = 0; $i < $this->position; $i++) {
				if (false === $this->cacheValid) {
					return;
				}

				$this->key();
				$this->current();
				$this->valid();
				$this->next();
			}
		}
	

		$this->position = false;
	}

	/**
	 * Return current iterator value
	 *
	 * @return mixed
	 */
	public function current()
	{
		$current = $this->cacheCurrent;
		$this->cacheCurrent = $this->iterator->current();

		return $current;
	}

	/**
	 * Return iterator key
	 *
	 * @return mixed
	 */
	public function key()
	{
		$key = $this->cacheKey;
		$this->cacheKey = $this->iterator->key();

		return $key;
	}

	/**
	 * Get next iteration
	 *
	 * @return mixed
	 */
	public function next()
	{
		$this->index++;

		return $this->iterator->next();
	}

	/**
	 * Check if iterator is valid
	 *
	 * @return bool
	 */
	public function valid()
	{
		$isValid = $this->cacheValid;
		$this->cacheValid = $this->iterator->valid();

		return $isValid;
	}

	/**
	 * Check if index is first
	 *
	 * @return bool
	 */
	public function isFirst()
	{
		return (0 === $this->index);
	}

	/**
	 * Check if index is last
	 *
	 * @return bool
	 */
	public function isLast()
	{
		return !$this->cacheValid;
	}

	/**
	 * Check if index is between first and last
	 *
	 * @return bool
	 */
	public function isMiddle()
	{
		return (!$this->isFirst() and !$this->isLast());
	}

	/**
	 * Check if index is odd
	 *
	 * @return bool
	 */
	public function isOdd()
	{
		return ($this->index % 2 != 0);
	}

	/**
	 * Check if index is even
	 *
	 * @return bool
	 */
	public function isEven()
	{
		return ($this->index % 2 == 0);
	}

	/**
	 * Check if index is divisible by
	 *
	 * @param integer $num parts
	 *
	 * @return bool
	 */
	public function isDivisible($divider = 2)
	{
		if ($divider <= 0 or !is_numeric($divider)) {
			return false;
		}

		return ((($this->index + 1) % (int) $divider) == 0);
	}
}

$s = new StateIterator(
	new LimitIterator(
		new ArrayIterator(
			['foo' => 'bar','bar' => 'baz','zuz' => 'xyz','laz' => 'suz']
		),0,2
	)
);

//$s->seek(3);

foreach($s as $k => $v){
	$p = print_r([
		'index' => $s->index(),
		'key' => $k,
		'value' => $v,
		'isFirst' => $s->isFirst(),
		'isLast' => $s->isLast(),
		'isMiddle' => $s->isMiddle(),
		'isOdd' => $s->isOdd(),
		'isEven' => $s->isEven(),
		'isDivisible3' => $s->isDivisible(3)
	],true);

	echo str_replace(['   ',PHP_EOL], '', $p) . PHP_EOL;
}
