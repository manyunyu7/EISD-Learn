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
        // Menggunakan Model Lesson dengan relasi category
        $query = Lesson::with('category') 
            ->whereBetween('start_date', [$request->start_date, $request->end_date]);
    
        // Filter Jabatan (Position) - Menggunakan kolom position_id (JSON)
        if ($request->filled('jabatan_id')) {
            $query->whereJsonContains('position_id', $request->jabatan_id);
        }
    
        // Filter Business Unit (Department) - Menggunakan kolom department_id (JSON)
        if ($request->filled('bu_id')) {
            $query->whereJsonContains('department_id', $request->bu_id);
        }
    
        // Filter Jenis Training
        if ($request->filled('training_type') && $request->training_type != 'All') {
            $query->where('training_type', $request->training_type);
        }
    
        return $query->orderBy('start_date', 'asc')->get();
    }


    // Tambahkan fungsi baru untuk preview
    public function previewReport(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // Opsional: Jika ingin debugging di sisi server (muncul di network tab browser)
        // return response()->json($request->all());

        // Mengambil data report berdasarkan filter
        $reports = $this->getReportData($request);

        // Kirim data ke view preview_page.blade.php
        return view('report.preview_page', [
            'reports' => $reports,
            'request' => $request // Membawa kembali inputan untuk ditampilkan di label preview
        ]);
    }
}