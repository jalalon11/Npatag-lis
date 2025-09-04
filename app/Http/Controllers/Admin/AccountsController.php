<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Attendance;

class AccountsController extends Controller
{
    /**
     * Display a listing of all accounts (teachers, teacher admins, guardians).
     */
    public function index()
    {
        $query = User::with(['school'])
            ->whereIn('role', ['admin', 'teacher', 'guardian']);

        // Handle search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Handle role filter
        if (request('role') && in_array(request('role'), ['admin', 'teacher', 'guardian'])) {
            $query->where('role', request('role'));
        }

        // Handle sorting
        $sort = request('sort', 'name');
        $order = request('order', 'asc');

        switch ($sort) {
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'role':
                $query->orderBy('role', $order);
                break;
            case 'created_at':
                $query->orderBy('created_at', $order);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $accounts = $query->get();
        $school = School::first();

        return view('admin.accounts.index', compact('accounts', 'school'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        $school = School::first();
        return view('admin.accounts.create', compact('school'));
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,guardian',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $school = School::first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => $request->role === 'admin' ? null : $school->id,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        $roleLabel = $request->role === 'admin' ? 'Administrator' : ucfirst(str_replace('_', ' ', $request->role));
        return redirect()->route('admin.accounts.index')
            ->with('success', "{$roleLabel} account created successfully for {$user->name}.");
    }

    /**
     * Display the specified account.
     */
    public function show(string $id)
    {
        $account = User::whereIn('role', ['admin', 'teacher', 'guardian'])
                       ->with('school')
                       ->findOrFail($id);
                       
        $sections = [];
        $teachingAssignments = [];
        
        // Only get teaching data for teachers and teacher admins
        if (in_array($account->role, ['teacher'])) {
            // Get sections where this user is the adviser
            $sections = Section::where('adviser_id', $account->id)->get();
            
            // Get teaching assignments from section_subject table
            $teachingAssignments = DB::table('section_subject')
                ->where('teacher_id', $account->id)
                ->join('sections', 'section_subject.section_id', '=', 'sections.id')
                ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
                ->select('sections.name as section_name', 'sections.grade_level', 'subjects.name as subject_name')
                ->get();
        }

        return view('admin.accounts.show', compact('account', 'teachingAssignments', 'sections'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(string $id)
    {
        $account = User::whereIn('role', ['admin', 'teacher', 'guardian'])->findOrFail($id);
        $school = School::first();
        return view('admin.accounts.edit', compact('account', 'school'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = User::whereIn('role', ['admin', 'teacher', 'guardian'])->findOrFail($id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($account->id),
            ],
            'role' => 'required|in:admin,teacher,guardian',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];
        
        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        
        $request->validate($rules);
        
        // Update account data
        $account->name = $request->name;
        $account->email = $request->email;
        $account->role = $request->role;
        $account->phone_number = $request->phone_number;
        $account->address = $request->address;
        
        // Only update password if it's provided
        if ($request->filled('password')) {
            $account->password = Hash::make($request->password);
        }
        
        $account->save();
        
        $roleLabel = ucfirst(str_replace('_', ' ', $request->role));
        return redirect()->route('admin.accounts.index')
            ->with('success', "{$roleLabel} account updated successfully.");
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $account = User::whereIn('role', ['admin', 'teacher', 'guardian'])->findOrFail($id);
            $roleLabel = ucfirst(str_replace('_', ' ', $account->role));
            
            // Handle references for teachers and teacher admins
            if (in_array($account->role, ['teacher'])) {
                // Handle sections where this user is adviser/homeroom teacher
                $sections = Section::where('adviser_id', $account->id)->get();
                
                foreach ($sections as $section) {
                    $section->adviser_id = null;
                    $section->save();
                }
                
                // Handle references in subject assignments
                DB::table('section_subject')
                    ->where('teacher_id', $account->id)
                    ->delete();
                    
                // Handle subjects taught by the user
                Subject::where('user_id', $account->id)->update(['user_id' => null]);
                
                // Handle attendance records
                Attendance::where('teacher_id', $account->id)->update(['teacher_id' => null]);
            }
            
            // Delete the account
            $account->delete();
            
            DB::commit();
            
            return redirect()->route('admin.accounts.index')
                ->with('success', "{$roleLabel} account deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Unable to delete account: ' . $e->getMessage());
        }
    }

    /**
     * Reset password for an account
     */
    public function resetPassword(Request $request, string $id)
    {
        $account = User::whereIn('role', ['teacher', 'guardian'])->findOrFail($id);
        
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $account->password = Hash::make($request->password);
        $account->save();
        
        return redirect()->back()->with('success', 'Password has been reset successfully for ' . $account->name);
    }

    /**
     * Promote a teacher to admin role
     */
    public function promoteToAdmin(Request $request, string $id)
    {
        $account = User::where('role', 'teacher')->findOrFail($id);
        
        $account->role = 'admin';
        $account->save();
        
        return redirect()->route('admin.accounts.index')
            ->with('success', $account->name . ' has been promoted to Admin successfully.');
    }

    /**
     * Demote an admin to teacher role
     */
    public function demoteToTeacher(Request $request, string $id)
    {
        $account = User::where('role', 'admin')->findOrFail($id);
        
        // Prevent demoting the current admin
        if ($account->id === auth()->id()) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'You cannot demote yourself.');
        }
        
        $account->role = 'teacher';
        $account->save();
        
        return redirect()->route('admin.accounts.index')
            ->with('success', $account->name . ' has been demoted to Teacher successfully.');
    }
}