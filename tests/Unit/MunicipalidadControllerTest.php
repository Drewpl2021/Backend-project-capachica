<?php

namespace Tests\Unit;

use App\Models\Municipalidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Ramsey\Uuid\Guid\Guid;

class MunicipalidadControllerTest extends TestCase
{

    /**
     * Test the index method without hitting the real database or routes.
     */

    /*
    public function testIndexReturnsPaginationData()
    {
        // Crear un UUID simulado
        $uuid = Guid::uuid4()->toString();

        // Crear un mock para el modelo Municipalidad
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);

        // Simular la consulta y la paginación
        $municipalidadMock->shouldReceive('query')->once()->andReturnSelf();
        $municipalidadMock->shouldReceive('paginate')->once()->with(10)->andReturnSelf();

        // Simular los métodos de paginación
        $municipalidadMock->shouldReceive('items')->once()->andReturn([
            (object)[
                'id' => $uuid, // Usamos el UUID generado
                'distrito' => 'Lima',
                'provincia' => 'Lima',
                'region' => 'Lima',
                'codigo' => '12345',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ]
        ]);

        $municipalidadMock->shouldReceive('total')->once()->andReturn(100);
        $municipalidadMock->shouldReceive('currentPage')->once()->andReturn(1);
        $municipalidadMock->shouldReceive('lastPage')->once()->andReturn(10);
        $municipalidadMock->shouldReceive('perPage')->once()->andReturn(10);

        // Crear un mock para el objeto Request
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('size', 10)->andReturn(10); // Simular el valor para 'size'
        $request->shouldReceive('input')->with('name')->andReturn(null); // Simular que 'name' no está presente

        // Crear el controlador y llamar al método index directamente
        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $response = $controller->index($request);

        // Convertir la respuesta JsonResponse en un array
        $responseData = $response->getData(true);

        // Verificar que la respuesta contiene la estructura esperada
        $this->assertIsArray($responseData['content']);
        $this->assertEquals(1, count($responseData['content']));
        $this->assertEquals('Lima', $responseData['content'][0]['distrito']);
        $this->assertEquals($uuid, $responseData['content'][0]['id']); // Verificar que el ID es el UUID esperado
        $this->assertEquals(100, $responseData['totalElements']); // Verificar que el total de elementos sea correcto
        $this->assertEquals(1, $responseData['currentPage']); // Verificar la página actual
        $this->assertEquals(10, $responseData['totalPages']); // Verificar el total de páginas
        $this->assertEquals(10, $responseData['perPage']); // Verificar los elementos por página
    }*/

    /**
     * Test the store method without hitting the real database or routes.
     */

    /*
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
    }*/


    /**
     * Test the show method without hitting the real database or routes.
     */

    /*
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
    }*/


    /*public function testUpdateUpdatesMunicipalidad()
    {
        // Crear un mock del modelo Municipalidad
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);

        // Simulamos que el método `find()` devuelve el mismo objeto `municipalidadMock`
        $municipalidadMock->shouldReceive('find')->once()->with(1)->andReturn($municipalidadMock);

        // Simulamos las propiedades del modelo Eloquent
        $municipalidadMock->id = 1;
        $municipalidadMock->distrito = 'Lima'; // Valor inicial
        $municipalidadMock->provincia = 'Lima';
        $municipalidadMock->region = 'Lima';
        $municipalidadMock->codigo = '12345';

        // Simulamos el método `update()` para que actualice el valor de 'distrito'
        $municipalidadMock->shouldReceive('update')->once()->with([
            'distrito' => 'Cusco',
            'provincia' => 'Cusco',
            'region' => 'Cusco',
            'codigo' => '67890'
        ])->andReturn(true);

        // Crear el controlador y la solicitud mockeada
        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('validate')->once()->andReturn([
            'distrito' => 'Cusco', // Simulamos que el valor de distrito es Cusco
            'provincia' => 'Cusco',
            'region' => 'Cusco',
            'codigo' => '67890'
        ]);

        // Llamar al método update
        $response = $controller->update($request, 1);

        // Convertir la respuesta JsonResponse en un array
        $responseData = $response->getData(true);

        // Verificar que la respuesta contiene los datos correctos después de la actualización
        $this->assertEquals('Cusco', $responseData['distrito']);  // Ahora debe ser 'Cusco'
        $this->assertEquals('Cusco', $responseData['provincia']);
        $this->assertEquals('Cusco', $responseData['region']);
        $this->assertEquals('67890', $responseData['codigo']);
    }


    public function testDestroyDeletesMunicipalidad()
    {
        // Crear un mock del modelo Municipalidad (modelo real, no stdClass)
        $municipalidadMock = Mockery::mock('alias:' . Municipalidad::class);

        // Simulamos que el método `find()` devuelve el mismo objeto `municipalidadMock`
        $municipalidadMock->shouldReceive('find')->once()->with(1)->andReturn($municipalidadMock);

        // Simulamos el método `delete()` para que devuelva true (simulando una eliminación exitosa)
        $municipalidadMock->shouldReceive('delete')->once()->andReturn(true);

        // Crear el controlador y la solicitud mockeada
        $controller = new \App\Http\Controllers\API\home\MunicipalidadController();
        $response = $controller->destroy(1);

        // Verificar que la respuesta contiene el ID esperado
        $this->assertEquals(1, $response->id);
    }*/
}
