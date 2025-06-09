<?php

namespace Tests\Unit;

use App\Http\Controllers\API\Modules\ModuleController;
use App\Models\Module;
use Illuminate\Testing\TestResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
    public function test_index_returns_paginated_modules_as_json()
    {
        // 🔧 Mock de módulo padre
        $parentModuleMock = (object) [
            'id' => 'parent-uuid',
            'title' => 'Parent Title',
            'code' => 'PM001',
            'subtitle' => 'Parent Subtitle',
        ];

        // 🔧 Mock del módulo
        $moduleMock = (object) [
            'id' => 'module-uuid',
            'title' => 'Module Title',
            'subtitle' => 'Module Subtitle',
            'type' => 'Type1',
            'code' => 'MOD001',
            'icon' => 'icon-mod',
            'status' => true,
            'moduleOrder' => 1,
            'link' => '/test',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
            'parentModule' => $parentModuleMock
        ];

        // 📄 Crear paginador falso
        $paginator = new LengthAwarePaginator(
            items: [$moduleMock],
            total: 1,
            perPage: 10,
            currentPage: 1
        );

        // 🧪 Mockear la clase Module::with(...) usando facades
        $moduleMockBuilder = Mockery::mock('overload:' . Module::class);

        $moduleMockBuilder
            ->shouldReceive('with')
            ->with('parentModule')
            ->andReturnSelf();

        $moduleMockBuilder
            ->shouldReceive('paginate')
            ->with(10)
            ->andReturn($paginator);

        // 🚀 Ejecutar el controlador
        $controller = new ModuleController();
        $request = Request::create('/api/module', 'GET', ['size' => 10]);
        $rawResponse = $controller->index($request);

        // ✅ Envolver para assertions
        $response = TestResponse::fromBaseResponse($rawResponse);

        // ✅ Verificación
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'content' => [[
                'id',
                'title',
                'subtitle',
                'type',
                'code',
                'icon',
                'status',
                'moduleOrder',
                'link',
                'createdAt',
                'updatedAt',
                'deletedAt',
                'parentModule'
            ]],
            'totalElements',
            'currentPage',
            'totalPages'
        ]);
    }
    public function test_show_returns_module()
    {
        $module = (object)[
            'id' => 'uuid-1',
            'title' => 'Test',
            'subtitle' => 'Sub',
            'type' => 'Type',
            'code' => 'X1',
            'icon' => 'icon',
            'status' => true,
            'moduleOrder' => 1,
            'link' => '/route',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
            'parentModule' => (object)[
                'id' => 'uuid-parent',
                'title' => 'Parent',
                'code' => 'PM01',
                'subtitle' => 'Parent Sub'
            ]
        ];

        $mock = Mockery::mock('alias:App\Models\Module');
        $mock->shouldReceive('with')->with('parentModule')->andReturnSelf();
        $mock->shouldReceive('findOrFail')->with('uuid-1')->andReturn($module);

        $controller = new ModuleController();
        $response = $controller->show('uuid-1');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('title', $response->getData(true));
    }

    public function test_update_modifies_module()
    {
        // ✅ 1. Crear un Request REAL
        $request = \Illuminate\Http\Request::create('/api/module/uuid-1', 'PUT', [
            'title' => 'Updated Title',
            'subtitle' => 'Updated Subtitle',
            'type' => 'Updated Type',
            'code' => 'MODX',
            'icon' => 'icon-x',
            'status' => true,
            'moduleOrder' => 5,
            'link' => '/link-x',
            'parentModuleId' => 'uuid-parent'
        ]);

        // ✅ 2. Mockear el Request de forma parcial
        $request = Mockery::mock($request)->makePartial();
        $request->shouldReceive('input')->with('parentModuleId')->andReturn('uuid-parent');
        $request->shouldReceive('merge')->once();
        $request->shouldReceive('validate')->once()->andReturn([
            'title' => 'Updated Title',
            'subtitle' => 'Updated Subtitle',
            'type' => 'Updated Type',
            'code' => 'MODX',
            'icon' => 'icon-x',
            'status' => true,
            'moduleOrder' => 5,
            'link' => '/link-x',
            'parent_module_id' => 'uuid-parent'
        ]);

        // ✅ 3. Mock del modelo
        $module = Mockery::mock();
        $module->shouldReceive('update')->once()->andReturnTrue();

        $moduleModel = Mockery::mock('alias:App\Models\Module');
        $moduleModel->shouldReceive('findOrFail')->once()->with('uuid-1')->andReturn($module);

        // ✅ 4. Ejecutar controlador
        $controller = new \App\Http\Controllers\API\Modules\ModuleController();
        $response = $controller->update($request, 'uuid-1');

        // ✅ 5. Verificar
        $this->assertEquals(200, $response->getStatusCode());
    }





    public function test_destroy_deletes_module()
    {
        $mockModule = Mockery::mock();
        $mockModule->shouldReceive('delete')->once();

        $moduleModel = Mockery::mock('alias:App\Models\Module');
        $moduleModel->shouldReceive('findOrFail')->with('uuid-1')->andReturn($mockModule);
        $moduleModel->shouldReceive('paginate')->andReturn([]);

        $controller = new ModuleController();
        $response = $controller->destroy('uuid-1');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
