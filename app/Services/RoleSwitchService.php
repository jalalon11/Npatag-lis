<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class RoleSwitchService
{
    const SESSION_KEY = 'admin_acting_as_teacher';
    const TEACHER_MODE = 'teacher';
    const ADMIN_MODE = 'admin';

    /**
     * Check if admin is currently acting as teacher
     */
    public static function isAdminActingAsTeacher(): bool
    {
        return Auth::check() && 
               Auth::user()->role === 'admin' && 
               Session::get(self::SESSION_KEY, false) === true;
    }

    /**
     * Get the current effective role (what role the user is acting as)
     */
    public static function getCurrentMode(): string
    {
        if (self::isAdminActingAsTeacher()) {
            return self::TEACHER_MODE;
        }
        
        return Auth::user()->role ?? 'guest';
    }

    /**
     * Switch admin to teacher mode
     */
    public static function switchToTeacherMode(): bool
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return false;
        }

        Session::put(self::SESSION_KEY, true);
        return true;
    }

    /**
     * Switch back to admin mode
     */
    public static function switchToAdminMode(): bool
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return false;
        }

        Session::forget(self::SESSION_KEY);
        return true;
    }

    /**
     * Toggle between admin and teacher mode
     */
    public static function toggleMode(): string
    {
        if (self::isAdminActingAsTeacher()) {
            self::switchToAdminMode();
            return self::ADMIN_MODE;
        } else {
            self::switchToTeacherMode();
            return self::TEACHER_MODE;
        }
    }

    /**
     * Check if user can switch roles (only admins can)
     */
    public static function canSwitchRoles(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the appropriate dashboard route based on current mode
     */
    public static function getDashboardRoute(): string
    {
        if (self::isAdminActingAsTeacher()) {
            return route('teacher.dashboard');
        }
        
        $user = Auth::user();
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role === 'teacher') {
            return route('teacher.dashboard');
        }
        
        return route('home');
    }

    /**
     * Get the appropriate profile route based on current mode
     */
    public static function getProfileRoute(): string
    {
        if (self::isAdminActingAsTeacher()) {
            return route('teacher.profile');
        }
        
        $user = Auth::user();
        if ($user->role === 'admin') {
            return route('admin.profile');
        } elseif ($user->role === 'teacher') {
            return route('teacher.profile');
        }
        
        return route('home');
    }
}