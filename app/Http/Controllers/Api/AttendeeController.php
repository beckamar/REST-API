<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Http\Resources\AttendeeResource;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;


/**
 * Controlador para gestionar operaciones relacionadas con los asistentes (attendees)
 * en el contexto de eventos.
 *
 * Este controlador proporciona métodos para listar, crear, mostrar, actualizar y eliminar
 * asistentes asociados a eventos. Además, incluye operaciones para mostrar la lista
 * de asistentes para un evento específico.
 *
 * @package App\Http\Controllers\Api
 */

 
class AttendeeController extends Controller
{
/**
 * Muestra la lista de attendees para un evento específico.
 *
 * @param  \App\Models\Event  $event
 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
 */
    public function index(Event $event)
    {
         // Obtener la lista de attendees para el evento, ordenados por la fecha más reciente
        $attendees = $event->attendees()->latest();
        return AttendeeResource::collection(
              // Devolver una colección de recursos AttendeeResource paginada
            $attendees->paginate()
        );
    }

/**
 * Almacena un nuevo attendee para un evento específico.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Event  $event
 * @return \App\Http\Resources\Api\AttendeeResource
 */
    public function store(Request $request, Event $event)
    {
        // Crea un nuevo asistente para el evento con el ID de usuario proporcionado
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);
        //Devuelve la respuesta utilizando el recurso AttendeeResource  
        return new AttendeeResource($attendee);
    }

/**
 * Muestra los detalles de un attendee específico para un evento.
 *
 * @param  \App\Models\Event  $event
 * @param  \App\Models\Attendee  $attendee
 * @return \App\Http\Resources\Api\AttendeeResource
 */
    public function show(Event $event, Attendee $attendee)
    {
        // Devuelve una respuesta utilizando el recurso AttendeeResource
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

/**
 * Elimina un attendee específico asociado a un evento.
 *
 * @param  \App\Models\Event  $event
 * @param  \App\Models\Attendee  $attendee
 * @return \Illuminate\Http\Response
 */
    public function destroy(Event $event, Attendee $attendee)
    {
        // Elimina el asistente de la base de datos
        $attendee->delete();
         // Devuelve una respuesta con el código de estado 204 (Sin contenido)
        return response(status: 204);
    }
}
