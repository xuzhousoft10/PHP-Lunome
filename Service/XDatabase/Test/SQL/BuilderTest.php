<?php
namespace X\Service\XDatabase\Test\SQL;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Builder;
/**
 * 
 */
class BuilderTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_builder() {
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Describe', Builder::build()->describe());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Select', Builder::build()->select());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Insert', Builder::build()->insert());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Update', Builder::build()->update());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Delete', Builder::build()->delete());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\CreateTable', Builder::build()->createTable());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\DropTable', Builder::build()->dropTable());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Truncate', Builder::build()->truncate());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\Rename', Builder::build()->rename());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\SQL\\Action\\AlterTable', Builder::build()->alterTable());
    }
}