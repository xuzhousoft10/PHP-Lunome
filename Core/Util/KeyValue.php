<?php
namespace X\Core\Util;
class KeyValue {
    private $contents = array();
    public function has( $name ) {
        return isset($this->contents[$name]);
    }
    public function get( $name, $default=null ) {
        return $this->has($name) ? $this->contents[$name] : $default;
    }
    public function set( $name, $value ) {
        $this->contents[$name] = $value;
    }
    public function getValues() {
        return $this->contents;
    }
    public function setValues( $values ) {
        $this->contents = $values;
    }
    public function delete( $name ) {
        unset($this->contents[$name]);
    }
}