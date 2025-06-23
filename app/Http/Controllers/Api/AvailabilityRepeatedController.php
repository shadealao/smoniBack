<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AvailabilityRepeated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityRepeatedController extends Controller
{
    /**
     * Affiche la liste des disponibilités récurrentes du moniteur connecté.
     * @group Disponibilités récurrentes
     * @response 200 {"success":true,"data":[{"id":1,"monitor_id":1,...}]}
     */
    public function index()
    {
        $user = Auth::user();
        $repeateds = array();
        $days = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
        foreach ($days as $day) {
            $repeated = AvailabilityRepeated::where('monitor_id', $user->id)->where('day_of_week', $day)->get();
            $info = [
                $day => $repeated
            ];
            array_push($repeateds,$info);
        }
        return response()->json(['success' => true, 'data' => $repeateds]);
    }

    /**
     * Crée une nouvelle disponibilité récurrente pour le moniteur connecté.
     * @group Disponibilités récurrentes
     * @bodyParam meeting_point_id int required ID du point de rendez-vous
     * @bodyParam vehicle_id int required ID du véhicule
     * @bodyParam day_of_week string required Jour de la semaine (lundi, mardi...)
     * @bodyParam time array required Tableau d'objets {start, end}
     * @bodyParam status boolean Statut (optionnel)
     * @response 201 {"success":true,"data":{"id":1,...},"message":"Disponibilité récurrente créée."}
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'meeting_point_id' => 'required|exists:meeting_points,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'day_of_week' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'time' => 'required|array',
            // 'time.*.start' => 'required|date_format:H:i',
            // 'time.*.end' => 'required|date_format:H:i|after:time.*.start',
            'time.*.start' => 'required',
            'time.*.end' => 'required',
            'status' => 'sometimes|boolean',
        ]);

        $repeated = AvailabilityRepeated::create([
            'monitor_id' => $user->id,
            'meeting_point_id' => $validated['meeting_point_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'day_of_week' => $validated['day_of_week'],
            'time' => $validated['time'],
            'status' => $validated['status'] ?? true,
        ]);
        return response()->json(['success' => true, 'data' => $repeated, 'message' => 'Disponibilité récurrente créée.']);
    }

    /**
     * Affiche une disponibilité récurrente spécifique.
     * @group Disponibilités récurrentes
     * @urlParam availabilityRepeated int required ID de la disponibilité récurrente
     * @response 200 {"success":true,"data":{"id":1,...}}
     */
    public function show(AvailabilityRepeated $availabilityRepeated)
    {
        $user = Auth::user();
        if ($availabilityRepeated->monitor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }
        return response()->json(['success' => true, 'data' => $availabilityRepeated]);
    }

    /**
     * Met à jour une disponibilité récurrente existante.
     * @group Disponibilités récurrentes
     * @urlParam availabilityRepeated int required ID de la disponibilité récurrente
     * @bodyParam meeting_point_id int ID du point de rendez-vous
     * @bodyParam vehicle_id int ID du véhicule
     * @bodyParam day_of_week string Jour de la semaine
     * @bodyParam time array Tableau d'objets {start, end}
     * @bodyParam status boolean Statut
     * @response 200 {"success":true,"data":{"id":1,...},"message":"Mise à jour réussie."}
     */
    public function update(Request $request, AvailabilityRepeated $availabilityRepeated)
    {
        $user = Auth::user();
        if ($availabilityRepeated->monitor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }
        $validated = $request->validate([
            'meeting_point_id' => 'sometimes|exists:meeting_points,id',
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'day_of_week' => 'sometimes|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'time' => 'sometimes|array',
            'time.*.start' => 'required',
            'time.*.end' => 'required',
            // 'time.*.start' => 'required_with:time|date_format:H:i',
            // 'time.*.end' => 'required_with:time|date_format:H:i|after:time.*.start',
            'status' => 'sometimes|boolean',
        ]);
        $availabilityRepeated->update($validated);
        return response()->json(['success' => true, 'data' => $availabilityRepeated->fresh(), 'message' => 'Mise à jour réussie.']);
    }

    /**
     * Supprime une disponibilité récurrente.
     * @group Disponibilités récurrentes
     * @urlParam availabilityRepeated int required ID de la disponibilité récurrente
     * @response 200 {"success":true,"message":"Supprimé avec succès."}
     */
    public function destroy($day)
    {
        $user = Auth::user();
        $repeated = AvailabilityRepeated::where('monitor_id', $user->id)->where('day_of_week', $day)->delete();

        return response()->json(['success' => true, 'message' => 'Supprimé avec succès.']);
    }
}
