<div class="row mt-2 border-primary col-md-12">
    <div class="row page-inner col-md-12">
        
        <div class="col-md-4 mb-3">
            <p>Sort by:</p>
            <div class="btn-group">
                <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px" id="sortBtn">
                    <span id="currentSort">{{ request()->query('sort', 'Latest') }}</span>
                </button>
                <button type="button" class="btn btnSort-custom" style="width: 40px" id="sortDropdownToggle" onclick="toggleDropdown('sortDropdown')">
                    <span>&#9662;</span>
                </button>
                <ul class="dropdown-menu" style="width: 100%;" id="sortDropdown">
                    <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Latest')">Latest</a></li>
                    <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Most Student')">Most Student</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <p>Category:</p>
            <div class="btn-group">
                <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px" id="categoryBtn">
                    <span id="currentCategory">{{ request()->query('category', 'All Category') }}</span>
                </button>
                <button type="button" class="btn btnSort-custom" style="width: 40px" id="categoryDropdownToggle" onclick="toggleDropdown('categoryDropdown')">
                    <span>&#9662;</span>
                </button>
                <ul class="dropdown-menu" style="width: 100%;" id="categoryDropdown">
                    <li><a class="dropdown-item text-left" href="#" onclick="changeCategoryText('All Category')">All Category</a></li>
                    @foreach ($lessonCategories as $category)
                        <li><a class="dropdown-item text-left" href="#" onclick="changeCategoryText('{{ $category->name }}')">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <p>Training Type:</p>
            <div class="btn-group">
                <button type="button" class="btn btnSort-custom" style="padding-right: 130px; width: 200px" id="trainingTypeBtn">
                    <span id="currentTrainingType">
                        @php
                            $tt = request()->query('training_type');
                            echo $tt == 'Online' ? 'Online' : ($tt == 'Offline' ? 'Offline' : 'All Type');
                        @endphp
                    </span>
                </button>
                <button type="button" class="btn btnSort-custom" style="width: 40px" id="ttDropdownToggle" onclick="toggleDropdown('trainingTypeDropdown')">
                    <span>&#9662;</span>
                </button>
                <ul class="dropdown-menu" style="width: 100%;" id="trainingTypeDropdown">
                    <li><a class="dropdown-item text-left" href="#" onclick="changeTrainingTypeText('')">All Type</a></li>
                    <li><a class="dropdown-item text-left" href="#" onclick="changeTrainingTypeText('Online')">Online</a></li>
                    <li><a class="dropdown-item text-left" href="#" onclick="changeTrainingTypeText('Offline')">Offline</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script>
    // Initialize current selected filters from URL or default
    var currentSort = '{{ request()->query('sort', 'Latest') }}';
    var currentCategory = '{{ request()->query('category', 'All Category') }}';
    var currentTrainingType = '{{ request()->query('training_type', '') }}';
    var query = '{{ request()->query('q', '') }}';

    // Update UI labels
    document.getElementById('currentSort').innerText = currentSort;
    document.getElementById('currentCategory').innerText = currentCategory;
    
    // Set initial Training Type label
    if(currentTrainingType !== "") {
        document.getElementById('currentTrainingType').innerText = currentTrainingType;
    }

    // --- Filter Functions ---

    function changeSortText(val) {
        currentSort = val;
        reloadPageWithFilters();
    }

    function changeCategoryText(val) {
        currentCategory = val;
        reloadPageWithFilters();
    }

    function changeTrainingTypeText(val) {
        currentTrainingType = val;
        reloadPageWithFilters();
    }

    // --- UI Helper Functions ---

    function toggleDropdown(dropdownId) {
        // Close all other dropdowns first for proper UX
        const dropdowns = ['sortDropdown', 'categoryDropdown', 'trainingTypeDropdown'];
        dropdowns.forEach(id => {
            if(id !== dropdownId) {
                document.getElementById(id).style.display = 'none';
            }
        });

        var dropdown = document.getElementById(dropdownId);
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }

    // Close dropdowns if user clicks outside
    window.onclick = function(event) {
        if (!event.target.matches('.btnSort-custom') && !event.target.matches('.btnSort-custom span')) {
            const dropdowns = ['sortDropdown', 'categoryDropdown', 'trainingTypeDropdown'];
            dropdowns.forEach(id => {
                document.getElementById(id).style.display = 'none';
            });
        }
    }

    function reloadPageWithFilters() {
        // Menggunakan URLSearchParams agar lebih rapi dan aman
        const params = new URLSearchParams();
        params.append('sort', currentSort);
        params.append('category', currentCategory);
        if (currentTrainingType !== "") params.append('training_type', currentTrainingType);
        if (query !== "") params.append('q', query);

        window.location.href = window.location.pathname + '?' + params.toString();
    }
</script>