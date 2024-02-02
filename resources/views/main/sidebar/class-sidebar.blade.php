@forelse ($classInfo as $data)
{{-- SEGMENT SIDEBAR --}}
<div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large btn-danger" onclick="w3_close()">Close &times;</button>
  <div class="card">
    <div class="card-body">
      <div class="w3-container">
        {{-- TABEL 1 --}}
        <table>
          <tr>
            <td>
              <p>
                  <span class="badge dynamic-badge" style=" border-radius: 0; font-size: 13px; font-weight: bold">{{ $data->course_category }}</span>
              </p>
            </td>
            <td><h5>{{ $data->mentor_name }}</h5></td>
            
          </tr>
          <tr>
            <td><h4><b>Learning Path</b></h4></td>
            <td> [] % Completed</td>
          </tr>
          <tr >
            <td colspan="2">BAR PROGRESS</td>
          </tr>
        </table>
      </div>
      
      <div class="w3-container">
        {{-- TABEL 2 --}}
        <table>
            <tr>
                <td style="color: red"><h5><b>Getting Started</b></h5></td>
                <td>{{ $totalSections }} sections</td>
                <td>% Finish</td>
            </tr>
            @forelse ($silabusClass as $index => $dataSilabus)
                <tr>
                    <td colspan="3">
                        <input type="checkbox" id="checkbox{{ $index }}" 
                               onclick="myFunction({{ $index }})"
                               {{ $dataSilabus->is_completed ? 'checked disabled' : '' }}>
                        {{ $dataSilabus->section_title }}
                    </td>
                </tr>
            @empty
                {{-- Handle case where $silabusClass is empty --}}
            @endforelse
        </table>
    </div>
    
    <script>
        // Inisialisasi array untuk menyimpan status centang checkbox
        var checkboxStatus = [];
    
        // Inisialisasi status checkbox dari server
        @foreach ($silabusClass as $index => $dataSilabus)
            checkboxStatus[{{ $index }}] = {{ $dataSilabus->is_completed ? 'true' : 'false' }};
        @endforeach
    
        function myFunction(index) {
            // Mendapatkan checkbox berdasarkan index
            var checkbox = document.getElementById('checkbox' + index);
    
            // Memeriksa status checkbox
            if (!checkboxStatus[index]) {
                // Jika checkbox belum pernah dicentang, centang dan simpan status
                checkbox.checked = true;
                checkboxStatus[index] = true;
    
                // Mendapatkan checkbox berikutnya
                var nextCheckbox = document.getElementById('checkbox' + (index + 1));
    
                // Jika checkbox saat ini dicentang dan ada checkbox berikutnya, aktifkan dan dicentangkan
                if (nextCheckbox) {
                    nextCheckbox.disabled = false;
                    nextCheckbox.checked = true;
                }
            }
        }
    </script>
    
    

    <div class="w3-container">
      {{-- TABEL 3 --}}
      <table>
        <tr>
          <td colspan="3"><h4><b>Five top Achivers In This Class</b></h4></td>
        </tr>
        <tr>
          <td>Foto</td>
          <td >Nama Student</td>
          <td>Grade</td>
        </tr>
      </table>
    </div>
    </div>
  </div>
</div>


{{-- SEGMENT MATERI/EXAM --}}
<div id="main">
  <div class="w3-teal">
    <div class="row">
      <div class="col">
        <button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
      </div>
      <div class="col">
        <div class="w3-container">
          <h1 id="judulKelas">{{ $data->course_title }}</h1>
        </div>
      </div>
    </div>
</div>

<div class="w3-container">
    <div class="w3-container">
      <tr>
        <td>
            <h2>[SUB JUDUL]</h2>
        </td>
    </tr>

      <table>
        <tr>
          <td colspan="3"><h4>IMAGES</h4></td>
          <td>Numbers of Student Watching</td>
          <td>Last Updated Info</td>
        </tr>
      </table>

    </div>
</div>
@empty
        {{-- Handle case where $myClasses is empty --}}
@endforelse