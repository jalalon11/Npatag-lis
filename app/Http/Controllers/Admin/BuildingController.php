<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\School;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $query = Building::with(['school', 'rooms'])
                ->when($user->role !== 'super_admin' && $user->role !== 'admin', function ($q) use ($user) {
                    return $q->where('school_id', $user->school_id);
                });

            // Get all buildings for statistics
            $allBuildings = $query->get();
            
            // Calculate statistics
            $totalBuildings = $allBuildings->count();
            $activeBuildings = $allBuildings->where('is_active', 1)->count();
            $totalRooms = $allBuildings->sum(function($building) {
                return $building->rooms->count();
            });
            $emptyBuildings = $allBuildings->filter(function($building) {
                return $building->rooms->count() === 0;
            })->count();

            $buildings = $query->paginate(15);

            return view('admin.buildings.index', compact(
                'buildings',
                'totalBuildings',
                'activeBuildings', 
                'totalRooms',
                'emptyBuildings'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading buildings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load buildings.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Get the single school in the system
            $school = School::first();
            
            if (!$school) {
                return redirect()->route('admin.buildings.index')
                    ->with('error', 'No school found in the system.');
            }
            
            return view('admin.buildings.create', compact('school'));
        } catch (\Exception $e) {
            Log::error('Error loading building creation form: ' . $e->getMessage());
            return redirect()->route('admin.buildings.index')
                ->with('error', 'Failed to load building creation form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            // Get the single school in the system
            $school = School::first();
            
            if (!$school) {
                return redirect()->back()
                    ->with('error', 'No school found in the system.');
            }

            Building::create([
                'name' => $request->name,
                'description' => $request->description,
                'school_id' => $school->id,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating building: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create building. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        try {
            $user = Auth::user();
            
            // Check access permissions
            if ($user->role !== 'super_admin' && $user->role !== 'admin' && $building->school_id !== $user->school_id) {
                return redirect()->route('admin.buildings.index')
                    ->with('error', 'Access denied.');
            }

            $building->load(['school', 'rooms.adviser', 'rooms.students']);
            
            // Get available rooms that can be assigned to this building
            $availableRooms = Section::where('school_id', $building->school_id)
                ->whereNull('building_id')
                ->where('is_active', true)
                ->get();

            return view('admin.buildings.show', compact('building', 'availableRooms'));
        } catch (\Exception $e) {
            Log::error('Error loading building details: ' . $e->getMessage());
            return redirect()->route('admin.buildings.index')
                ->with('error', 'Failed to load building details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        try {
            // Get the single school in the system
            $school = School::first();
            
            if (!$school) {
                return redirect()->route('admin.buildings.index')
                    ->with('error', 'No school found in the system.');
            }

            return view('admin.buildings.edit', compact('building', 'school'));
        } catch (\Exception $e) {
            Log::error('Error loading building edit form: ' . $e->getMessage());
            return redirect()->route('admin.buildings.index')
                ->with('error', 'Failed to load edit form.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Building $building)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            // Get the single school in the system
            $school = School::first();
            
            if (!$school) {
                return redirect()->back()
                    ->with('error', 'No school found in the system.');
            }

            $building->update([
                'name' => $request->name,
                'description' => $request->description,
                'school_id' => $school->id,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating building: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update building. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        try {
            $user = Auth::user();
            
            // Check access permissions
            if ($user->role !== 'super_admin' && $user->role !== 'admin' && $building->school_id !== $user->school_id) {
                return redirect()->route('admin.buildings.index')
                    ->with('error', 'Access denied.');
            }

            // Check if building has assigned rooms
            if ($building->rooms()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete building with assigned rooms. Please reassign rooms first.');
            }

            $building->delete();

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting building: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete building. Please try again.');
        }
    }

    /**
     * Assign a room to a building.
     */
    public function assignRoom(Request $request, Building $building)
    {
        $request->validate([
            'room_id' => 'required|exists:sections,id',
        ]);

        try {
            $user = Auth::user();
            
            // Check access permissions
            if ($user->role !== 'super_admin' && $user->role !== 'admin' && $building->school_id !== $user->school_id) {
                return redirect()->back()->with('error', 'Access denied.');
            }

            $room = Section::findOrFail($request->room_id);
            
            // Ensure room belongs to the same school
            if ($room->school_id !== $building->school_id) {
                return redirect()->back()
                    ->with('error', 'Room must belong to the same school as the building.');
            }

            // Check if room is already assigned to a building
            if ($room->building_id) {
                return redirect()->back()
                    ->with('error', 'Room is already assigned to another building.');
            }

            $room->update(['building_id' => $building->id]);

            return redirect()->back()
                ->with('success', 'Room assigned to building successfully.');
        } catch (\Exception $e) {
            Log::error('Error assigning room to building: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to assign room. Please try again.');
        }
    }

    /**
     * Unassign a room from a building.
     */
    public function unassignRoom(Request $request, Building $building)
    {
        $request->validate([
            'room_id' => 'required|exists:sections,id',
        ]);

        try {
            $user = Auth::user();
            
            // Check access permissions
            if ($user->role !== 'super_admin' && $user->role !== 'admin' && $building->school_id !== $user->school_id) {
                return redirect()->back()->with('error', 'Access denied.');
            }

            $room = Section::findOrFail($request->room_id);
            
            // Ensure room belongs to this building
            if ($room->building_id !== $building->id) {
                return redirect()->back()
                    ->with('error', 'Room is not assigned to this building.');
            }

            $room->update(['building_id' => null]);

            return redirect()->back()
                ->with('success', 'Room unassigned from building successfully.');
        } catch (\Exception $e) {
            Log::error('Error unassigning room from building: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to unassign room. Please try again.');
        }
    }
}
