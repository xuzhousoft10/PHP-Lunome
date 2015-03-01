<?php
namespace X\Service\XDatabase\Test\Table;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
/**
 * 
 */
class ColumnTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_column() {
        $column = Column::setup('test');
        $this->assertSame('test', $column->getName());
        
        $column->setType(ColumnType::T_VARCHAR);
        $this->assertSame(ColumnType::T_VARCHAR, $column->getType());
        
        $column->setLength('10');
        $this->assertSame(10, $column->getLength());
        
        $column->setNullable(false);
        $this->assertFalse($column->getNullable());
        
        $column->setDefault('default-value');
        $this->assertSame('default-value', $column->getDefault());
        
        $column->setIsAutoIncrement(true);
        $this->assertTrue($column->getIsAutoIncrement());
        
        $column->setIsZeroFill(true);
        $this->assertTrue($column->getIsZeroFill());
        
        $column->setIsUnsigned(true);
        $this->assertTrue($column->getIsUnsigned());
        
        $column->setIsBinary(true);
        $this->assertTrue($column->getIsBinary());
        
        $column->setIsPrimaryKey(true);
        $this->assertTrue($column->getIsPrimaryKey());
        
        $columnString = "VARCHAR(10) ZEROFILL UNSIGNED BINARY AUTO_INCREMENT NOT NULL DEFAULT 'default-value'";
        $this->assertSame($columnString, $column->toString());
    }
}