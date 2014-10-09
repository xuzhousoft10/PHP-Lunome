<?php
namespace X\Module\Lunome\Util\Model;
use X\Util\Model\Basic;
abstract class Poster extends Basic {
    abstract public function getMediaKey();
}