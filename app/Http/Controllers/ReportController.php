<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data Business Unit (Departments) dari koneksi ithub
        $businessUnits = DB::connection('ithub')
            ->table('m_unit_businesses')
            ->select('id', 'code', 'name')
            ->where('code', 'like', '%_NEW%')
            ->get();

        // 2. Ambil data Jabatan (Positions) dari koneksi ithub
        $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'code', 'name')
            ->get();

        // 3. Logika Generate Report
        $reports = null;
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $reports = $this->getReportData($request);
        }

        return view('report.generate_page', compact('businessUnits', 'positions', 'reports'));
    }

    private function getReportData(Request $request)
    {
        // Mengambil nilai dari request yang sudah divalidasi
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        
        // Asumsi parameter ke-3 dan ke-4 adalah filter tambahan (misal: user_id atau category)
        // Jika tidak ada di request, kirim NULL
        $positionName = $request->input('positionName', null); 
        $buName = $request->input('buName', null);
        $trainingType = $request->input('trainingType', null);
    
        // Memanggil procedure dengan array bindings (?)
        $query = DB::select('CALL sp_get_learning_report(?, ?, ?, ?)', [
            $startDate,
            $endDate,
            $positionName,
            $buName,
            $trainingType
        ]);
    
        return $query;
    }

    // Tambahkan fungsi baru untuk preview
    public function previewReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'positionName'   => 'nullable|string|max:255',
            'buName'   => 'nullable|string|max:255',
            'trainingType'   => 'nullable|string|max:255',
        ]);
    
        $reports = $this->getReportData($request);
    
        return view('report.preview_page', [
            'reports' => $reports,
            'request' => $request 
        ]);
    }
}