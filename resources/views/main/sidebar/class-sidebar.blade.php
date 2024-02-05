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
            @foreach ($silabusClass as $item)
            <tr>
              <td colspan="3">
                <input type="checkbox" {{ $item->section_title == $sectionTable->section_title ? 'checked' : '' }}>
                {{ $item->section_title }}
              </td>
            </tr>
            @endforeach
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
          <h2>{{ $sectionTable->section_title }}</h2>
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
    <div class="w3-container">
        <br>
        <button type="button" class="btn success">Next</button>
    </div>
</div>
@empty
        {{-- Handle case where $myClasses is empty --}}
@endforelse