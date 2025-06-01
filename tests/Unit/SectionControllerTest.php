<?php

namespace Tests\Unit;

use App\Http\Controllers\API\home\SectionController;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class SectionControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_paginated_sections()
    {
        $sectionMock = new \stdClass();
        $sectionMock->id = 'uuid-section';
        $sectionMock->code = 'SEC01';
        $sectionMock->name = 'Sección A';
        $sectionMock->status = true;

        $paginator = new LengthAwarePaginator([$sectionMock], 1, 10, 1);
        $paginator->setPageName('page');

        $sectionMockBuilder = Mockery::mock('alias:' . Section::class);
        $sectionMockBuilder->shouldReceive('paginate')->with(10)->andReturn($paginator);

        $controller = new SectionController();
        $request = Request::create('/section', 'GET', ['size' => 10]);
        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content', $response->getData(true));
    }

    public function test_show_returns_section()
    {
        $mock = Mockery::mock('alias:' . Section::class);
        $mock->shouldReceive('find')->with('uuid-section')->once()->andReturn((object)[
            'id' => 'uuid-section',
            'code' => 'SEC01',
            'name' => 'Sección A',
            'status' => true
        ]);

        $controller = new SectionController();
        $response = $controller->show('uuid-section');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('uuid-section', $response->getData()->id);
    }

    public function test_update_modifies_section()
    {
        $sectionMock = Mockery::mock('alias:' . Section::class);
        $sectionMock->shouldReceive('find')->with('uuid-section')->andReturn($sectionMock);
        $sectionMock->shouldReceive('update')->once()->with([
            'code' => 'SEC01',
            'name' => 'Actualizada',
            'status' => true
        ])->andReturn(true);

        $controller = new SectionController();

        $request = Request::create('/section/uuid-section', 'PUT', [
            'code' => 'SEC01',
            'name' => 'Actualizada',
            'status' => true,
        ]);

        $response = $controller->update($request, 'uuid-section');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_destroy_deletes_section()
    {
        $sectionMock = Mockery::mock('alias:' . Section::class);
        $sectionMock->shouldReceive('find')->with('uuid-section')->andReturn($sectionMock);
        $sectionMock->shouldReceive('delete')->once()->andReturn(true);

        $controller = new SectionController();
        $response = $controller->destroy('uuid-section');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('deleted successfully', $response->getContent());
    }
}
