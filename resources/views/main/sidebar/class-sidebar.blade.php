{{-- SEGMENT SIDEBAR --}}
<div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large" onclick="w3_close()">Close &times;</button>
  <div class="card">
    <div class="card-body">
      {{-- TABEL 1 --}}
    <table>
      <tr>
        <td>
          <p>
              <span class="badge dynamic-badge" style=" border-radius: 0; font-size: 13px; font-weight: bold">{{ $data->course_category }}</span>
          </p>
        </td>
        <td>[NAMA MENTOR]</td>
        
      </tr>
      <tr>
        <td><h4><b>Learning Path</b></h4></td>
        <td>Presentase Progress</td>
      </tr>
      <tr >
        <td colspan="2">BAR PROGRESS</td>
      </tr>
    </table>
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

    {{-- TABEL 2 --}}
    <table>
      <tr>
        <td>Getting Started</td>
        <td>sections</td>
        <td>% Finish</td>
      </tr>
      <tr>
        <td colspan="3">Silabus</td>
      </tr>
    </table>

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


{{-- SEGMENT MATERI/EXAM --}}
<div id="main">
  <div class="w3-teal">
    <div class="row">
      <div class="col">
        <button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
      </div>
      <div class="col">
        <div class="w3-container">
          <h1 id="judulKelas">[JUDUL KELAS]</h1>
        </div>
      </div>
    </div>
</div>

<div class="w3-container">
    <div class="w3-container">
      <h2>[SUBJUDUL MATERI KELAS]</h2>

      <table>
        <tr>
          <td colspan="3"><h4>IMAGES</h4></td>
          <td>Numbers of Student Watching</td>
          <td>Last Updated Info</td>
        </tr>
      </table>

    </div>
</div>