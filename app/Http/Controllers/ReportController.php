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
        // 1. Ambil Data Master dari koneksi 'ithub'
        // Gunakan 'nip' sebagai key jika mdln_username berisi NIP karyawan
        $employees = DB::connection('ithub')->table('u_employees')
            ->select('user_id', 'nip', 'gender', 'department_id', 'position_id', 'job_title')
            ->get()
            ->keyBy('nip'); // <--- DISESUAIKAN: Menggunakan nip agar cocok dengan mdln_username
    
        $departments = DB::connection('ithub')->table('m_departments')->pluck('name', 'id');
        $positions = DB::connection('ithub')->table('m_positions')->pluck('name', 'id');
        $unitBusinesses = DB::connection('ithub')->table('m_unit_businesses')->pluck('name', 'id');
    
        // 2. Query Utama (Learning)
        $query = DB::table('lessons')
            ->join('student_lesson', 'lessons.id', '=', 'student_lesson.lesson_id')
            ->join('users', 'student_lesson.student_id', '=', 'users.id')
            ->leftJoin('lesson_categories', 'lessons.category_id', '=', 'lesson_categories.id') 
            ->select(
                'lessons.id as lesson_id',
                'lessons.start_date',
                'lessons.course_title',
                'lessons.vendor',
                'lessons.location',
                'lessons.duration',
                'lessons.proposed_budget',
                'lessons.actual_budget',
                'lessons.target_audience',
                'lessons.training_type',
                'users.name as student_name',
                'users.mdln_username', // Ini adalah trigger matching
                'student_lesson.learn_status'
                // 'student_lesson.absence_reason' // Aktifkan jika kolom ini ada di table student_lesson
            )
            ->whereNull('lessons.deleted_at')
            ->whereBetween('lessons.start_date', [$request->start_date, $request->end_date])
            ->where('users.role', 'student');
    
        if ($request->filled('training_type') && $request->training_type != 'All') {
            $query->where('lessons.training_type', $request->training_type);
        }
    
        $rawData = $query->orderBy('lessons.start_date', 'asc')->get();
    
        // 3. Mapping Data
        $reports = $rawData->map(function ($item, $key) use ($employees, $departments, $positions, $unitBusinesses) {
            // Ambil data dari koleksi employees berdasarkan mdln_username
            $emp = $employees->get($item->mdln_username);
    
            $actual_peserta = $item->learn_status == 1 ? 1 : 0;
            $persentase = $item->target_audience > 0 ? ($actual_peserta / $item->target_audience) * 100 : 0;
    
            return [
                'no' => $key + 1,
                'start_date' => $item->start_date,
                'nama' => $item->student_name,
                // SOLUSI NIP: Ambil dari $emp (DB ITHUB) atau fallback ke mdln_username (DB Learning)
                'nip' => $emp->nip ?? $item->mdln_username, 
                'jenis_kelamin' => $emp->gender ?? '-',
                'posisi' => $positions[$emp->position_id ?? null] ?? '-',
                'department' => $departments[$emp->department_id ?? null] ?? '-',
                'jabatan' => $emp->job_title ?? '-',
                'unit_bisnis' => $unitBusinesses[$emp->unit_business_id ?? null] ?? '-',
                'judul_training' => $item->course_title,
                'vendor' => $item->vendor ?? '-',
                'lokasi' => $item->location,
                'durasi' => $item->duration,
                'budget_awal' => $item->proposed_budget,
                'budget_real' => $item->actual_budget,
                'ket_absen' => $item->absence_reason ?? '-',
                'target_peserta' => $item->target_audience,
                'actual_peserta' => $actual_peserta,
                'persentase' => number_format($persentase, 0) . '%',
                'progress' => $item->learn_status == 1 ? 'Finished' : 'In Progress',
                'mode' => $item->training_type
            ];
        });
    
        return $reports;
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