<?php
class Person {
    private $name;
    private $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        if ($age > 0) {
            $this->age = $age;
        }
    }

    public function introduce() {
        echo "My name is " . $this->name . " and I am " . $this->age . " years old.";
    }
}
?>