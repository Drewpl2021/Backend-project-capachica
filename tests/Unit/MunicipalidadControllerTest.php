<?php

namespace Tests\Unit;

use App\Models\Municipalidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Str;

class MunicipalidadControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method without hitting the real database or routes.
     */

    public function testIndexReturnsPaginationData()
    {
        // Crear un mock parcial de Municipalidad para evitar errores con with()
        $municipalidadMock = Mockery::mock('overload:' . Municipalidad::class);

        // Crear objetos falsos para simular los resultados
        $fakeMunicipalidad = new \stdClass();
        $fakeMunicipalidad->id = 'uuid-prueba';
        $fakeMunicipalidad->distrito = 'Lima';
        $fakeMunicipalidad->provincia = 'Lima';
        $fakeMunicipalidad->region = 'Lima';
        $fakeMunicipalidad->codigo = '12345';
        $fakeMunicipalidad->created_at = now();
        $fakeMunicipalidad->updated_at = now();
        $fakeMunicipalidad->deleted_at = null;
        $fakeMunicipalidad->sliderMunis = collect(); // <- evita error en el mapeo

        $paginatorMock = Mockery::mock('Illuminate\Pagination\LengthAwarePaginator');
        $paginatorMock->shouldReceive('items')->andReturn([$fakeMunicipalidad]);
        $paginatorMock->shouldReceive('total')->andReturn(1);
        $paginatorMock->shouldReceive('currentPage')->andReturn(1);
        $paginatorMock->shouldReceive('lastPage')->andReturn(1);
        $paginatorMock->shouldReceive('perPage')->andReturn(10);

        // Mock del query builder
        $queryMock = Mockery::mock();
        $queryMock->shouldReceive('with')->with('sliderMunis')->andReturnSelf();
        $queryMock->shouldReceive('paginate')->with(10)->andReturn($paginatorMock);

        // Reemplazar el método query
        $municipalidadMock->shouldReceive('query')->andReturn($queryMock);

        // Mock del Request
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('size', 10)->andReturn(10);
        $request->shouldReceive('input')->with('name')->andReturn(null);

        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $response = $controller->index($request);
        $data = $response->getData(true);

        $this->assertEquals('Lima', $data['content'][0]['distrito']);
        $this->assertEquals(1, $data['totalElements']);
    }


    /**
     * Test the store method without hitting the real database or routes.
     */


    public function testStoreCreatesMunicipalidad()
    {
        // Crear un mock del modelo Municipalidad
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);
        $municipalidadMock->shouldReceive('create')->once()->andReturn((object)[
            'distrito' => 'Lima',
            'provincia' => 'Lima',
            'region' => 'Lima',
            'codigo' => '12345'
        ]);

        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('validate')->once()->andReturn([
            'distrito' => 'Lima',
            'provincia' => 'Lima',
            'region' => 'Lima',
            'codigo' => '12345'
        ]);

        // Llamar al método store con la solicitud simulada
        $response = $controller->store($request);

        // Convertir la respuesta JsonResponse en un array
        $responseData = $response->getData(true);  // Usamos true para obtener un array asociativo

        // Verificar que la respuesta contiene los datos correctos
        $this->assertEquals('Lima', $responseData['distrito']);
        $this->assertEquals('Lima', $responseData['provincia']);
        $this->assertEquals('Lima', $responseData['region']);
        $this->assertEquals('12345', $responseData['codigo']);
    }


    /**
     * Test the show method without hitting the real database or routes.
     */


    public function testShowReturnsMunicipalidad()
    {
        // Crear un UUID simulado
        $uuid = \Ramsey\Uuid\Guid\Guid::uuid4()->toString();

        // Crear un mock del modelo Municipalidad
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);
        $municipalidadMock->shouldReceive('find')->once()->with($uuid)->andReturn((object)[
            'id' => $uuid, // Usamos el UUID generado
            'distrito' => 'Lima',
            'provincia' => 'Lima',
            'region' => 'Lima',
            'codigo' => '12345',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null
        ]);

        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $response = $controller->show($uuid);

        // Convertir la respuesta JsonResponse en un array
        $responseData = $response->getData(true); // Convertir en un array asociativo

        // Verificar que la respuesta contiene los datos correctos
        $this->assertEquals($uuid, $responseData['id']); // Verificamos que el UUID es correcto
        $this->assertEquals('Lima', $responseData['distrito']);
    }


    public function testUpdateUpdatesMunicipalidad()
    {
        $uuid = (string) Str::uuid(); // Usamos UUID real como string

        // Crear mock estático del modelo
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);

        // Crear mock de instancia que representa un registro encontrado
        $modelMock = Mockery::mock();
        $modelMock->shouldReceive('update')->once()->with([
            'distrito' => 'Cusco',
            'provincia' => 'Cusco',
            'region' => 'Cusco',
            'codigo' => '67890'
        ])->andReturn(true);

        // Asignamos el UUID como ID
        $modelMock->id = $uuid;
        $modelMock->distrito = 'Cusco';
        $modelMock->provincia = 'Cusco';
        $modelMock->region = 'Cusco';
        $modelMock->codigo = '67890';

        // Simular find() para que devuelva este objeto completo
        $municipalidadMock->shouldReceive('find')->once()->with($uuid)->andReturn($modelMock);

        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('validate')->once()->andReturn([
            'distrito' => 'Cusco',
            'provincia' => 'Cusco',
            'region' => 'Cusco',
            'codigo' => '67890'
        ]);

        $response = $controller->update($request, $uuid);
        $responseData = $response->getData(true);

        // Verificaciones con UUID
        $this->assertEquals($uuid, $responseData['id']);
        $this->assertEquals('Cusco', $responseData['distrito']);
        $this->assertEquals('Cusco', $responseData['provincia']);
        $this->assertEquals('Cusco', $responseData['region']);
        $this->assertEquals('67890', $responseData['codigo']);
    }



    public function testDestroyDeletesMunicipalidad()
    {
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);

        // Crear un objeto con id y método delete simulados
        $modelMock = Mockery::mock();
        $modelMock->shouldReceive('delete')->once()->andReturn(true);
        $modelMock->id = 1;

        // Simular find
        $municipalidadMock->shouldReceive('find')->once()->with(1)->andReturn($modelMock);

        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $response = $controller->destroy(1);

        $data = $response->getData(true);
        $this->assertEquals(1, $data['id']);
    }
}
