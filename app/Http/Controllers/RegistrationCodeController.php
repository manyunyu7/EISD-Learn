<?php

namespace App\Http\Controllers;

use App\Models\RegistrationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationCodeController extends Controller
{
    //crud method with RegistrationCode model
    public function index()
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


        // Fetch data
        $datas = RegistrationCode::all()->map(function ($data) use ($departments, $locations, $positions) {
            // Map department name
            $data->department_name = $departments->get($data->department_id)->name ?? '-';
            // Map position name
            $data->position_name = $positions->get($data->position_id)->name ?? '-';
            // Parse location JSON and map location names
            $data->location_names = collect(json_decode($data->location, true))->map(function ($loc) use ($locations) {
                return $locations->get($loc['site_id'])->name ?? '-';
            });

            return $data;
        });


        $compact = compact(
            'datas'
        );

        return view('registration_code.manage')->with($compact);
    }

    public function create(Request $request)
    {

        $locations = DB::connection('ithub')
            ->table('m_unit_businesses')
            ->select('id', 'code', 'name')
            ->get();

        $compact = compact('locations');

        return view('registration_code.create')->with($compact);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'registration_code' => 'required|string|unique:registration_codes', // Replace 'your_table_name' with the actual name of your database table
            'notes' => 'nullable|string',
            'is_active' => 'required|in:y,n',
        ], [
            // Custom error messages in Bahasa Indonesia
            'registration_code.required' => 'Kode pendaftaran harus diisi.',
            'registration_code.string' => 'Kode pendaftaran harus berupa teks.',
            'registration_code.unique' => 'Kode pendaftaran harus unik. Kode ini sudah digunakan.',
            'notes.string' => 'Catatan harus berupa teks.',
            'department_id.required' => 'ID departemen harus diisi.',
            'department_id.uuid' => 'ID departemen tidak valid.',
            'position_id.required' => 'ID posisi harus diisi.',
            'position_id.uuid' => 'ID posisi tidak valid.',
            'location.required' => 'Lokasi harus diisi.',
            'location.uuid' => 'Lokasi tidak valid.',
            'is_active.required' => 'Status harus diisi.',
            'is_active.in' => 'Status harus berupa "y" atau "n".',
        ]);


        // Transform the location data
        $locationData = [
            ['site_id' => $request->location]
        ];

        if ($request->location == null || $request->location == "") {
            $locationData = null;
        }

        // Create a new instance of RegistrationCode
        $data = new RegistrationCode();

        // Assign the validated data to the model
        $data->registration_code = $request->registration_code;
        $data->notes = $request->notes;
        $data->department_id = $request->department_id;
        $data->position_id = $request->position_id;

        // Convert the location array to JSON string

        if ($request->location != null || $request->location != "") {
            $data->location = json_encode($locationData); // Convert to JSON string        $data->position_id = $request->position_id;
        }

        $data->is_active = $request->is_active;

        if ($data->save()) {
            //redirect dengan pesan sukses
            return redirect('registration-code-management')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            //redirect dengan pesan error
            return redirect('registration-code-management')->with(['error' => 'Data Gagal Disimpan']);
        }
    }


    public function show() {}

    public function edit($id)
    {
        // Find the registration code by ID
        $data = RegistrationCode::findOrFail($id);

        // Fetch locations, departments, and positions as needed
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


        // Return the view with the registration code data
        return view('registration_code.edit', compact('data', 'locations', 'departments', 'positions'));
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'registration_code' => 'required|string|unique:registration_codes,registration_code,' . $id,
            'notes' => 'nullable|string',
            'is_active' => 'required|in:y,n',
            'department_id' => 'required|uuid', // Make sure to validate department_id and position_id
            'position_id' => 'required|uuid',
            'location' => 'nullable|uuid', // Add validation rules based on your requirements
        ], [
            // Custom error messages in Bahasa Indonesia
            'registration_code.required' => 'Kode pendaftaran harus diisi.',
            'registration_code.string' => 'Kode pendaftaran harus berupa teks.',
            'registration_code.unique' => 'Kode pendaftaran harus unik. Kode ini sudah digunakan.',
            'notes.string' => 'Catatan harus berupa teks.',
            'department_id.required' => 'ID departemen harus diisi.',
            'department_id.uuid' => 'ID departemen tidak valid.',
            'position_id.required' => 'ID posisi harus diisi.',
            'position_id.uuid' => 'ID posisi tidak valid.',
            'location.required' => 'Lokasi harus diisi.',
            'location.uuid' => 'Lokasi tidak valid.',
            'is_active.required' => 'Status harus diisi.',
            'is_active.in' => 'Status harus berupa "y" atau "n".',
        ]);

        // Find the registration code by ID
        $data = RegistrationCode::findOrFail($id);

        // Assign the validated data to the model
        $data->registration_code = $request->registration_code;
        $data->notes = $request->notes;
        $data->department_id = $request->department_id;
        $data->position_id = $request->position_id;
        $data->is_active = $request->is_active;

        // Transform the location data
        $locationData = [
            ['site_id' => $request->location]
        ];

        if ($request->location == null || $request->location == "") {
            $locationData = null;
        } else {
            $data->location = json_encode($locationData);
        }

        if ($data->save()) {
            return redirect('registration-code-management')->with(['success' => 'Data Berhasil Diperbarui']);
        } else {
            return redirect('registration-code-management')->with(['error' => 'Data Gagal Diperbarui']);
        }
    }

    public function destroy() {}
}
