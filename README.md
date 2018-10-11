# stateiterator

<?php

require_once __DIR__ . '/StateIterator.php';

class ReverseIterator implements Iterator
{
	private $iterator;

	public function __construct(Iterator $iterator)
	{
		$this->iterator = new StateIterator($iterator);
		$this->seekPosition = 4;
	}

	public function rewind()
	{
		$this->iterator->rewind();
		foreach($this->iterator as $key => $value){
			if($this->seekPosition -1 == $this->iterator->index()){
				break;
			}
		}
	}

	public function next()
	{
		--$this->seekPosition;
		foreach($this->iterator as $key => $value){
			if($this->seekPosition - 1 == $this->iterator->index()){
				var_dump('i',$key,$value);
				break;
			}
		}
	}
	
	public function current()
	{
		return $this->iterator->current();
	}

	public function key()
	{
		return $this->iterator->key();
	}

	public function valid()
	{
		return ($this->seekPosition !== false and $this->seekPosition >= 0);
	}
}

$r = new ReverseIterator(
	new ArrayIterator(
		[
			'foo',
			'bar',
			'baz',
			'zuz',
			'xyz'
		]
	)
);

foreach($r as $k => $v){
	echo $k . ' ' . $v . PHP_EOL;
}
