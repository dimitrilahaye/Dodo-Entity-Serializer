<?php

namespace DodoPhpLab\DodoEntitySerializer\Classes;

/**
* General class for DodoEntitySerializer. Provides all the methods to :
* <ul>
*	<li> Convert Entity Object or Array of Entities Objects to JSON </li>
*	<li> Convert JSON to Entity Object or array of Entities Objects JSON </li>
* </ul>
*
* @author Dimitri Lahaye <contact@dimitrilahaye.net>
* @license http://www.dbad-license.org/ DBAD Public License
* @version 0.1.1-alpha
*
* @package DodoPhpLab
* @subpackage DodoEntitySerializer\Classes
*/
class EntitySerializer {

    private static $instance;
    
    private function __construct(){}

    public static function getInstance(){
        static::$instance = static::$instance == null ? new EntitySerializer() : static::$instance;
        return static::$instance;
    }

    /**
    * Returns an object from a json and the class's namespace of the object we want to return.
    *
    * @param string $classNamespace the class name with namespace.
    * @param string $json the json to transform in object.
    *
    * @return mixed[] array of Entity-type objects or one Entity-type object.
    */
    public function jsonToObject($classNamespace, $json){
        if(is_array(json_decode($json))){
            return $this->deserializeJsonArray($classNamespace, $json);
        }
        return $this->deserializeJson($classNamespace, $json);
    }
    
    /**
    * Returns a string formatted json from an object and his class's namespace.
    *
    * @param string $classNamespace the class name with namespace.
    * @param mixed[] $object the object to transform into json.
    *
    * @return string the string formatted json representation of the $object passed in argument.
    */
    public function objectToJson($classNamespace, $object){
        if(is_array($object)){
            return $this->serializeObjectsArray($classNamespace, $object);
        }
        return $this->serializeObject($classNamespace, $object);
    }

    #############################################################################
    ################ PRIVATE API ################################################
    #############################################################################

    /**
    * @internal
    *
    * Returns a string formatted json from an object and his class's namespace.
    *
    * @param string $classNamespace the class name with namespace.
    * @param mixed[] $object the object to transform into json.
    *
    * @return mixed[] a string-formatted json array from an object and his class's namespace.
    */
    private function serializeObject($classNamespace, $object){
        if(class_exists($classNamespace)) {
            $rClass = new \ReflectionClass($classNamespace);
            $rProperties = $rClass->getProperties();
            $objects = [];
            foreach ($rProperties as $rProperty) {
                $rProperty->setAccessible(true);
                $propertyName = $rProperty->getName();
                //if value is DateTime
                if(is_object($rProperty->getValue($object)) && get_class($rProperty->getValue($object)) == "DateTime"){
                	$objects += $this->handleDateTimeIntoSerializeObject($propertyName, $rProperty->getValue($object));
                } else {
                	$propertyValue = $this->getPropertyValueFromObject($object, $rProperty);
	                if($propertyValue != null){
	                    $objects += array($propertyName => $propertyValue);
	                }
                }
            }
            return json_encode($objects);
        }
        throw new \ErrorException("The class ".$classNamespace." doesn't exist");
    }

    /**
    * @internal
    *
    * Get an object and property to return the value of this property from this object.
    *
    * @param mixed[] $object Object to evaluate.
    * @param \ReflectionProperty $classProperty property from $object.
    *
    * @return string value to put in the Objects array we want to return.
    */
    private function getPropertyValueFromObject($object, $classProperty){
        $propertyValue = null;
        //value is object
        if(is_object($classProperty->getValue($object))) {
    		$subClass = new \ReflectionClass($classProperty->getValue($object));
            $propertyValue = json_decode($this->serializeObject($subClass->getName(), $classProperty->getValue($object)));
        }
        //value is array
        else if(is_array($classProperty->getValue($object))) {
            $subClass = new \ReflectionClass($classProperty->getValue($object)[0]);
            $propertyValue = json_decode($this->serializeObjectsArray($subClass->getName(), $classProperty->getValue($object)));
        }
        //value is primitive
        else {
            $propertyValue = $classProperty->getValue($object);
        }
        return $propertyValue;
    }
    
    /**
    * @internal
    *
    * Returns a json array constructed with the class's namespace and an array of objects.
    *
    * @param string $classNamespace the class name with namespace.
    * @param mixed[] $objects the array of objects we want to transform into json array.
    *
    * @return string-formatted json array from $classNamespace and an array of objects.
    */
    private function serializeObjectsArray($classNamespace, $objects){
        $jsonArray = [];
        foreach($objects as $object) {
            $json = $this->serializeObject($classNamespace, $object);
            $jsonArray[] = json_decode($json);
        }
        return json_encode($jsonArray);
    }

    /**
    * @internal
    *
    * Returns an object constructed with the class's namespace and a string-formatted json object.
    *
    * @param string $classNamespace the class name with namespace.
    * @param string $json the json to transform in object
    * @throws \ErrorException
    *
    * @return mixed[] an Entity-like object from $classNamespace and $json
    */
    private function deserializeJson($classNamespace, $json){
        $object = null;
        if(class_exists($classNamespace)) {
            $object = new $classNamespace();
            $json = json_decode($json);
            foreach($json as $key => $value){
                $method = 'set'.ucfirst($key);
                if(method_exists($object, $method)) {
            		$rClass = new \ReflectionClass($classNamespace);
                	$rMethod = $rClass->getMethod($method);
                	$rMethod->setAccessible(true);
                	$rParams = $rMethod->getParameters();
                	if($rParams[0]->getClass() != null){
                		$value = $this->handleDateTimeIntoDeserializeJson($rParams[0], $value);
	                }
                    $object->$method($value);
                } else {
                    throw new \ErrorException("Method ".$method." doesn't exist !");
                }
            }
        } else {
            throw new \ErrorException("The class ".$classNamespace." doesn't exist");
        }
        return $object;
    }

    /**
    * @internal
    *
    * Returns an array of objects constructed with the class's namespace and a json array.
    *
    * @param string $classNamespace the class name with namespace.
    * @param string $jsonArray the string-formatted json array we want to transform in objects array.
    * @throws \ErrorException
    *
    * @return mixed[] an array of object from $classNamespace and a $jsonArray.
    */
    private function deserializeJsonArray($classNamespace, $jsonArray){
        $objects = [];
        foreach(json_decode($jsonArray) as $json) {
            $object = $this->deserializeJson($classNamespace, json_encode($json));
            $objects[] = $object;
        }
        return $objects;
    }

    /**
    * @internal
    *
    * If param passed in argument ask for \DateTime value, then returns the value from \DateTime instance.
    *
    * @param \ReflectionParameter $reflectionParam the reflection parameter from a \ReflectionClass instance.
    * @param string $value the value we want to evaluate
    *
    * @return \DateTime a \DateTime instance of the value passed in argument.
    */
    private function handleDateTimeIntoDeserializeJson($reflectionParam, $value){
    	if($reflectionParam->getClass()->getName() == "DateTime"){
    		$value = new \DateTime($value);
    	}
    	return $value;
    }

    /**
    * @internal
    *
    * If param passed in argument ask for \DateTime value, then returns the value from \DateTime instance.
    *
    * @param string $propertyName the name of the property for what we want a \DateTime instance of the $rValue.
    * @param string $rValue the value we want to evaluate
    *
    * @return mixed[] an array in format array("property name" => \DateTime value).
    */
    private function handleDateTimeIntoSerializeObject($propertyName, $rValue){
    	$date = $rValue->format("Y-m-d");
    	return array($propertyName => $date);
    }

}