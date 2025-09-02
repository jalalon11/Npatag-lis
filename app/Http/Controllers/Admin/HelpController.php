<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_admin) {
                abort(403, 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display the admin help index page.
     */
    public function index()
    {
        return view('admin.help.index');
    }

    /**
     * Display specific tutorial page for admins.
     */
    public function tutorial($topic)
    {
        // Validate that the requested tutorial exists
        $validTopics = [
            'schools',
            'teacher-admins',
            'sections',
            'subjects',
            'students',
            'enrollments',
            'reports',
            'support',
            'accounts',
            'announcements',
            'resources',
            'faq',
        ];

        if (!in_array($topic, $validTopics)) {
            return redirect()->route('admin.help.index')
                ->with('error', 'The requested tutorial does not exist.');
        }

        return view("admin.help.tutorials.{$topic}");
    }
}