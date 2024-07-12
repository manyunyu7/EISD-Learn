<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Http\Middleware\Student;
use App\Models\Absensi;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{

    // Replace 'your_secret_key' with a strong and secure key
    private $key = 'yepow';

    public function insertAbsensiMobile(Request $request)
    {
        $userId = $request->lms_user_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $sectionId = $this->decryptData($request->qr_content);


        $section = CourseSection::findOrFail($sectionId);


        if ($section == null) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Section Tidak Ditemukan",
                "Class Section Not Found",
                $sectionId
            );
        }

        if ($section->enable_absensi == "n" || $section->enable_absensi != null && $section->enable_absensi != "y") {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Tidak Dapat Melakukan Absensi Diluar Waktu Yang Telah Ditentukan",
                "Presensi Not Activated",
                $sectionId
            );
        }

        $absensi = new Absensi();
        $absensi->student_id = $userId;
        $absensi->latitude = $latitude;
        $absensi->longitude = $longitude;
        $absensi->section_id = $sectionId;

        // Check if the combination of student_id and section_id already exists
        $existingAbsensi = Absensi::where('student_id', $userId)
            ->where('section_id', $sectionId)
            ->exists();

        if ($existingAbsensi) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Absensi untuk siswa dan kelas ini sudah ada.",
                "Duplicate Absensi",
                $sectionId
            );
        }


        if ($absensi->save()) {
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "Absensi Berhasil Disimpan!",
                "Success",
                $absensi
            );
        } else {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Absensi Gagal Disimpan : ",
                "Gagal Mendaftar Kelas",
                $sectionId
            );
        }
    }

    public function updateAbsensi(Request $request)
    {
        $sectionId = $request->section_id;
        $enabled = $request->enabled;

        $section = CourseSection::find($sectionId);

        if (!$section) {
            // Handle if section not found
            return response()->json(['error' => 'Section not found'], 404);
        }

        $section->enable_absensi = $enabled ? 'y' : 'n';
        $section->save();

        // Return updated state of enable_absensi
        return response()->json(['enabled' => $section->enable_absensi == 'y', 'message' => 'Absensi updated successfully']);
    }

    public function manage($lessonId, $sectionId)
    {
        $section = CourseSection::findOrFail($sectionId);
        $enableAbsensi = $section->enable_absensi;
        $course = Lesson::where("id", '=', $section->course_id)->first();

        $students = DB::table('absensis as a')
            ->select('u.id', 'u.name', 'u.department_id', 'a.created_at', 'u.location', 'u.contact')
            ->leftJoin('users as u', 'a.student_id', '=', 'u.id')
            ->get();

        foreach ($students as $item) {
            $departmentId = $item->department_id;
            $department = DB::connection('ithub')->selectOne("SELECT * FROM m_departments where id = '$departmentId'");
            $departmentName = "";
            if ($department != null) {
                $departmentName = $department->name;
            } else {
                $departmentName = "Tidak Ada Department";
            }
            $item->department = $departmentName;
        }


        $courseName = $course->course_title;
        $courseId = $course->id;
        $qrFormula = $this->encryptData($sectionId);
        return view('lessons.absensi.manage_absensi')->with(compact('students', 'sectionId', 'enableAbsensi', 'course', 'section', 'qrFormula'));
    }

    public function encryptData($data)
    {
        // Encrypt the data using AES encryption
        $encryptedData = $this->encryptAES($data, $this->key);

        return $encryptedData;
    }

    public function decryptData($data)
    {
        $encryptedData = $data;

        // Decrypt the data using AES decryption
        $decryptedData = $this->decryptAES($encryptedData, $this->key);
        return $decryptedData;
        return response()->json(['decrypted_data' => $decryptedData]);
    }

    private function encryptAES($data, $key)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    private function decryptAES($data, $key)
    {
        list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    }
}
