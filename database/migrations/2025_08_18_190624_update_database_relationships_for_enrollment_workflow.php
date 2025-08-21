<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // 1. Ensure enrollments table has proper indexes for performance
            if (Schema::hasTable('enrollments')) {
                Schema::table('enrollments', function (Blueprint $table) {
                    // Add composite index for common queries
                    if (!$this->indexExists('enrollments', 'enrollments_school_status_grade_index')) {
                        $table->index(['school_id', 'status', 'preferred_grade_level'], 'enrollments_school_status_grade_index');
                    }
                    
                    // Add index for enrollment tracking queries
                    if (!$this->indexExists('enrollments', 'enrollments_student_id_lrn_index')) {
                        $table->index(['student_id', 'lrn'], 'enrollments_student_id_lrn_index');
                    }
                    
                    // Add index for processing queries
                    if (!$this->indexExists('enrollments', 'enrollments_processed_by_processed_at_index')) {
                        $table->index(['processed_by', 'processed_at'], 'enrollments_processed_by_processed_at_index');
                    }
                });
            }
            
            // 2. Ensure students table has proper constraints and indexes
            if (Schema::hasTable('students')) {
                Schema::table('students', function (Blueprint $table) {
                    // Add composite index for enrollment-related queries
                    if (!$this->indexExists('students', 'students_enrollment_school_year_index')) {
                        $table->index(['enrollment_id', 'school_year'], 'students_enrollment_school_year_index');
                    }
                    
                    // Add index for active students queries
                    if (!$this->indexExists('students', 'students_is_active_section_index')) {
                        $table->index(['is_active', 'section_id'], 'students_is_active_section_index');
                    }
                    
                    // Ensure unique constraint on student_id is case-insensitive
                    // This helps prevent duplicate student IDs with different cases
                    if (!$this->indexExists('students', 'students_student_id_unique_ci')) {
                        DB::statement('CREATE UNIQUE INDEX students_student_id_unique_ci ON students (UPPER(student_id))');
                    }
                    
                    // Ensure unique constraint on lrn is case-insensitive
                    if (!$this->indexExists('students', 'students_lrn_unique_ci')) {
                        DB::statement('CREATE UNIQUE INDEX students_lrn_unique_ci ON students (UPPER(lrn)) WHERE lrn IS NOT NULL');
                    }
                });
            }
            
            // 3. Add constraints to ensure data integrity
            if (Schema::hasTable('enrollments') && Schema::hasTable('students')) {
                // Ensure that when a student is created from an enrollment,
                // the enrollment status should be 'enrolled'
                DB::statement("
                    CREATE TRIGGER IF NOT EXISTS update_enrollment_status_on_student_creation
                    AFTER INSERT ON students
                    FOR EACH ROW
                    BEGIN
                        IF NEW.enrollment_id IS NOT NULL THEN
                            UPDATE enrollments 
                            SET status = 'enrolled', 
                                processed_at = NOW()
                            WHERE id = NEW.enrollment_id 
                            AND status = 'approved';
                        END IF;
                    END
                ");
                
                // Ensure that when a student's enrollment_id is updated,
                // the old enrollment is marked as processed
                DB::statement("
                    CREATE TRIGGER IF NOT EXISTS update_enrollment_on_student_update
                    AFTER UPDATE ON students
                    FOR EACH ROW
                    BEGIN
                        IF OLD.enrollment_id IS NOT NULL AND NEW.enrollment_id != OLD.enrollment_id THEN
                            UPDATE enrollments 
                            SET status = 'enrolled', 
                                processed_at = NOW()
                            WHERE id = OLD.enrollment_id;
                        END IF;
                        
                        IF NEW.enrollment_id IS NOT NULL AND NEW.enrollment_id != OLD.enrollment_id THEN
                            UPDATE enrollments 
                            SET status = 'enrolled', 
                                processed_at = NOW()
                            WHERE id = NEW.enrollment_id 
                            AND status = 'approved';
                        END IF;
                    END
                ");
            }
            
            // 4. Ensure sections table has proper indexes for enrollment queries
            if (Schema::hasTable('sections')) {
                Schema::table('sections', function (Blueprint $table) {
                    // Add composite index for section queries by school and grade
                    if (!$this->indexExists('sections', 'sections_school_grade_active_index')) {
                        $table->index(['school_id', 'grade_level', 'is_active'], 'sections_school_grade_active_index');
                    }
                });
            }
            
            // 5. Add check constraints to ensure data consistency
            if (Schema::hasTable('enrollments')) {
                // Ensure enrollment dates are logical
                DB::statement("
                    ALTER TABLE enrollments 
                    ADD CONSTRAINT chk_enrollment_dates 
                    CHECK (processed_at IS NULL OR processed_at >= created_at)
                ");
                
                // Ensure approved/enrolled enrollments have assigned sections
                DB::statement("
                    ALTER TABLE enrollments 
                    ADD CONSTRAINT chk_enrollment_section_assignment 
                    CHECK (
                        (status IN ('approved', 'enrolled') AND assigned_section_id IS NOT NULL) OR 
                        (status NOT IN ('approved', 'enrolled'))
                    )
                ");
            }
            
            // 6. Ensure proper cascade behavior for enrollment workflow
            if (Schema::hasTable('students') && Schema::hasTable('enrollments')) {
                // Update the foreign key constraint to use SET NULL instead of CASCADE
                // This prevents accidental deletion of enrollment records
                $this->updateForeignKeyConstraint(
                    'students', 
                    'enrollment_id', 
                    'enrollments', 
                    'id', 
                    'SET NULL'
                );
            }
            
        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            \Log::error('Error in enrollment workflow migration: ' . $e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // Drop triggers
            DB::statement('DROP TRIGGER IF EXISTS update_enrollment_status_on_student_creation');
            DB::statement('DROP TRIGGER IF EXISTS update_enrollment_on_student_update');
            
            // Drop check constraints
            if (Schema::hasTable('enrollments')) {
                try {
                    DB::statement('ALTER TABLE enrollments DROP CONSTRAINT chk_enrollment_dates');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }
                
                try {
                    DB::statement('ALTER TABLE enrollments DROP CONSTRAINT chk_enrollment_section_assignment');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }
            }
            
            // Drop custom indexes
            if (Schema::hasTable('enrollments')) {
                Schema::table('enrollments', function (Blueprint $table) {
                    $table->dropIndex('enrollments_school_status_grade_index');
                    $table->dropIndex('enrollments_student_id_lrn_index');
                    $table->dropIndex('enrollments_processed_by_processed_at_index');
                });
            }
            
            if (Schema::hasTable('students')) {
                Schema::table('students', function (Blueprint $table) {
                    $table->dropIndex('students_enrollment_school_year_index');
                    $table->dropIndex('students_is_active_section_index');
                });
                
                // Drop custom unique indexes
                DB::statement('DROP INDEX IF EXISTS students_student_id_unique_ci ON students');
                DB::statement('DROP INDEX IF EXISTS students_lrn_unique_ci ON students');
            }
            
            if (Schema::hasTable('sections')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->dropIndex('sections_school_grade_active_index');
                });
            }
            
        } catch (\Exception $e) {
            \Log::error('Error in enrollment workflow migration rollback: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
    
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("
                SELECT INDEX_NAME 
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND INDEX_NAME = ?
            ", [$table, $indexName]);
            
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Update foreign key constraint
     */
    private function updateForeignKeyConstraint(
        string $table, 
        string $column, 
        string $referencedTable, 
        string $referencedColumn, 
        string $onDelete
    ): void {
        try {
            // Get existing foreign key constraint name
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND COLUMN_NAME = ? 
                AND REFERENCED_TABLE_NAME = ?
            ", [$table, $column, $referencedTable]);
            
            if (!empty($constraints)) {
                $constraintName = $constraints[0]->CONSTRAINT_NAME;
                
                // Drop existing constraint
                DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraintName}");
                
                // Add new constraint with updated behavior
                DB::statement("
                    ALTER TABLE {$table} 
                    ADD CONSTRAINT {$constraintName} 
                    FOREIGN KEY ({$column}) 
                    REFERENCES {$referencedTable}({$referencedColumn}) 
                    ON DELETE {$onDelete}
                ");
            }
        } catch (\Exception $e) {
            \Log::error("Error updating foreign key constraint: " . $e->getMessage());
        }
    }
};
