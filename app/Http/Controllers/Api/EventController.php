<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;   
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use App\Models\Event;

/**
 * Controlador para gestionar operaciones relacionadas con eventos en el contexto de la API.
 *
 * Este controlador proporciona métodos para realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * en eventos, incluyendo la creación, visualización, actualización y eliminación de eventos.
 * Además, ofrece la capacidad de listar eventos con la opción de incluir la relación 'user'.
 *
 * @package App\Http\Controllers\Api
 */



class EventController extends Controller
{
    use CanLoadRelationships;
    
    /**
     * Define las relaciones que pueden ser incluidas en la respuesta.
     *
     * @var array
     */
    private array $relations = ['user', 'attendees', 'attendees.user'];

/**
 * Muestra una lista de eventos con relaciones opcionales según los parámetros de consulta 'include'.
 *
 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
 */
    public function index()
    {
        // Inicializa una consulta para la entidad Event
        $query = $this->loadRelationships(Event::query());
        // Se cargarán todos los eventos junto con las relaciones especificadas
        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

/**
 * Almacena un nuevo evento en la base de datos.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function store(Request $request)
    {
         // Crear un nuevo evento utilizando los datos validados y asignar manualmente el user_id
        $event = Event::create([
            ...$request -> validate([
                 // Validar los datos de la solicitud utilizando reglas de validación
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($this->loadRelationships($event));

    }

    /** 
    * Muestra los detalles de un evento específico, cargando relaciones según las definidas en $relations.
    *
    * @param  \App\Models\Event  $event
    * @return \App\Http\Resources\Api\EventResource
    */
    public function show(Event $event)
    {
     /**Cargar relaciones según las definidas en $relations de la clase y devuelve  
      * una respuesta utilizando el recurso EventResource
      */
        return new EventResource($this->loadRelationships($event));
    }

/**
 * Actualiza los detalles de un evento existente en la base de datos.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Event  $event
 * @return \App\Http\Resources\Api\EventResource
 */
    public function update(Request $request, Event $event)
    {
         // Actualizar el evento con los datos validados y devolver el resultado de la actualización
        $event->update(
             // Validar los datos de la solicitud utilizando reglas de validación
            $request->validate([
           'name' => 'sometimes|string|max:255',
           'description' => 'nullable|string',
           'start_time' => 'sometimes|date',
           'end_time' => 'sometimes|date|after:start_time'
           ])
        );
        return new EventResource($this->loadRelationships($event));
    }

/**
 * Elimina un evento específico de la base de datos.
 *
 * @param  \App\Models\Event  $event
 * @return \Illuminate\Http\Response
 */
    public function destroy(Event $event)
    {
        // Elimina el evento de la base de datos
        $event->delete();
        // Devuelve una respuesta con el código de estado 204 (Sin contenido)
        return response(status: 204);
    }
}

