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
        // Query dasar dengan join ke kategori
        $query = Lesson::query()
            ->select(
                'lessons.*',
                'course_categories.name as category_name'
            )
            ->join('course_categories', 'lessons.category_id', '=', 'course_categories.id')
            ->whereBetween('lessons.start_date', [$request->start_date, $request->end_date]);

        // Filter berdasarkan Jabatan (Position)
        // Karena di storeV2 menggunakan json_encode, gunakan whereJsonContains
        if ($request->filled('jabatan_id')) {
            $query->whereJsonContains('lessons.position_id', $request->jabatan_id);
        }

        // Filter berdasarkan Business Unit (Department)
        if ($request->filled('bu_id')) {
            $query->whereJsonContains('lessons.department_id', $request->bu_id);
        }

        // Filter berdasarkan Jenis Training (All, Online, Offline)
        if ($request->filled('training_type') && $request->training_type != 'All') {
            $query->where('lessons.training_type', $request->training_type);
        }

        return $query->orderBy('lessons.start_date', 'asc')->get();
    }


    // Tambahkan fungsi baru untuk preview
    public function previewReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Mengambil data dengan filter yang sama seperti sebelumnya
        $reports = $this->getReportData($request);

        // Kirim data ke view baru bernama preview_page.blade.php
        return view('report.preview_page', compact('reports', 'request'));
    }
}