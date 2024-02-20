@forelse ($classInfo as $data)
<br><br><br><br>
  <div class="inner-page" style="background-color: cyan">
    <div class="container-fluid">
      <div class="row">
        @forelse ($section_spec as $index => $sectionSpec)
        <div class="col-8 con-1" style="background-color: white; height:100vh">
            {{-- Segment View Modul --}}
            <div class="container-fluid">
                @if(Str::contains(Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video),'pdf'))
                    <iframe id="pdfIframe" onload="" src="{{url("/")."/library/viewerjs/src/#"}}{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}"
                            width="100%" height="550" allowfullscreen="" webkitallowfullscreen=""></iframe>

                    <!-- Add this single <script> tag to the body of your HTML document -->


                    <script>
                        // Listen for a message from the iframe
                        window.addEventListener('message', function(event) {
                            if (event.data === 'iframeLoaded') {
                                startTracking();
                            }
                        });

                        function startTracking() {
                            console.log('Tracking started.');

                            function getCurrentPage() {
                                var iframe = document.getElementById('pdfIframe');
                                var currentPage = iframe.contentWindow.document.querySelector('.toolbarField.pageNumber').value;
                                return parseInt(currentPage, 10);
                            }

                            function getTotalPages() {
                                var iframe = document.getElementById('pdfIframe');
                                var totalPages = iframe.contentWindow.document.querySelector('.toolbarLabel').textContent;
                                var match = totalPages.match(/of (\d+)/);
                                if (match && match[1]) {
                                    return parseInt(match[1], 10);
                                }
                                return 0;
                            }

                            function calculatePercentageCompletion() {
                                var currentPage = getCurrentPage();
                                var totalPages = getTotalPages();

                                if (totalPages === 0) {
                                    return 0;
                                }

                                return (currentPage / totalPages) * 100;
                            }

                            function updatePageInfo() {
                                var currentPage = getCurrentPage();
                                var totalPages = getTotalPages();
                                var percentageCompletion = calculatePercentageCompletion();

                                console.log('Current Page:', currentPage);
                                console.log('Total Pages:', totalPages);
                                console.log('Percentage Completion:', percentageCompletion + '%');
                            }

                            setInterval(updatePageInfo, 1000);
                        }
                    </script>
                @else
                    @php
                        $videoFormats = ['mp4', 'webm', 'ogg']; // Add more video formats as needed
                        $imageFormats = ['jpg', 'jpeg', 'png', 'gif']; // Add more image formats as needed
                        $fileExtension = pathinfo($sectionSpec->section_video, PATHINFO_EXTENSION);
                    @endphp

                    @if (in_array($fileExtension, $videoFormats))
                        <div class="container-fluid" id="videoContainer" style="max-height: auto; background-color:black">
                            <video crossorigin controls playsinline id="myVideo" autoplay="autoplay" width="100%" class="video-mask" disablePictureInPicture controlsList="nodownload">
                                <source src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}">
                            </video>
                        </div>
                        
                        <script>
                            var video = document.getElementById('myVideo');
                            video.addEventListener('loadedmetadata', function() {
                                var width = this.videoWidth;
                                var height = this.videoHeight;
                                if (width > height) {
                                    // Landscape
                                    document.getElementById('videoContainer').classList.add('landscape');
                                } else {
                                    // Portrait
                                    document.getElementById('videoContainer').classList.add('portrait');
                                }
                            });
                        </script>
                    
                    @elseif (in_array($fileExtension, $imageFormats))
                        <div class="container-fluid" style="background-color: black">
                            <img
                            class="fluid"
                            style="height: auto; width: 100%;"
                            src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}"
                            alt="Image">
                        </div>
                    @else

                    @endif
                @endif

                {{-- Segment Judl Modul --}}
                <h1>Segment Judul</h1>

                {{-- Segment Deskripsi --}}
                <h3><b>Deskripsi</b></h3>
                <p>Isi Deskripsi</p>

                {{-- Segment Button Navigation --}}
                @if ($firstSectionId!=null)
                    @if ($prevSectionId)
                        <button type="button" class="btn btn-success" onclick="prevFunction('{{ route('course.openClass', [$sectionSpec->lesson_id, $prevSectionId]) }}')">
                            Prev
                        </button>
                        <script>
                            function prevFunction(url) {
                                window.location.href = url;
                            }
                        </script>
                    @endif
                    
                    @if ($nextSectionId !== null)
                        <button type="button" class="btn btn-success" onclick="nextFunction('{{ route('course.openClass', [$sectionSpec->lesson_id, $nextSectionId]) }}')">
                            Next
                        </button>
                        <script>
                            function nextFunction(url) {
                                window.location.href = url;
                            }
                        </script>
                    @endif
                @endif
                @empty
                {{-- Handle case where $myClasses is empty --}}
                @endforelse
            </div>
        </div>
        



        {{-- SIDEBAR COURSE --}}
        <div class="col-4 con-2" style="background-color: white">
          <div class="container">
            {{-- TABEL 1 --}}
            <table class="table table1">
                <tr >
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
                    <td> <b>{{ $progressPercentage }} % Completed</b></td>
                </tr>
                <tr >
                    <td colspan="2">
                      <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </td>
                </tr>
            </table>
          </div>

          <div class="container">
            {{-- TABEL 2 --}}
            <table class="table table2 table-striped">
                <thead class="table-dark align-middle" >
                    <td class="align-middle" style="color: red"><h5><b>Getting Started</b></h5></td>
                    <td class="align-middle">{{ $totalSections }} sections</td>
                    <td class="align-middle pull-right">{{ $progressPercentage }}% Finish ({{ $total_hasTaken }}/{{ $totalSections }})</td>
                </thead>
                <tbody>
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
                                        <div class="align-middle">
                                            @if (isset($item) && isset($item->isTaken))
                                                @php
                                                    $isCurrent = $item->isCurrent ?? false;
                                                @endphp
                                                <a class="align-middle" style="text-decoration: none" href="{{ route('course.openClass', [$item->lesson_id, $item->section_id]) }}">
                                                    <p class="text-dark">{{ $item->section_title }}</p>
                                                </a>
                                            @endif
                                        </div>
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
                                        <div class="align-middle">
                                            @if (isset($item) && isset($item->isTaken))
                                                @php
                                                    $isCurrent = $item->isCurrent ?? false;
                                                @endphp
                                                <a class="align-middle" style="text-decoration: none" href="{{ route('course.openClass', [$item->lesson_id, $item->section_id]) }}">
                                                    <p class="text-dark">{{ $item->section_title }}</p>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @empty
                        <li class="nav-item card p-1 bg-dark" style="margin-bottom: 6px !important">
                            <p style="margin-bottom: 0px !important">Belum Ada Materi di Kelas Ini</p>
                        </li>
                @endforelse

                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @empty
        {{-- Handle case where $myClasses is empty --}}
  @endforelse