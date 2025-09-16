<?php

namespace App\Http\Controllers;

use App\Models\DailyTeam;
use App\Models\Employee;
use App\Models\Pep;
use App\Models\TeamName;
use App\Models\Vehicle;
use App\Models\SubTeamName;
use Illuminate\Http\Request;

class DailyTeamController extends Controller
{
    public function index()
    {
        $teams = DailyTeam::with([
            'teamname', 'pep', 'leader',
            'dailyTeamMembers.employee',
            'dailyTeamVehicles.vehicle',
            'subTeams.subTeamName',
            'subTeams.leader',
            'subTeams.members.employee',
            'subTeams.vehicles.vehicle',
        ])->orderByDesc('work_date')->get();

        return view('dashboard-daily-teams', compact('teams'));
    }

    public function create()
    {
        $teamNames = TeamName::pluck('name', 'id');
        $peps = Pep::pluck('code', 'id');
        $employees = Employee::pluck('full_name', 'id');
        $vehicles = Vehicle::pluck('car_plate', 'id');
        $subTeamNames = SubTeamName::pluck('name', 'id');

        return view('daily-teams.create', compact('teamNames', 'peps', 'employees', 'vehicles', 'subTeamNames'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'team_name_id' => 'required|exists:team_names,id',
            'pep_id' => 'required|exists:peps,id',
            'work_date' => 'required|date',
            'leader_id' => 'required|exists:employees,id',
            'work_type' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $team = DailyTeam::create($data);

        // Miembros directos
        if ($request->has('dailyTeamMembers')) {
            foreach ($request->dailyTeamMembers as $member) {
                $team->dailyTeamMembers()->create([
                    'employee_id' => $member['employee_id'],
                ]);
            }
        }

        // Vehículos directos
        if ($request->has('dailyTeamVehicles')) {
            foreach ($request->dailyTeamVehicles as $vehicle) {
                $team->dailyTeamVehicles()->create([
                    'vehicle_id' => $vehicle['vehicle_id'],
                    'status' => $vehicle['status'] ?? null,
                ]);
            }
        }

        // Subequipos
        if ($request->has('subTeams')) {
            foreach ($request->subTeams as $subTeam) {
                $newSubTeam = $team->subTeams()->create([
                    'sub_team_name_id' => $subTeam['sub_team_name_id'],
                    'leader_id' => $subTeam['leader_id'],
                ]);
                // Miembros del subequipo
                if (!empty($subTeam['members'])) {
                    foreach ($subTeam['members'] as $subMember) {
                        $newSubTeam->members()->create([
                            'employee_id' => $subMember['employee_id'],
                        ]);
                    }
                }
                // Vehículos del subequipo
                if (!empty($subTeam['vehicles'])) {
                    foreach ($subTeam['vehicles'] as $subVehicle) {
                        $newSubTeam->vehicles()->create([
                            'vehicle_id' => $subVehicle['vehicle_id'],
                            'status' => $subVehicle['status'] ?? null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('daily-teams.index')->with('success', 'Equipo creado correctamente.');
    }

    public function edit($id)
    {
        $team = DailyTeam::with([
            'teamname', 'pep', 'leader',
            'dailyTeamMembers.employee',
            'dailyTeamVehicles.vehicle',
            'subTeams.subTeamName',
            'subTeams.leader',
            'subTeams.members.employee',
            'subTeams.vehicles.vehicle',
        ])->findOrFail($id);

        $teamNames = TeamName::pluck('name', 'id');
        $peps = Pep::pluck('code', 'id');
        $employees = Employee::pluck('full_name', 'id');
        $vehicles = Vehicle::pluck('car_plate', 'id');
        $subTeamNames = SubTeamName::pluck('name', 'id');

        return view('daily-teams.edit', compact('team', 'teamNames', 'peps', 'employees', 'vehicles', 'subTeamNames'));
    }

    public function update(Request $request, $id)
    {
        $team = DailyTeam::findOrFail($id);

        $data = $request->validate([
            'team_name_id' => 'required|exists:team_names,id',
            'pep_id' => 'required|exists:peps,id',
            'work_date' => 'required|date',
            'leader_id' => 'required|exists:employees,id',
            'work_type' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $team->update($data);

        // Actualizar miembros directos
        if ($request->has('dailyTeamMembers')) {
            $currentMemberIds = $team->dailyTeamMembers()->pluck('id')->toArray();
            $newMemberIds = collect($request->dailyTeamMembers)->pluck('id')->filter()->toArray();

            // Eliminar miembros que ya no están
            $idsToDelete = array_diff($currentMemberIds, $newMemberIds);
            foreach ($idsToDelete as $idToDelete) {
                $team->dailyTeamMembers()->find($idToDelete)?->delete();
            }

            // Actualizar o agregar nuevos miembros
            foreach ($request->dailyTeamMembers as $member) {
                $team->dailyTeamMembers()->updateOrCreate(
                    ['id' => $member['id'] ?? null],
                    ['employee_id' => $member['employee_id']]
                );
            }
        }

        // Actualizar vehículos directos
        if ($request->has('dailyTeamVehicles')) {
            $currentVehicleIds = $team->dailyTeamVehicles()->pluck('id')->toArray();
            $newVehicleIds = collect($request->dailyTeamVehicles)->pluck('id')->filter()->toArray();

            // Eliminar vehículos que ya no están
            $idsToDelete = array_diff($currentVehicleIds, $newVehicleIds);
            foreach ($idsToDelete as $idToDelete) {
                $team->dailyTeamVehicles()->find($idToDelete)?->delete();
            }

            // Actualizar o agregar nuevos vehículos
            foreach ($request->dailyTeamVehicles as $vehicle) {
                $team->dailyTeamVehicles()->updateOrCreate(
                    ['id' => $vehicle['id'] ?? null],
                    [
                        'vehicle_id' => $vehicle['vehicle_id'],
                        'status' => $vehicle['status'] ?? null,
                    ]
                );
            }
        }

        // Actualizar subequipos
        if ($request->has('subTeams')) {
            $currentSubTeamIds = $team->subTeams()->pluck('id')->toArray();
            $newSubTeamIds = collect($request->subTeams)->pluck('id')->filter()->toArray();

            // Eliminar subequipos que ya no están
            $idsToDelete = array_diff($currentSubTeamIds, $newSubTeamIds);
            foreach ($idsToDelete as $idToDelete) {
                $team->subTeams()->find($idToDelete)?->delete();
            }

            // Actualizar o agregar nuevos subequipos
            foreach ($request->subTeams as $subTeam) {
                $newSubTeam = $team->subTeams()->updateOrCreate(
                    ['id' => $subTeam['id'] ?? null],
                    [
                        'sub_team_name_id' => $subTeam['sub_team_name_id'],
                        'leader_id' => $subTeam['leader_id'],
                    ]
                );

                // Miembros del subequipo
                if (!empty($subTeam['members'])) {
                    $currentSubMemberIds = $newSubTeam->members()->pluck('id')->toArray();
                    $newSubMemberIds = collect($subTeam['members'])->pluck('id')->filter()->toArray();

                    // Eliminar miembros que ya no están
                    $idsToDelete = array_diff($currentSubMemberIds, $newSubMemberIds);
                    foreach ($idsToDelete as $idToDelete) {
                        $newSubTeam->members()->find($idToDelete)?->delete();
                    }

                    // Actualizar o agregar nuevos miembros
                    foreach ($subTeam['members'] as $subMember) {
                        $newSubTeam->members()->updateOrCreate(
                            ['id' => $subMember['id'] ?? null],
                            ['employee_id' => $subMember['employee_id']]
                        );
                    }
                }

                // Vehículos del subequipo
                if (!empty($subTeam['vehicles'])) {
                    $currentSubVehicleIds = $newSubTeam->vehicles()->pluck('id')->toArray();
                    $newSubVehicleIds = collect($subTeam['vehicles'])->pluck('id')->filter()->toArray();

                    // Eliminar vehículos que ya no están
                    $idsToDelete = array_diff($currentSubVehicleIds, $newSubVehicleIds);
                    foreach ($idsToDelete as $idToDelete) {
                        $newSubTeam->vehicles()->find($idToDelete)?->delete();
                    }

                    // Actualizar o agregar nuevos vehículos
                    foreach ($subTeam['vehicles'] as $subVehicle) {
                        $newSubTeam->vehicles()->updateOrCreate(
                            ['id' => $subVehicle['id'] ?? null],
                            [
                                'vehicle_id' => $subVehicle['vehicle_id'],
                                'status' => $subVehicle['status'] ?? null,
                            ]
                        );
                    }
                }
            }
        }

        return redirect()->route('daily-teams.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $team = DailyTeam::findOrFail($id);
        $team->delete();

        return redirect()->route('daily-teams.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
