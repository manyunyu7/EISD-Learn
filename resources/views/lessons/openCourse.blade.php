@forelse ($classInfo as $data)
{{-- SEGMENT SIDEBAR --}}
<div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large btn-danger" onclick="w3_close()">Close &times;</button>
  <div class="card">
    <div class="card-body">
      <div class="w3-container">
        {{-- TABEL 1 --}}
        <table class="table1">
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
        <table class="table2">
            <tr>
                <td style="color: red"><h5><b>Getting Started</b></h5></td>
                <td>TOTAL sections</td>
                <td>% Finish</td>
            </tr>
            @forelse ($section as $item)
                <tr>
                    <td>
                        @if (isset($item) && isset($item->isTaken))
                          @php
                              $isCurrent = $item->isCurrent ?? false;
                          @endphp
                            @if ($item->isTaken && !$isCurrent)
                                <input type="checkbox" checked>
                            @elseif ($isCurrent)
                                <input type="checkbox" checked>
                            @else
                                <input type="checkbox" disabled>
                            @endif
                        @endif
                    </td>
                    <td colspan="3">
                        @if (isset($item) && isset($item->isTaken))
                            @php
                                $isCurrent = $item->isCurrent ?? false;
                            @endphp
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('course.openClass', [$item->lesson_id, $item->section_id]) }}">
                                        <p class="text-dark mb-0">{{ $item->section_title }}</p>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <li class="nav-item card p-1 bg-dark" style="margin-bottom: 6px !important">
                    <p style="margin-bottom: 0px !important">Belum Ada Materi di Kelas Ini</p>
                </li>
            @endforelse

        </table>
    </div>
    </div>
  </div>
</div>


  {{-- SEGMENT MATERI/EXAM --}}
  @forelse ($section_spec as $index => $sectionSpec)
    <div id="main">
      <div class="w3-teal">
        <div class="row">
          <div class="col">
            <button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
          </div>
          <div class="col">
            <div class="w3-container">
              <h1 id="judulKelas">{{ $sectionSpec->lessons_title }}</h1>
            </div>
          </div>
        </div>
    </div>

    <div class="w3-container">
        <div class="w3-container">
          <tr>
            <td>
                @if(isset($section_spec) && is_array($section_spec) && count($section_spec) > 0 && property_exists($section_spec[0], 'section_title'))
                    <h2>{{ $section_spec[0]->section_title }}</h2>
                @else
                    <p>Section title not available</p>
                @endif
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
            @if ($firstSectionId!=null)
              @if ($prevSectionId)
                  <a style="text-decoration: none" href="{{ route('course.openClass', [$sectionSpec->lesson_id, $prevSectionId]) }}" class="btn success">Prev</a>
              @endif
              
              @if ($nextSectionId !== null)
                  <a style="text-decoration: none" href="{{ route('course.openClass', [$sectionSpec->lesson_id, $nextSectionId]) }}" class="btn success">Next</a>
              @endif
        
            @endif
            
        </div>
    </div>
  @empty
  @endforelse
@empty
        {{-- Handle case where $myClasses is empty --}}
@endforelse