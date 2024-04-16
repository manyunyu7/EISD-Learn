<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Http\Middleware\Student;
use App\Models\Absensi;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{


    public function insertAbsensiMobile(Request $request){
        $userId = $request->lms_user_id;
        $sectionId = $request->section_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $section = CourseSection::find($sectionId);

        if($section==null){
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Section Tidak Ditemukan",
                "Class Section Not Found",
                null
            );
        }

        if($section->enable_absensi==false || $section->enable_absensi=="n"){
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Tidak Dapat Melakukan Absensi Diluar Waktu Yang Telah Ditentukan",
                "Presensi Not Activated",
                null
            );
        }

        $absensi = new Absensi();
        $absensi->user_id = $userId;
        $absensi->latitude=$latitude;
        $absensi->longitude=$longitude;
        $absensi->section_id=$sectionId;

        if($absensi->save()){
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "Absensi Berhasil Disimpan!",
                "Success",
                $absensi
            );
        }else{
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Absensi Gagal Disimpan : ",
                "Gagal Mendaftar Kelas",
                null
            );
        }
    }

    public function updateAbsensi(Request $request){
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

    public function manage($lessonId,$sectionId){
        $section = CourseSection::findOrFail($sectionId);
        $enableAbsensi = $section->enable_absensi;
        $course = Lesson::where("id",'=',$section->course_id)->first();
        $dayta = User::all();
        return view('lessons.absensi.manage_absensi')->with(compact('dayta','sectionId','enableAbsensi','course','section'));
    }
}
