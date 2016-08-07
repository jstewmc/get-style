<?php
/**
 * The file for the get-style tests
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\GetStyle;

use Jstewmc\TestCase\TestCase;

/**
 * Tests for the get-style test
 */
class GetTest extends TestCase
{
    /* !__construct() */
    
    /**
     * __construct() should set the properties
     */
    public function testConstruct()
    {
        $styles = ['foo' => ['bar' => 'baz']];
        
        $get = new Get($styles);
        
        $this->assertEquals($styles, $this->getProperty('styles', $get));
        
        return;
    }
    
    
    /* !__invoke() */
    
    /**
     * __invoke() should return string if styles do not exist
     */
    public function testInvokeReturnsStringIfStylesDoNotExist()
    {
        return $this->assertEquals('', (new Get([]))());
    }
    
    /**
     * __invoke() should return string if global styles do exist
     */
    public function testInvokeReturnsStringIfGlobalStyleExists()
    {
        $styles = ['*' => ['foo' => 'bar']];
        
        $expected = 'foo: bar;';
        $actual   = (new Get($styles))();
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
    
    /**
     * __invoke() should return string if named styles do not exist
     */
    public function testInvokeThrowsExceptionIfNamedStyleDoesNotExist()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Get([]))('foo');
        
        return;
    }
    
    /**
     * __invoke() should return string if named styles do exist
     */
    public function testInvokeReturnsStringIfNamedStyleDoesExist()
    {
        $styles = [
            '*' => [
                'foo' => 'bar'
            ],
            'foo' => [
                'bar' => 'baz'
            ]
        ];
        
        $expected = 'foo: bar; bar: baz;';
        $actual   = (new Get($styles))('foo');
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
    
    /**
     * __invoke() should return string if custom declarations do exist
     */
    public function testInvokeReturnsStringIfCustomDeclarationsDoExist()
    {
        $styles = [
            '*' => [
                'foo' => 'bar'
            ],
            'foo' => [
                'bar' => 'baz'
            ]
        ];
        
        $expected = 'foo: qux; bar: baz;';
        $actual   = (new Get($styles))('foo', ['foo' => 'qux']);
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
}
