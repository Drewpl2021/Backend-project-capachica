<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use App\Http\Controllers\API\home\SectionDetailController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionDetailControllerTest extends TestCase
{
    use WithFaker;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_all_section_details()
    {
        $mock = Mockery::mock('alias:App\Models\SectionDetail');
        $mock->shouldReceive('all')->once()->andReturn([
            (object)['id' => 'uuid-1', 'title' => 'Fake Title']
        ]);

        $controller = new SectionDetailController();
        $response = $controller->index();

        $this->assertEquals(200, $response->status());
    }

    public function test_show_returns_section_detail()
    {
        $mockedModel = (object)[
            'id' => 'uuid-1',
            'code' => 'SD001',
            'title' => 'Title'
        ];

        $mock = Mockery::mock('alias:App\Models\SectionDetail');
        $mock->shouldReceive('find')->once()->with('uuid-1')->andReturn($mockedModel);

        $controller = new SectionDetailController();
        $response = $controller->show('uuid-1');

        $this->assertEquals(200, $response->status());
    }

    public function test_show_returns_not_found()
    {
        $mock = Mockery::mock('alias:App\Models\SectionDetail');
        $mock->shouldReceive('find')->once()->with('uuid-1')->andReturn(null);

        $controller = new SectionDetailController();
        $response = $controller->show('uuid-1');

        $this->assertEquals(404, $response->status());
    }

    public function test_destroy_deletes_section_detail()
    {
        $model = Mockery::mock();
        $model->shouldReceive('delete')->once()->andReturn(true);

        $mock = Mockery::mock('alias:App\Models\SectionDetail');
        $mock->shouldReceive('find')->once()->with('uuid-1')->andReturn($model);

        $controller = new SectionDetailController();
        $response = $controller->destroy('uuid-1');

        $this->assertEquals(200, $response->status());
    }

    public function test_destroy_returns_not_found()
    {
        $mock = Mockery::mock('alias:App\Models\SectionDetail');
        $mock->shouldReceive('find')->once()->with('uuid-1')->andReturn(null);

        $controller = new SectionDetailController();
        $response = $controller->destroy('uuid-1');

        $this->assertEquals(404, $response->status());
    }

    public function test_update_section_detail()
    {
        $model = Mockery::mock();
        $model->shouldReceive('save')->once()->andReturn(true);
        $model->shouldReceive('setAttribute')->withAnyArgs();

        $mock = Mockery::mock('alias:App\Models\SectionDetail');
        $mock->shouldReceive('find')->once()->with('uuid-1')->andReturn($model);

        $request = Request::create('/fake-url', 'PUT', [
            'status' => true,
            'code' => 'UPDATED',
            'title' => 'Updated Title',
        ]);

        $controller = new SectionDetailController();
        $response = $controller->update($request, 'uuid-1');

        $this->assertEquals(200, $response->status());
    }
}
