<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeroomController extends Controller
{
    /**
     * Display a listing of all rooms with adviser assignment capability.
     */
    public function index(Request $request)
    {
        try {
            // Build query for rooms
            $query = Section::with(['adviser', 'school', 'building'])
                ->withCount('students')
                ->orderByRaw('CAST(REPLACE(REPLACE(REPLACE(grade_level, "Grade ", ""), "grade ", ""), " ", "") AS UNSIGNED)');
            
            // Apply filters
            if ($request->filled('school_id')) {
                $query->where('school_id', $request->school_id);
            }
            
            if ($request->filled('grade_level')) {
                $query->where('grade_level', $request->grade_level);
            }
            
            if ($request->filled('adviser_status')) {
                if ($request->adviser_status === 'assigned') {
                    $query->whereNotNull('adviser_id');
                } elseif ($request->adviser_status === 'unassigned') {
                    $query->whereNull('adviser_id');
                }
            }
            
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            
            $rooms = $query->paginate(15)->withQueryString();
            
            // Get filter options
            $schools = School::all();
            $gradeLevels = Section::distinct()->pluck('grade_level')->sort()->values();
            
            // Get statistics
            $totalRooms = Section::count();
            $assignedRooms = Section::whereNotNull('adviser_id')->count();
            $unassignedRooms = Section::whereNull('adviser_id')->count();
            
            return view('admin.homeroom.index', compact(
                'rooms',
                'schools',
                'gradeLevels',
                'totalRooms',
                'assignedRooms',
                'unassignedRooms'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading homeroom advising: ' . $e->getMessage());
            
            return view('admin.homeroom.index', [
                'rooms' => collect(),
                'schools' => collect(),
                'gradeLevels' => collect(),
                'totalRooms' => 0,
                'assignedRooms' => 0,
                'unassignedRooms' => 0
            ])->with('error', 'Error loading homeroom advising. Please try again.');
        }
    }
    
    /**
     * Show the form for assigning an adviser to a room.
     */
    public function assign(Section $room)
    {
        try {
            // Get available teachers and admins for the room's school
            $availableAdvisers = User::where('school_id', $room->school_id)
                ->whereIn('role', ['teacher', 'admin'])
                ->orderBy('name')
                ->get();
            
            return view('admin.homeroom.assign', compact('room', 'availableAdvisers'));
            
        } catch (\Exception $e) {
            Log::error('Error loading adviser assignment form: ' . $e->getMessage());
            return redirect()->route('admin.homeroom.index')
                ->with('error', 'Failed to load adviser assignment form.');
        }
    }
    
    /**
     * Update the adviser assignment for a room.
     */
    public function updateAdviser(Request $request, Section $room)
    {
        try {
            $request->validate([
                'adviser_id' => 'nullable|exists:users,id',
            ]);
            
            // Validate adviser if provided
            if ($request->filled('adviser_id')) {
                $adviser = User::where('id', $request->adviser_id)
                    ->where('school_id', $room->school_id)
                    ->whereIn('role', ['teacher', 'admin'])
                    ->first();
                
                if (!$adviser) {
                    return back()->withErrors([
                        'adviser_id' => 'The selected adviser must be a teacher or admin in the same school as the room.'
                    ]);
                }
            }
            
            $room->update([
                'adviser_id' => $request->adviser_id
            ]);
            
            $message = $request->filled('adviser_id') 
                ? 'Room adviser assigned successfully.' 
                : 'Room adviser removed successfully.';
            
            return redirect()->route('admin.homeroom.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error updating room adviser: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update room adviser.']);
        }
    }
    
    /**
     * Bulk assign advisers to multiple rooms.
     */
    public function bulkAssign(Request $request)
    {
        try {
            $request->validate([
                'assignments' => 'required|array',
                'assignments.*.room_id' => 'required|exists:sections,id',
                'assignments.*.adviser_id' => 'nullable|exists:users,id',
            ]);
            
            DB::beginTransaction();
            
            $successCount = 0;
            $errors = [];
            
            foreach ($request->assignments as $assignment) {
                $room = Section::find($assignment['room_id']);
                
                if (!$room) {
                    $errors[] = "Room with ID {$assignment['room_id']} not found.";
                    continue;
                }
                
                // Validate adviser if provided
                if (!empty($assignment['adviser_id'])) {
                    $adviser = User::where('id', $assignment['adviser_id'])
                        ->where('school_id', $room->school_id)
                        ->whereIn('role', ['teacher', 'admin'])
                        ->first();
                    
                    if (!$adviser) {
                        $errors[] = "Invalid adviser for room {$room->name}.";
                        continue;
                    }
                }
                
                $room->update([
                    'adviser_id' => $assignment['adviser_id'] ?: null
                ]);
                
                $successCount++;
            }
            
            DB::commit();
            
            $message = "Successfully updated {$successCount} room assignments.";
            if (!empty($errors)) {
                $message .= ' Some assignments failed: ' . implode(', ', $errors);
            }
            
            return redirect()->route('admin.homeroom.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk adviser assignment: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update room assignments.']);
        }
    }
}