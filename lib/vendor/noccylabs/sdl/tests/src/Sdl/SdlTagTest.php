<?php

namespace Sdl;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-02-02 at 02:04:09.
 */
class SdlTagTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SdlTagNew
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SdlTag;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Sdl\SdlTag::setTagName
     * @todo   Implement testSetTagName().
     */
    public function testSetTagName()
    {
        // Remove the following lines when you implement this test.
        $this->object->setTagName("foo:bar");
        $this->assertEquals($this->object->getTagName(),"foo:bar");
    }

    /**
     * @covers Sdl\SdlTag::hasAttribute
     * @todo   Implement testHasAttribute().
     */
    public function testHasAttribute()
    {
        $this->assertFalse($this->object->hasAttribute("foo"));
        $this->object->setAttribute("foo","bar");
        $this->assertTrue($this->object->hasAttribute("foo"));
    }

    /**
     * @covers Sdl\SdlTag::getAttribute
     * @todo   Implement testGetAttribute().
     */
    public function testGetAttribute()
    {
        $this->assertEquals($this->object->getAttribute("foo"),null);
    }

    /**
     * @covers Sdl\SdlTag::setAttribute
     * @todo   Implement testSetAttribute().
     */
    public function testSetAttribute()
    {
        $nod = new SdlTag("tag1");

        $nod->setAttribute("foo","baz");
        $this->assertEquals("baz", $nod->getAttribute("foo"));
    }

    /**
     * @covers Sdl\SdlTag::setValue
     * @todo   Implement testSetValue().
     */
    public function testSetValue()
    {
        $nod = new SdlTag("tag1");

        $nod->setValue("Hello");
        $this->assertEquals("Hello", $nod->getValue());
        $this->assertEquals("Hello", $nod->getValue(0));
    }

    /**
     * @covers Sdl\SdlTag::setValuesFromArray
     * @todo   Implement testSetValuesFromArray().
     */
    public function testSetValuesFromArray()
    {
        $nod = new SdlTag("tag1");

        $arr = array("Hello", "World");
        $nod->setValuesFromArray($arr);
        $this->assertEquals("Hello", $nod->getValue());
        $this->assertEquals("Hello", $nod->getValue(0));
        $this->assertEquals("World", $nod->getValue(1));
    }

    /**
     * @covers Sdl\SdlTag::hasChildren
     * @todo   Implement testHasChildren().
     */
    public function testHasChildren()
    {
        $nod = new SdlTag("tag1");
        $child = new SdlTag("tag2");
        $nod->addChild($child);
        
        $this->assertTrue($nod->hasChildren());
    }

    /**
     * @covers Sdl\SdlTag::getChildren
     * @todo   Implement testGetChildren().
     */
    public function testGetChildren()
    {
        $nod = new SdlTag("tag1");
        $child = new SdlTag("tag2");
        $nod->addChild($child);
        
        $this->assertEquals(array($child), $nod->getChildren());
    }

    /**
     * @covers Sdl\SdlTag::addChild
     * @todo   Implement testAddChild().
     */
    public function testAddChild()
    {
        $nod = new SdlTag("tag1");
        $child = new SdlTag("tag2");
        $nod->addChild($child);

        $this->assertEquals($child->getParent(), $nod);
    }

    /**
     * @covers Sdl\SdlTag::createChild
     * @todo   Implement testCreateChild().
     */
    public function testCreateChild()
    {
        $nod = new SdlTag("tag1");
        $child = $nod->createChild("tag2");

        $this->assertNotNull($child);
        $this->assertNotEquals($child, $nod);
    }

    /**
     * @covers Sdl\SdlTag::end
     * @todo   Implement testEnd().
     */
    public function testEnd()
    {
        $nod = new SdlTag("tag1");
        $child = $nod->createChild("tag2");

        $this->assertEquals($child->end(), $nod);
    }

    /**
     * @covers Sdl\SdlTag::getParent
     * @todo   Implement testGetParent().
     */
    public function testGetParent()
    {
        $nod = new SdlTag("tag1");

        $this->assertEquals($nod->getParent(), null);
    }

    /**
     * @covers Sdl\SdlTag::setParent
     * @todo   Implement testSetParent().
     */
    public function testSetParent()
    {
        $nod = new SdlTag("tag1");
        $pnod = new SdlTag("tag2");

        $this->assertEquals($nod->getParent(), null);
        $nod->setParent($pnod);
        $this->assertEquals($nod->getParent(), $pnod);
    }

    public function testCreateComment()
    {
        SdlComment::setCommentStyle(SdlComment::STYLE_HASH);
        $out = $this->object->createComment("Hello World")->encode();
        $this->assertEquals("# Hello World\n", $out);
    }
    
    /**
     * @covers Sdl\SdlTag::encode
     * @todo   Implement testEncode().
     */
    public function testEncode()
    {
        $out = $this->object
            ->createChild("foo")
                ->createChild("bar")
                    ->setValuesFromArray([1,2,3])
                    ->end()
                ->createChild("baz")
                    ->setValuesFromArray(["do","re","mi"])
                    ->end()
                ->end()
            ->encode();
        $expect = "foo {\n    bar 1 2 3\n    baz \"do\" \"re\" \"mi\"\n}\n";
        $this->assertEquals($expect,$out);
    }

}