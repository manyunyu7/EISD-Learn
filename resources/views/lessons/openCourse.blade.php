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
                <span class="w3-badge dynamic-badge" style="border-radius: 0; font-weight: bold">{{ $data->course_category }}</span>
              </p>
            </td>
            <script>
              var badges = document.querySelectorAll('.dynamic-badge');
          
              badges.forEach(function (badge) {
                  var selectedCategory = badge.textContent;
                  var badgeColor, textColor;
          
                  switch (selectedCategory) {
                      case 'Management Trainee':
                          badgeColor = '#f7c8ca';
                          textColor = '#D02025';
                          break;
                      case 'General':
                          badgeColor = 'blue';
                          break;
                      case 'Design':
                          badgeColor = 'green';
                          break;
                      case 'Finance & Accounting':
                          badgeColor = 'purple';
                          break;
                      case 'Human Resource and Development':
                          badgeColor = 'orange';
                          break;
                      case '3D Modelling':
                          badgeColor = 'pink';
                          break;
                      case 'Digital Management':
                          badgeColor = '#EBEBFF';
                          textColor = '#342F98';
                          break;
                      case 'Marketing and Business':
                          badgeColor = 'yellow';
                          break;
                      case 'Food and Beverage':
                          badgeColor = 'brown';
                          break;
                      case 'Management':
                          badgeColor = 'teal';
                          break;
                      case 'Social and Politics':
                          badgeColor = 'indigo';
                          break;
                      case 'Office':
                          badgeColor = 'maroon';
                          break;
                      case 'Outdoor Activity':
                          badgeColor = 'lime';
                          break;
                      case 'Junior High School':
                          badgeColor = 'navy';
                          break;
                      case 'Senior High School':
                          badgeColor = 'olive';
                          break;
          
                      default:
                          badgeColor = 'gray';
                  }
          
                  badge.style.backgroundColor = badgeColor;
                  badge.style.color = textColor; // Set text color to white
              });
            </script>
            <td>
              <h5>
                {{ $data->mentor_name }}
              </h5>
            </td>
          </tr>
          <tr>
            <td><h4><b>Learning Path</b></h4></td>
            <td> [] % Completed</td>
          </tr>
          <tr >
            <td colspan="2">
              <div style="width: 100%; background-color: #ddd;">
                <div  style="width: {{ $progressPercentage }}% ;height: 30px; background-color: #04AA6D; text-align: center; line-height: 30px; color: white;">
                  {{ $progressPercentage }}%
                </div>
              </div>

            </td>
          </tr>
        </table>
      </div>
      
      <div class="w3-container">
        {{-- TABEL 2 --}}
        <table class="table2">
            <tr>
                <td class="align-middle" style="color: red"><h5><b>Getting Started</b></h5></td>
                <td class="align-middle" >{{ $totalSections }} sections</td>
                <td class="align-middle">{{ $progressPercentage }}% Finish</td>
            </tr>
            @forelse ($sections as $item)
                @if (isset($item) && isset($item->isTaken))
                  @php
                      $isCurrent = $item->isCurrent ?? false;
                  @endphp
                    @if($isCurrent)
                      <tr style="background-color: #f7ada5">
                          <td class="align-middle">
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
                          <td class="align-middle" colspan="3">
                              @if (isset($item) && isset($item->isTaken))
                                  @php
                                      $isCurrent = $item->isCurrent ?? false;
                                  @endphp
                                  <a style="text-decoration: none" href="{{ route('course.openClass', [$item->lesson_id, $item->section_id]) }}">
                                    <p class="text-dark">{{ $item->section_title }}</p>
                                  </a>
                              @endif
                          </td>
                      </tr>
                    @else
                    <tr>
                      <td class="align-middle">
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
                      <td class="align-middle" colspan="3">
                          @if (isset($item) && isset($item->isTaken))
                              @php
                                  $isCurrent = $item->isCurrent ?? false;
                              @endphp
                              <a style="text-decoration: none" href="{{ route('course.openClass', [$item->lesson_id, $item->section_id]) }}">
                                <p class="text-dark">{{ $item->section_title }}</p>
                              </a>
                          @endif
                      </td>
                  </tr>
                    @endif
                @endif
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