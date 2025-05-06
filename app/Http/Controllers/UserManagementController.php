<?php

namespace App\Http\Controllers; // Make sure this matches

use App\Models\User;
use App\Models\Lesson;
use App\Models\StudentLesson;
use App\Models\CourseSection;
use App\Models\StudentSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use StudentSectionCompleted;


class UserManagementController extends Controller
{

        /**
     * Reset the user's password.
     */
    public function resetPassword($id)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Generate a new default password
            $newPassword = 'modern888'; // Replace with your default password logic if needed

            // Update the user's password (hash it before saving)
            $user->password = Hash::make($newPassword);
            $user->save();

            // Return JSON success response
            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully.',
                'new_password' => $newPassword // Optional: for debugging or admin display
            ]);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        // Fetch departments and locations
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->get()
            ->keyBy('id'); // Convert to associative array for quick lookup

            $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'name')
            ->get()
            ->keyBy('id'); // Convert to associative array for quick lookup


        $locations = DB::connection('ithub')
            ->table('m_unit_businesses')
            ->select('id', 'code', 'name')
            ->get()
            ->keyBy('id'); // Convert to associative array for quick lookup

        // Fetch users
        $users = User::all()->map(function ($user) use ($departments, $locations,$positions) {
            // Map department name
            $user->department_name = $departments->get($user->department_id)->name ?? 'Unknown';
            // Map position name
            $user->position_name = $positions->get($user->position_id)->name ?? 'Unknown';
            // Parse location JSON and map location names
            $user->location_names = collect(json_decode($user->location, true))->map(function ($loc) use ($locations) {
                return $locations->get($loc['site_id'])->name ?? 'Unknown';
            });

            return $user;
        });

        if ($request->dump == true) {
            return $users;
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {

        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->whereNull('deleted_at')
            ->where('code', 'like', '%_NEW%')
            ->get();

        return view('users.create')->with(compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            // Add validation rules for other fields as needed
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => bcrypt($request->password),
            'profile_url' => $request->profile_url,
            'role' => $request->role,
            'contact' => $request->contact,
            'jobs' => $request->jobs,
            'institute' => $request->institute,
            'mdln_username' => $request->mdln_username,
            'motto' => $request->motto,
            'remember_token' => $request->remember_token,
            'university' => $request->university,
            'major' => $request->major,
            'interest' => $request->interest,
            'cv' => $request->cv,
            'sub_department' => $request->sub_department,
            'location' => $request->location,
            'url_personal_website' => $request->url_personal_website,
            'url_facebook' => $request->url_facebook,
            'url_instagram' => $request->url_instagram,
            'url_linkedin' => $request->url_linkedin,
            'url_twitter' => $request->url_twitter,
            'url_whatsapp' => $request->url_whatsapp,
            'url_youtube' => $request->url_youtube,
            // 'department_id' => $request->department_id,
            // 'position_id' => $request->position_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->whereNull('deleted_at')
            ->where('code', 'like', '%_NEW%')
            ->get();

        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            // Add validation rules for other fields as needed
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            // 'password' => $request->password ? bcrypt($request->password) : $user->password,
            // 'profile_url' => $request->profile_url,
            'role' => $request->role,
            'contact' => $request->contact,
            'jobs' => $request->jobs,
            'institute' => $request->institute,
            // 'mdln_username' => $request->mdln_username,
            'motto' => $request->motto,
            'remember_token' => $request->remember_token,
            'university' => $request->university,
            'major' => $request->major,
            'interest' => $request->interest,
            'cv' => $request->cv,
            // 'sub_department' => $request->sub_department,
            // 'location' => $request->location,
            'url_personal_website' => $request->url_personal_website,
            'url_facebook' => $request->url_facebook,
            'url_instagram' => $request->url_instagram,
            'url_linkedin' => $request->url_linkedin,
            'url_twitter' => $request->url_twitter,
            'url_whatsapp' => $request->url_whatsapp,
            'url_youtube' => $request->url_youtube,
            // 'department_id' => $request->department_id,
            // 'position_id' => $request->position_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function show(){

    }

    public function exportExcel(Request $request)
    {
        // Fetch departments, positions, locations
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->get()
            ->keyBy('id');
    
        $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'name')
            ->get()
            ->keyBy('id');
    
        $locations = DB::connection('ithub')
            ->table('m_unit_businesses')
            ->select('id', 'code', 'name')
            ->get()
            ->keyBy('id');
    
        // Filter users if department is selected
        $usersQuery = User::query();
    
        if ($request->filled('department')) {
            $usersQuery->where('department_id', $request->department);
        }
    
        // Fetch and map user data
        $users = $usersQuery->get()->map(function ($user) use ($departments, $locations, $positions) {
            $user->department_name = $departments->get($user->department_id)->name ?? 'Unknown';
            $user->position_name = $positions->get($user->position_id)->name ?? 'Unknown';
            $user->location_names = collect(json_decode($user->location, true))->map(function ($loc) use ($locations) {
                return $locations->get($loc['site_id'])->name ?? 'Unknown';
            });
            return $user;
        });
    
        if ($request->dump == true) {
            return $users;
        }

    
        return view('users.export_view', compact('users', 'departments'));
    }


    public function showForm()
    {
        return view('users.import_excel');
    }
    

    public function importExcel(Request $request, User $user)
    {
        if($request->submit_type === 'preview'){
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls',
            ]);
    
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
    
            // Skip header row (index 0)
            $data = array_slice($rows, 1);
    
            $db_learning_user = User::select('id', 'name')->get();
    
            $results = [];
    
            foreach($data as $i => $row){
                $excel_id_learning = $row[0];
                $excel_name = $row[2];
                $excel_realta_code = $row[6];
                $excel_valid_name = $row[4];
                $excel_target = $row[7];
                // $match = $db_learning_user->firstWhere('id',  $excel_id_learning);
                // Ambil data user yang cocok berdasarkan ID
                $user = User::find($excel_id_learning);
                if($user){
                    $results[] = [
                        'id_learning' => $excel_id_learning,
                        'excel_name' => $excel_name,
                        'match' => $user ? $user->name : null,
                        'realta_code' => $excel_realta_code,
                        'valid_name' => $excel_valid_name,
                        'adj_target' => $excel_target
                    ];
                }
            }

            return view('users.excel_preview', compact('results'));

        }
        elseif($request->submit_type === 'process'){
            $results = unserialize(base64_decode($request->excel_data));

            foreach($results as $i => $row){
                $excel_id_learning = $row['id_learning'] ?? null;
                $excel_realta_code = $row['realta_code'] ?? null;
                $excel_name = $row['excel_name'] ?? null;
                $excel_valid_name = $row['valid_name'] ?? null;
                $excel_target = $row['adj_target'] ?? null;

                // Lakukan pengecekan null sebelum mencari user
                if ($excel_id_learning === null) {
                    continue; // Lewati baris ini jika ID tidak ada
                }

                // Ambil data user yang cocok berdasarkan ID
                $user = User::find($excel_id_learning);
                

                // Hanya proses jika user ditemukan di database
                if($user){
                    // Bandingkan dengan string 'TRUE' atau 'FALSE' (sesuaikan jika di Excel beda)
                    // Pertimbangkan case-insensitive dan trim spasi jika perlu
                    $target_upper = strtoupper(trim((string)$excel_target));

                    if($target_upper === 'TRUE'){
                        $user->realta_code = $excel_realta_code;
                        $user->save();
                    }
                    elseif($target_upper === 'FALSE'){
                        // Hanya update jika excel_valid_name tidak kosong/null
                        if (!empty($excel_valid_name)) {
                             $user->name = $excel_valid_name;
                        }
                        $user->realta_code = $excel_realta_code;
                        $user->save();
                    }
                     // else: apa yang terjadi jika adj_target bukan TRUE atau FALSE?
                }
                // else: apa yang terjadi jika user dengan ID tsb tidak ditemukan di DB?
            }
            // return redirect()->route('users.excel_preview', compact('results'))->with('success', 'Integrasi data berhasil!');
            return redirect()->route('users.import.form')->with('success', 'Integrasi data berhasil!');
        }
        return redirect()->back()->withErrors(['msg' => 'Tipe submit tidak valid.']);
    }

    public function showTable()
    {

        $users_learning = DB::connection('mysql')
            ->table('users')
            ->where(function ($query) {
                $query->whereNull('mdln_username')
                      ->orWhere('mdln_username', '');
            })
            ->select('id', 'name', 'mdln_username')
            ->get();   

        // Ambil semua mdln_username dari koneksi mysql/learning
        $mdlnUsernames = DB::connection('mysql')
            ->table('users')
            ->pluck('mdln_username')
            ->filter() // Menghapus null dan empty string
            ->toArray();

        // Ambil user dari itHub yang id-nya tidak ada di mdln_username
        $data_user_unclear = DB::connection('ithub')
            ->table('users')
            ->whereNotIn('id', $mdlnUsernames)
            ->select('id','name', 'email', 'is_active')
            ->get();


        
        return view('users.export_view_ithub', compact('data_user_unclear', 'users_learning'));
    }

    public function moreInforUser(Request $request, $userID){
        $user = User::findOrFail($userID);

        // $studentLessons = StudentLesson::with(['lessons.id'])->where('student_id', $userID)->get();
        $studentLessons = StudentLesson::where('student_id', $userID)->get();

        $progress_class = [];
        foreach($studentLessons as $itemLesson){
            // lakukan join dengan course_section menggunakan course_section.course_id = itemLesson->course_id kemudian join lagi dengan tabel tudent _section menggunakan course_section.id = student_section.section_id
            $lessonId = $itemLesson->lesson_id;
            $lessonTitle = Lesson::where('id', $lessonId)->value('course_title');
            $joinDate = Lesson::where('id', $lessonId)->value('created_at');
            // Ambil semua section dari lesson ini
            $sections = CourseSection::where('course_id', $lessonId)->get();
            $totalSections = $sections->count();
            $sectionIds = $sections->pluck('id');

            // Hitung berapa section yang diselesaikan oleh user
            $completedSections = StudentSection::where('student_id', $userID)->whereIn('section_id', $sectionIds)->count();

            $progress = $totalSections > 0 ? round(($completedSections / $totalSections) * 100, 2) : 0 ;

            $progress_class[] = [
                'lesson_id' => $lessonId,
                'lesson_title' => $lessonTitle,
                'join_date' => $joinDate,
                'progress' => $progress.'%',
                'completed' => $completedSections,
                'total' => $totalSections,
            ];

        }


        return view('users.more_info', compact('user', 'progress_class'));
    }
    
}
