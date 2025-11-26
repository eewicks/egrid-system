<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupRecoveryController extends Controller
{
    /**
     * Display the backup & recovery page
     */
    public function index()
    {
        return view('backup_recovery');
    }
}
