<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Section $section): bool
    {
        // Only allow admins to view sections from their school
        return $user->role === 'admin' && 
               $user->school_id === $section->school_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Section $section): bool
    {
        // Only allow admins to update sections from their school
        return $user->role === 'admin' && 
               $user->school_id === $section->school_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Section $section): bool
    {
        // Only allow admins to delete sections from their school
        return $user->role === 'admin' && 
               $user->school_id === $section->school_id;
    }
}