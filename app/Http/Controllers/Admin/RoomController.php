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
use App\Models\Teacher;
use App\Models\Subject;

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
            $defaultSchool = $schools->first(); // Get the first (and likely only) school
            $teachers = User::whereIn('role', ['teacher', 'admin'])->with('school')->get();
            $buildings = Building::with('school')->where('is_active', true)->get();
            $gradeLevels = [
                'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 
                'Grade 4', 'Grade 5', 'Grade 6'
            ];

            return view('admin.rooms.create', compact(
                'schools', 'teachers', 'buildings', 'gradeLevels', 'defaultSchool'
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
            // Check if this is a batch entry
            $isBatch = $request->has('is_batch') && ($request->is_batch == '1' || $request->is_batch == 1);
            
            if ($isBatch) {
                return $this->storeBatch($request);
            }
            
            // Single room creation
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
     * Store batch rooms from CSV-like input.
     */
    private function storeBatch(Request $request)
    {
        try {
            // Validate batch input
            $request->validate([
                'batch_data' => 'required|string',
            ]);
            
            $batchData = trim($request->batch_data);
            $lines = explode("\n", $batchData);
            $createdCount = 0;
            $errors = [];
            
            // Grade level mapping from numbers to strings
            $gradeLevelMap = [
                '1' => 'Grade 1',
                '2' => 'Grade 2', 
                '3' => 'Grade 3',
                '4' => 'Grade 4',
                '5' => 'Grade 5',
                '6' => 'Grade 6',
                '7' => 'Grade 7',
                '8' => 'Grade 8',
                '9' => 'Grade 9',
                '10' => 'Grade 10',
                '11' => 'Grade 11',
                '12' => 'Grade 12',
                'K' => 'Kindergarten',
                'k' => 'Kindergarten'
            ];
            
            foreach ($lines as $lineNumber => $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                // Parse CSV format: Room Name, Grade Level, School Year, Adviser ID, Building ID, Student Limit
                $parts = array_map('trim', explode(',', $line));
                
                if (count($parts) < 3) {
                    $errors[] = "Line " . ($lineNumber + 1) . ": Invalid format. Expected at least: Room Name, Grade Level, School Year";
                    continue;
                }
                
                $name = $parts[0];
                $gradeLevel = $parts[1];
                $schoolYear = $parts[2];
                $adviserId = !empty($parts[3]) ? $parts[3] : null;
                $buildingId = !empty($parts[4]) ? $parts[4] : null;
                $studentLimit = !empty($parts[5]) ? (int)$parts[5] : 30;
                
                // Convert numeric grade level to string format
                if (isset($gradeLevelMap[$gradeLevel])) {
                    $gradeLevel = $gradeLevelMap[$gradeLevel];
                }
                
                // Validate required fields
                if (empty($name)) {
                    $errors[] = "Line " . ($lineNumber + 1) . ": Room name is required";
                    continue;
                }
                
                if (empty($gradeLevel)) {
                    $errors[] = "Line " . ($lineNumber + 1) . ": Grade level is required";
                    continue;
                }
                
                if (empty($schoolYear)) {
                    $errors[] = "Line " . ($lineNumber + 1) . ": School year is required";
                    continue;
                }
                
                // Get default school (assuming single school system)
                $school = School::first();
                if (!$school) {
                    $errors[] = "Line " . ($lineNumber + 1) . ": No school found in system";
                    continue;
                }
                
                // Validate adviser if provided
                if ($adviserId) {
                    $adviser = User::where('id', $adviserId)
                        ->where('school_id', $school->id)
                        ->whereIn('role', ['teacher', 'admin'])
                        ->first();
                        
                    if (!$adviser) {
                        $errors[] = "Line " . ($lineNumber + 1) . ": Invalid adviser ID or adviser not in school";
                        continue;
                    }
                }
                
                // Validate building if provided
                if ($buildingId) {
                    $building = Building::where('id', $buildingId)
                        ->where('school_id', $school->id)
                        ->where('is_active', true)
                        ->first();
                        
                    if (!$building) {
                        $errors[] = "Line " . ($lineNumber + 1) . ": Invalid building ID or building not active";
                        continue;
                    }
                }
                
                try {
                    Section::create([
                        'name' => $name,
                        'grade_level' => $gradeLevel,
                        'adviser_id' => $adviserId,
                        'school_id' => $school->id,
                        'school_year' => $schoolYear,
                        'student_limit' => $studentLimit,
                        'building_id' => $buildingId,
                        'is_active' => true,
                    ]);
                    
                    $createdCount++;
                } catch (\Exception $e) {
                    $errors[] = "Line " . ($lineNumber + 1) . ": " . $e->getMessage();
                }
            }
            
            if ($createdCount > 0 && empty($errors)) {
                return redirect()->route('admin.rooms.index')
                    ->with('success', "Successfully created {$createdCount} rooms.");
            } elseif ($createdCount > 0 && !empty($errors)) {
                return redirect()->route('admin.rooms.index')
                    ->with('warning', "Created {$createdCount} rooms with some errors: " . implode(', ', $errors));
            } else {
                return back()->withErrors([
                    'batch_data' => 'Failed to create any rooms. Errors: ' . implode(', ', $errors)
                ])->withInput();
            }
            
        } catch (\Exception $e) {
            Log::error('Error creating batch rooms: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create rooms.'])->withInput();
        }
    }

    /**
     * Display the specified room.
     */
    public function show(Section $room)
    {
        try {
            $room->load(['adviser', 'school', 'students', 'subjects', 'building']);
            $availableSubjects = Subject::all();
            $teachers = User::whereIn('role', ['teacher', 'admin'])->with('school')->get();
            return view('admin.rooms.show', compact('room', 'availableSubjects', 'teachers'));
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
            $defaultSchool = $schools->first(); // Get the first (and likely only) school
            $teachers = User::whereIn('role', ['teacher', 'admin'])->with('school')->get();
            $buildings = Building::with('school')->where('is_active', true)->get();
            $gradeLevels = [
                'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 
                'Grade 4', 'Grade 5', 'Grade 6'
            ];

            return view('admin.rooms.edit', compact(
                'room', 'schools', 'teachers', 'buildings', 'gradeLevels', 'defaultSchool'
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

    /**
     * Assign subjects to the room.
     */
    public function assignSubjects(Request $request, Section $room)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'subjects' => 'required|array',
                'subjects.*.subject_id' => 'required|exists:subjects,id',
                'subjects.*.teacher_id' => 'required|exists:users,id',
            ]);

            Log::info('Assigning subjects to room', [
                'room_id' => $room->id,
                'subjects' => $request->subjects
            ]);

            // Begin transaction
            DB::beginTransaction();

            // Instead of clearing all existing subjects, we'll determine which ones to add or update
            $existingSubjectIds = $room->subjects->pluck('id')->toArray();
            $newSubjectIds = collect($request->subjects)->pluck('subject_id')->toArray();

            // Loop through new subject assignments
            foreach ($request->subjects as $subject) {
                // Check if this subject is already assigned to this room
                $existingPivot = DB::table('section_subject')
                    ->where('section_id', $room->id)
                    ->where('subject_id', $subject['subject_id'])
                    ->first();

                if ($existingPivot) {
                    // Update the existing subject-teacher assignment
                    DB::table('section_subject')
                        ->where('section_id', $room->id)
                        ->where('subject_id', $subject['subject_id'])
                        ->update([
                            'teacher_id' => $subject['teacher_id'],
                            'updated_at' => now(),
                        ]);
                } else {
                    // Insert a new subject-teacher assignment
                    DB::table('section_subject')->insert([
                        'section_id' => $room->id,
                        'subject_id' => $subject['subject_id'],
                        'teacher_id' => $subject['teacher_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Log success
            Log::info('Subjects assigned successfully', ['room_id' => $room->id]);

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.rooms.show', $room)
                ->with('success', 'Subjects assigned successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            Log::error('Failed to assign subjects: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Failed to assign subjects: ' . $e->getMessage());
        }
    }
}