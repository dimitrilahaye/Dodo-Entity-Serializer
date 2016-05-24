<?php

namespace DodoPhpLab\DodoEntitySerializerTest\Classes;

class User{

	private $name; 			## String
	private $age; 			## int
	private $married; 		## bool
	private $birth; 		## \DateTime
	private $subscribers; 	## array

	public function getName(){
		return $this->name;
	}

	public function getAge(){
		return $this->age;
	}

	public function getSubscribers(){
		return $this->subscribers;
	}

	public function getMarried(){
		return $this->married;
	}

	public function getBirth(){
		return $this->birth;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function setAge($age){
		$this->age = $age;
	}

	public function setSubscribers($subscribers){
		$this->subscribers = $subscribers;
	}
	
	public function addSubscriber(User $subscriber){
		$this->subscribers[] = $subscriber;
	}

	public function setMarried($married){
		$this->married = $married;
	}

	public function setBirth(\DateTime $birth){
		$this->birth = $birth;
	}

}