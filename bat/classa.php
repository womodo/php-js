<?php
class ClassA {
    public function __construct() {        
    }

    public function getValue() {
        try {
            throw new Exception("エラー");
            return "aaa";
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(1);
        }
    }
}