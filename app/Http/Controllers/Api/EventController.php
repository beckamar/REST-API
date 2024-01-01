<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;   
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * // Devuelve una colección de recursos de eventos, 
     * transformando todos los eventos disponibles en el 
     * formato definido por la clase EventResource.
     */
    public function index()
    {
                                               //Se cargarán todos los eventos junto con sus propias relaciones de usuarios 
        return EventResource::collection(Event::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
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

        return new EventResource($event);

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
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
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}


/**
 * When building an API, you may need a transformation layer that sits between your 
 * Eloquent models and the JSON responses that are actually returned to your application's users. 
 * For example, you may wish to display certain attributes for a subset of users and not others, 
 * or you may wish to always include certain relationships in the JSON representation of your models. 
 * Eloquent's resource classes allow you to expressively and easily transform your models and model collections into JSON.
 */
