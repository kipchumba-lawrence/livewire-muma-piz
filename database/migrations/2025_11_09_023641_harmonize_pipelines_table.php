<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration harmonizes the pipelines table by:
     * - Standardizing all collations to utf8mb4_unicode_ci
     * - Ensuring consistent defaults
     * - Adding missing fields (makeup, hair, outfit, redit, reditails)
     * - Fixing field types and lengths (makeup, hair, outfit to VARCHAR(255))
     */
    public function up(): void
    {
        // Add makeup, hair, and outfit columns if they don't exist
        // Using raw SQL to ensure they're created even if ->after() fails
        if (!Schema::hasColumn('pipelines', 'makeup')) {
            DB::statement('ALTER TABLE pipelines ADD COLUMN makeup VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER note');
        }
        
        if (!Schema::hasColumn('pipelines', 'hair')) {
            // Check if makeup exists to place after it, otherwise after note
            if (Schema::hasColumn('pipelines', 'makeup')) {
                DB::statement('ALTER TABLE pipelines ADD COLUMN hair VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER makeup');
            } else {
                DB::statement('ALTER TABLE pipelines ADD COLUMN hair VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER note');
            }
        }
        
        if (!Schema::hasColumn('pipelines', 'outfit')) {
            // Check if hair exists to place after it, otherwise after makeup, or after note
            if (Schema::hasColumn('pipelines', 'hair')) {
                DB::statement('ALTER TABLE pipelines ADD COLUMN outfit VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER hair');
            } elseif (Schema::hasColumn('pipelines', 'makeup')) {
                DB::statement('ALTER TABLE pipelines ADD COLUMN outfit VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER makeup');
            } else {
                DB::statement('ALTER TABLE pipelines ADD COLUMN outfit VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER note');
            }
        }
        
        // Add redit and reditails if they don't exist
        if (!Schema::hasColumn('pipelines', 'redit')) {
            // Try to add after outfit, otherwise just add at end
            if (Schema::hasColumn('pipelines', 'outfit')) {
                DB::statement('ALTER TABLE pipelines ADD COLUMN redit INT NULL DEFAULT 0 AFTER outfit');
            } else {
                DB::statement('ALTER TABLE pipelines ADD COLUMN redit INT NULL DEFAULT 0');
            }
        }
        
        if (!Schema::hasColumn('pipelines', 'reditails')) {
            // Add after redit if it exists, otherwise after outfit, or at end
            if (Schema::hasColumn('pipelines', 'redit')) {
                DB::statement('ALTER TABLE pipelines ADD COLUMN reditails VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER redit');
            } elseif (Schema::hasColumn('pipelines', 'outfit')) {
                DB::statement('ALTER TABLE pipelines ADD COLUMN reditails VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER outfit');
            } else {
                DB::statement('ALTER TABLE pipelines ADD COLUMN reditails VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
            }
        }

        // Use raw SQL to standardize collations and ensure proper defaults
        // Only modify columns that exist to avoid errors
        
        // Build the ALTER statements dynamically based on existing columns
        $columnsToModify = [];
        
        // String fields with collation standardization
        $stringColumns = [
            'customer_name' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL',
            'booked_time' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL',
            'package' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'venue' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "indoor"',
            'phone' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL',
            'editor' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'editing_status' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "pending"',
            'shoot_status' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "pending"',
            'numberofpix' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'export_link' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'payment_status' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "pending"',
            'email' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'pipeline_status' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "pending"',
            'makeup' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'hair' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
            'outfit' => 'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL',
        ];
        
        // Only add columns that exist
        foreach ($stringColumns as $column => $definition) {
            if (Schema::hasColumn('pipelines', $column)) {
                $columnsToModify[] = "MODIFY COLUMN {$column} {$definition}";
            }
        }
        
        // Add optional columns if they exist
        if (Schema::hasColumn('pipelines', 'merchant_request_id')) {
            $columnsToModify[] = 'MODIFY COLUMN merchant_request_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL';
        }
        
        if (Schema::hasColumn('pipelines', 'checkout_request_id')) {
            $columnsToModify[] = 'MODIFY COLUMN checkout_request_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL';
        }
        
        if (Schema::hasColumn('pipelines', 'reditails')) {
            $columnsToModify[] = 'MODIFY COLUMN reditails VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL';
        }
        
        // Execute string column modifications
        if (!empty($columnsToModify)) {
            DB::statement('ALTER TABLE pipelines ' . implode(', ', $columnsToModify));
        }
        
        // Text fields
        $textColumns = [];
        if (Schema::hasColumn('pipelines', 'note')) {
            $textColumns[] = 'MODIFY COLUMN note TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL';
        }
        if (Schema::hasColumn('pipelines', 'image_collection')) {
            $textColumns[] = 'MODIFY COLUMN image_collection TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL';
        }
        
        if (!empty($textColumns)) {
            DB::statement('ALTER TABLE pipelines ' . implode(', ', $textColumns));
        }
        
        // Integer fields
        $intColumns = [];
        if (Schema::hasColumn('pipelines', 'total_amount')) {
            $intColumns[] = 'MODIFY COLUMN total_amount INT NULL';
        }
        if (Schema::hasColumn('pipelines', 'paid_amount')) {
            $intColumns[] = 'MODIFY COLUMN paid_amount INT NULL';
        }
        if (Schema::hasColumn('pipelines', 'redit')) {
            $intColumns[] = 'MODIFY COLUMN redit INT NULL DEFAULT 0';
        }
        
        if (!empty($intColumns)) {
            DB::statement('ALTER TABLE pipelines ' . implode(', ', $intColumns));
        }

        // Note: Defaults are already set in the ALTER TABLE statement above
        // The Schema builder's ->change() method requires doctrine/dbal package
        // Using raw SQL ensures compatibility without additional dependencies
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reverting collation changes is complex and may not be necessary
        // This migration is primarily for harmonization, so rollback is optional
        // We only remove columns that were added by this migration
        
        Schema::table('pipelines', function (Blueprint $table) {
            // Remove fields if they were added by this migration
            // Note: We don't remove makeup, hair, outfit as they may have been added manually
            // and are needed by the application
            
            if (Schema::hasColumn('pipelines', 'reditails')) {
                $table->dropColumn('reditails');
            }
            
            if (Schema::hasColumn('pipelines', 'redit')) {
                $table->dropColumn('redit');
            }
        });
    }
};
