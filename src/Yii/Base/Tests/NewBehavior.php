<?php
class NewBehavior extends \Yii\Base\CBehavior
{
	public function test()
	{
		$this->owner->behaviorCalled=true;
		return 2;
	}
}

