<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabaseBackupController extends Controller
{
    public function backup()
    {
        // Database credentials
        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        // Backup file path
        $date = now()->format('Y-m-d_H-i-s');
        $backupFileName = "file_{$date}.sql";
        $backupFilePath = public_path("mysql/backup/{$backupFileName}");

        // Create the backup directory if it doesn't exist
        $backupDir = dirname($backupFilePath);
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Command to dump the database and redirect stderr to stdout
        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > {$backupFilePath} 2>&1";

        // Execute the command
        exec($command, $output, $returnVar);

        // Check if the command was successful
        if ($returnVar !== 0) {
            return response()->json(['message' => 'Database backup failed', 'output' => implode("\n", $output)], 500);
        }

        // Generate public URL
        $publicUrl = url("mysql/backup/{$backupFileName}");

        return response()->json(['message' => 'Database backup completed', 'file' => $publicUrl], 200);
    }
}
