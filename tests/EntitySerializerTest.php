<?php 

use DodoPhpLab\DodoEntitySerializer\Classes\EntitySerializer;
use DodoPhpLab\DodoEntitySerializerTest\Classes\User;

define("__USER__", "DodoPhpLab\DodoEntitySerializerTest\Classes\User");

class EntitySerializerTest extends PHPUnit_Framework_TestCase {

  private $serializer;  

  public function setUp() {
    $this->serializer = EntitySerializer::getInstance();
  }

  public function testConvertEntityToJsonWithString(){
    $user_1 = new User();
    $user_1->setName("John Doe");
    $json = $this->serializer->objectToJson(__USER__, $user_1);
    $this->assertEquals('{"name":"John Doe"}', $json);
  }

  public function testConvertEntityToJsonWithInt(){
    $user_1 = new User();
    $user_1->setAge(18);
    $json = $this->serializer->objectToJson(__USER__, $user_1);
    $this->assertEquals('{"age":18}', $json);
  }

  public function testConvertEntityToJsonWithBool(){
    $user_1 = new User();
    $user_1->setMarried(true);
    $json = $this->serializer->objectToJson(__USER__, $user_1);
    $this->assertEquals('{"married":true}', $json);
  }

  public function testConvertEntityToJsonWithArray(){
    $user_1 = new User();
    $user_2 = new User();
    $user_3 = new User();
    $user_2->setName("Jane Doe");
    $user_3->setName("Wade Wilson");
    $user_1->addSubscriber($user_2);
    $user_1->addSubscriber($user_3);
    $user_1->setName("John Doe");
    $json = $this->serializer->objectToJson(__USER__, $user_1);
    $this->assertEquals('{"name":"John Doe","subscribers":[{"name":"Jane Doe"},{"name":"Wade Wilson"}]}', $json);
  }

  public function testConvertEntityToJsonWithDate(){
    $user_1 = new User();
    $user_1->setBirth(new DateTime("2009-06-13"));
    $json = $this->serializer->objectToJson(__USER__, $user_1);
    $this->assertEquals('{"birth":"2009-06-13"}', $json);
  }

  public function testConvertEntitiesArrayToJson(){
    $user_1 = new User();
    $user_1->setName("John Doe");
    $user_2 = new User();
    $user_2->setName("Jane Doe");
    $user_3 = new User();
    $user_3->setName("Wade Wilson");
    $users = [$user_1, $user_2, $user_3];
    $json = $this->serializer->objectToJson(__USER__, $users);
    $this->assertEquals('[{"name":"John Doe"},{"name":"Jane Doe"},{"name":"Wade Wilson"}]', $json);
  }

  public function testConvertJsonToObjectWithString(){
    $json = '{"name":"John Doe"}';
    $user = $this->serializer->jsonToObject(__USER__, $json);
    $this->assertEquals("John Doe", $user->getName());
  }

  public function testConvertJsonToObjectWithInt(){
    $json = '{"age":18}';
    $user = $this->serializer->jsonToObject(__USER__, $json);
    $this->assertEquals(18, $user->getAge());
  }

  public function testConvertJsonToObjectWithBool(){
    $json = '{"married":true}';
    $user = $this->serializer->jsonToObject(__USER__, $json);
    $this->assertEquals(true, $user->getMarried());
  }

  public function testConvertJsonToObjectWithArray(){
    $json = '{"name":"John Doe","subscribers":[{"name":"Jane Doe"},{"name":"Wade Wilson"}]}';
    $user = $this->serializer->jsonToObject(__USER__, $json);
    $this->assertEquals(2, sizeof($user->getSubscribers()));
  }

  public function testConvertJsonToEntityWithDate(){
    $json = '{"birth":"2009-06-13"}';
    $user = $this->serializer->jsonToObject(__USER__, $json);
    $this->assertEquals("2009-06-13", $user->getBirth()->format("Y-m-d"));
  }

  public function testConvertJsonToEntitiesArray(){
    $json = '[{"name":"John Doe"},{"name":"Jane Doe"},{"name":"Wade Wilson"}]';
    $users = $this->serializer->jsonToObject(__USER__, $json);
    $this->assertEquals(3, sizeof($users));
    $this->assertEquals("John Doe", $users[0]->getName());
    $this->assertEquals("Jane Doe", $users[1]->getName());
    $this->assertEquals("Wade Wilson", $users[2]->getName());
  }
  
}

/*
###### TESTS ######

###### METHODS IMPROVEMENTS ######
Handle add... methods (there : addSubscriber with User object in argument)
See how to manage null values
###### EXCEPTIONS HANDLER ######

*/