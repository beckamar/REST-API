<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::all();
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
        return $event;

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return $event;
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
        return $event;
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
