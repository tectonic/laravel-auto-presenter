<?php namespace McCool\Tests;

use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;
use McCool\Tests\Stubs\DecoratedAtomFieldsPresenter;
use Mockery as m;

class BasePresenterTest extends TestCase
{
	private $decoratedAtom;

	public function setUp()
	{
		$this->decoratedAtom = new DecoratedAtom;
	}

	public function testResourceIsReturned()
	{
		$presenter = new DecoratedAtomPresenter($this->decoratedAtom);
		$this->assertEquals($this->decoratedAtom, $presenter->getResource());
	}

	public function testFieldsAreReturned()
	{
		$presenter = new DecoratedAtomFieldsPresenter($this->decoratedAtom);
		$this->assertEquals(['name', 'address'], $presenter->getExposedFields());
	}

	public function testFieldsAreAccessible()
	{
		$presenter = new DecoratedAtomFieldsPresenter($this->decoratedAtom);

		$this->assertTrue($presenter->fieldIsExposed('name'));
		$this->assertFalse($presenter->fieldIsExposed('somekeythatdoesntexist'));
	}

	public function testArrayConversionShouldRespectFieldLimitations()
	{
		$presenter = new DecoratedAtomFieldsPresenter($this->decoratedAtom);
		$presentedArray = $presenter->toArray();

		$this->assertArrayHasKey('name', $presentedArray);
		$this->assertArrayHasKey('address', $presentedArray);
		$this->assertArrayNotHasKey('random', $presentedArray);
	}

	public function testFieldsAreAccessibleWithEmptyFieldsArray()
	{
		$presenter = new DecoratedAtomPresenter($this->decoratedAtom);
		$this->assertTrue($presenter->fieldIsExposed('name'));
	}

    public function testResourceAttributeDeference()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals(DecoratedAtomPresenter::class, $presenter->getPresenterClass());
    }

    public function testPresenterMethodDeference()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals('Primer', $presenter->favorite_movie);
    }

    /**
     * @covers presenter::thisMethodDoesntExist
     * @expectedException \McCool\LaravelAutoPresenter\ResourceMethodNotFound
     */
    public function testResourceMethodNotFoundThrowsException()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $presenter->thisMethodDoesntExist();
    }
}
