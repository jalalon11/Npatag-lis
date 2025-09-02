<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\User;
use App\Models\School;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms with statistics.
     */
    public function index(Request $request)
    {
        try {
            // Get all sections for statistics calculation
            $allSections = Section::with(['adviser', 'school', 'students'])->get();
            
            // Calculate statistics
            $totalRooms = $allSections->count();
            $activeRooms = $allSections->where('is_active', 1)->count();
            $totalStudents = $allSections->sum(function($section) {
                return $section->students->count();
            });
            $unassignedAdvisers = $allSections->where('adviser_id', null)->count();
            
            // Build query for paginated results
            $query = Section::with(['adviser', 'school'])
                ->withCount('students')
                ->orderByRaw('CAST(REPLACE(REPLACE(REPLACE(grade_level, "Grade ", ""), "grade ", ""), " ", "") AS UNSIGNED)');
            
            // Apply filters
            if ($request->filled('school_id')) {
                $query->where('school_id', $request->school_id);
            }
            
            if ($request->filled('grade_level')) {
                $query->where('grade_level', $request->grade_level);
            }
            
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }
            
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            
            $rooms = $query->paginate(12)->withQueryString();
            
            // Get filter options
            $schools = School::all();
            $gradeLevels = Section::distinct()->pluck('grade_level')->sort()->values();
            
            return view('admin.rooms.index', compact(
                'rooms', 
                'totalRooms', 
                'activeRooms', 
                'totalStudents', 
                'unassignedAdvisers',
                'schools',
                'gradeLevels'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading rooms: ' . $e->getMessage());
            
            return view('admin.rooms.index', [
                'rooms' => collect(),
                'totalRooms' => 0,
                'activeRooms' => 0,
                'totalStudents' => 0,
                'unassignedAdvisers' => 0,
                'schools' => collect(),
                'gradeLevels' => collect()
            ])->with('error', 'Error loading rooms. Please try again.');
        }
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        try {
            $schools = School::all();
            $teachers = User::whereIn('role', ['teacher', 'admin'])->with('school')->get();
            $buildings = Building::with('school')->where('is_active', true)->get();
            $gradeLevels = [
                'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 
                'Grade 4', 'Grade 5', 'Grade 6'
            ];

            return view('admin.rooms.create', compact(
                'schools', 'teachers', 'buildings', 'gradeLevels'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading room creation form: ' . $e->getMessage());
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Failed to load room creation form.');
        }
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'grade_level' => 'required|string',
                'adviser_id' => 'nullable|exists:users,id',
                'school_id' => 'required|exists:schools,id',
                'school_year' => 'required|string|max:255',
                'student_limit' => 'nullable|integer|min:1|max:100',
                'building_id' => 'nullable|exists:buildings,id',
            ]);

            // Validate adviser if provided
            if ($request->filled('adviser_id')) {
                $adviser = User::where('id', $request->adviser_id)
                    ->where('school_id', $request->school_id)
                    ->whereIn('role', ['teacher', 'admin'])
                    ->first();

                if (!$adviser) {
                    return back()->withErrors([
                        'adviser_id' => 'The selected adviser must be a teacher or admin in the selected school.'
                    ])->withInput();
                }
            }

            Section::create([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'adviser_id' => $request->adviser_id,
                'school_id' => $request->school_id,
                'school_year' => $request->school_year,
                'student_limit' => $request->student_limit ?? 30,
                'building_id' => $request->building_id,
                'is_active' => true,
            ]);

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Room created successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create room.'])->withInput();
        }
    }

    /**
     * Display the specified room.
     */
    public function show(Section $room)
    {
        try {
            $room->load(['adviser', 'school', 'students', 'subjects', 'building']);
            return view('admin.rooms.show', compact('room'));
            
        } catch (\Exception $e) {
            Log::error('Error loading room details: ' . $e->getMessage());
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Failed to load room details.');
        }
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Section $room)
    {
        try {
            $schools = School::all();
            $teachers = User::whereIn('role', ['teacher', 'admin'])->with('school')->get();
            $buildings = Building::with('school')->where('is_active', true)->get();
            $gradeLevels = [
                'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 
                'Grade 4', 'Grade 5', 'Grade 6'
            ];

            return view('admin.rooms.edit', compact(
                'room', 'schools', 'teachers', 'buildings', 'gradeLevels'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading room edit form: ' . $e->getMessage());
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Failed to load room edit form.');
        }
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Section $room)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'grade_level' => 'required|string',
                'adviser_id' => 'nullable|exists:users,id',
                'school_id' => 'required|exists:schools,id',
                'school_year' => 'required|string|max:255',
                'student_limit' => 'nullable|integer|min:1|max:100',
                'building_id' => 'nullable|exists:buildings,id',
            ]);

            // Validate adviser if provided
            if ($request->filled('adviser_id')) {
                $adviser = User::where('id', $request->adviser_id)
                    ->where('school_id', $request->school_id)
                    ->whereIn('role', ['teacher', 'admin'])
                    ->first();

                if (!$adviser) {
                    return back()->withErrors([
                        'adviser_id' => 'The selected adviser must be a teacher or admin in the selected school.'
                    ])->withInput();
                }
            }

            $room->update([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'adviser_id' => $request->adviser_id,
                'school_id' => $request->school_id,
                'school_year' => $request->school_year,
                'student_limit' => $request->student_limit ?? 30,
                'building_id' => $request->building_id,
            ]);

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Room updated successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error updating room: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update room.'])->withInput();
        }
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Section $room)
    {
        try {
            // Check if room has students
            if ($room->students()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete room with enrolled students.'
                ], 400);
            }

            $room->delete();

            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting room: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room.'
            ], 500);
        }
    }

    /**
     * Toggle room status (active/inactive).
     */
    public function toggleStatus(Section $room)
    {
        try {
            $room->update(['is_active' => !$room->is_active]);
            
            return response()->json([
                'success' => true,
                'message' => 'Room status updated successfully.',
                'is_active' => $room->is_active
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling room status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room status.'
            ], 500);
        }
    }
}