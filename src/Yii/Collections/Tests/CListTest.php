<?php

class ListItem
{
	public $data='data';
}

class CListTest extends CTestCase
{
	protected $list;
	protected $item1, $item2, $item3;

	public function setUp()
	{
		$this->list=new \Yii\Collections\CList;
		$this->item1=new ListItem;
		$this->item2=new ListItem;
		$this->item3=new ListItem;
		$this->list->add($this->item1);
		$this->list->add($this->item2);
	}

	public function tearDown()
	{
		$this->list=null;
		$this->item1=null;
		$this->item2=null;
		$this->item3=null;
	}

	public function testConstruct()
	{
		$a=array(1,2,3);
		$list=new \Yii\Collections\CList($a);
		$this->assertEquals(3,$list->getCount());
		$list2=new \Yii\Collections\CList($this->list);
		$this->assertEquals(2,$list2->getCount());
	}

	public function testGetReadOnly()
	{
		$list = new \Yii\Collections\CList(null, true);
		$this->assertTrue($list->getReadOnly(), 'List is not read-only');
		$list = new \Yii\Collections\CList(null, false);
		$this->assertFalse($list->getReadOnly(), 'List is read-only');
	}

	public function testGetCount()
	{
		$this->assertEquals(2,$this->list->getCount());
		$this->assertEquals(2,$this->list->Count);
	}

	public function testAdd()
	{
		$this->list->add(null);
		$this->list->add($this->item3);
		$this->assertEquals(4,$this->list->getCount());
		$this->assertEquals(3,$this->list->indexOf($this->item3));
	}

	public function testInsertAt()
	{
		$this->list->insertAt(0,$this->item3);
		$this->assertEquals(3,$this->list->getCount());
		$this->assertEquals(2,$this->list->indexOf($this->item2));
		$this->assertEquals(0,$this->list->indexOf($this->item3));
		$this->assertEquals(1,$this->list->indexOf($this->item1));
		$this->setExpectedException('\Yii\Base\CException');
		$this->list->insertAt(4,$this->item3);
	}

	public function testCanNotInsertWhenReadOnly()
	{
		$list = new \Yii\Collections\CList(array(), true);
		$this->setExpectedException('\Yii\Base\CException');
		$list->insertAt(1, 2);
	}

	public function testRemove()
	{
		$this->list->remove($this->item1);
		$this->assertEquals(1,$this->list->getCount());
		$this->assertEquals(-1,$this->list->indexOf($this->item1));
		$this->assertEquals(0,$this->list->indexOf($this->item2));
		
		$this->assertFalse($this->list->remove($this->item1));

	}

	public function testRemoveAt()
	{
		$this->list->add($this->item3);
		$this->list->removeAt(1);
		$this->assertEquals(-1,$this->list->indexOf($this->item2));
		$this->assertEquals(1,$this->list->indexOf($this->item3));
		$this->assertEquals(0,$this->list->indexOf($this->item1));
		$this->setExpectedException('\Yii\Base\CException');
		$this->list->removeAt(2);
	}

	public function testCanNotRemoveWhenReadOnly()
	{
		$list = new \Yii\Collections\CList(array(1, 2, 3), true);
		$this->setExpectedException('\Yii\Base\CException');
		$list->removeAt(2);
	}

	public function testClear()
	{
		$this->list->clear();
		$this->assertEquals(0,$this->list->getCount());
		$this->assertEquals(-1,$this->list->indexOf($this->item1));
		$this->assertEquals(-1,$this->list->indexOf($this->item2));
	}

	public function testContains()
	{
		$this->assertTrue($this->list->contains($this->item1));
		$this->assertTrue($this->list->contains($this->item2));
		$this->assertFalse($this->list->contains($this->item3));
	}

	public function testIndexOf()
	{
		$this->assertEquals(0,$this->list->indexOf($this->item1));
		$this->assertEquals(1,$this->list->indexOf($this->item2));
		$this->assertEquals(-1,$this->list->indexOf($this->item3));
	}

	public function testCopyFrom()
	{
		$array=array($this->item3,$this->item1);
		$this->list->copyFrom($array);
		$this->assertTrue(count($array)==2 && $this->list[0]===$this->item3 && $this->list[1]===$this->item1);
		$this->setExpectedException('\Yii\Base\CException');
		$this->list->copyFrom($this);
	}

	public function testMergeWith()
	{
		$array=array($this->item3,$this->item1);
		$this->list->mergeWith($array);
		$this->assertTrue($this->list->getCount()==4 && $this->list[0]===$this->item1 && $this->list[3]===$this->item1);
		$this->setExpectedException('\Yii\Base\CException');
		$this->list->mergeWith($this);
	}

	public function testToArray()
	{
		$array=$this->list->toArray();
		$this->assertTrue(count($array)==2 && $array[0]===$this->item1 && $array[1]===$this->item2);
	}

	public function testArrayRead()
	{
		$this->assertTrue($this->list[0]===$this->item1);
		$this->assertTrue($this->list[1]===$this->item2);
		$this->setExpectedException('\Yii\Base\CException');
		$a=$this->list[2];
	}

	public function testGetIterator()
	{
		$n=0;
		$found=0;
		foreach($this->list as $index=>$item)
		{
			foreach($this->list as $a=>$b);	// test of iterator
			$n++;
			if($index===0 && $item===$this->item1)
				$found++;
			if($index===1 && $item===$this->item2)
				$found++;
		}
		$this->assertTrue($n==2 && $found==2);
	}

	public function testArrayMisc()
	{
		$this->assertEquals($this->list->Count,count($this->list));
		$this->assertTrue(isset($this->list[1]));
		$this->assertFalse(isset($this->list[2]));
	}

	public function testOffsetSetAdd()
	{
		$list = new \Yii\Collections\CList(array(1, 2, 3));
		$list->offsetSet(null, 4);
		$this->assertEquals(array(1, 2, 3, 4), $list->toArray());
	}

	public function testOffsetSetReplace()
	{
		$list = new \Yii\Collections\CList(array(1, 2, 3));
		$list->offsetSet(1, 4);
		$this->assertEquals(array(1, 4, 3), $list->toArray());
	}

	public function testOffsetUnset()
	{
		$list = new \Yii\Collections\CList(array(1, 2, 3));
		$list->offsetUnset(1);
		$this->assertEquals(array(1, 3), $list->toArray());
	}

	public function testIteratorCurrent()
	{
		$list = new \Yii\Collections\CList(array('value1', 'value2'));
		$val = $list->getIterator()->current();
		$this->assertEquals('value1', $val);
	}
}
