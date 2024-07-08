<!-- Sort by Dropdown -->
<div class="row mt-2 border-primary col-md-12">
    <div class="row page-inner col-md-12">
        <div class="col-md-4 mb-3">
            <p>Sort by:</p>
            <div class="btn-group">
                <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px"
                    id="sortBtn"><span id="currentSort">{{ request()->query('sort', 'Latest') }}</span></button>
                <button type="button" class="btn btnSort-custom" style="width: 40px" id="sortDropdownToggle"
                    onclick="toggleSortDropdown()">
                    <span>&#9662;</span>
                </button>
                <ul class="dropdown-menu" style="width: 100%;" id="sortDropdown">
                    <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Latest')">Latest</a>
                    </li>
                    <!-- <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Recommend')">Recommend</a></li> -->
                    <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Most Student')">Most
                            Student</a></li>
                </ul>
            </div>
        </div>

        <!-- Category Dropdown -->
        <div class="col-md-8 mb-3">
            <p>Category:</p>
            <div class="btn-group">
                <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px"
                    id="categoryBtn"><span
                        id="currentCategory">{{ request()->query('category', 'All Category') }}</span></button>
                <button type="button" class="btn btnSort-custom" style="width: 40px" id="categoryDropdownToggle"
                    onclick="toggleCategoryDropdown()">
                    <span>&#9662;</span>
                </button>
                <ul class="dropdown-menu" style="width: 100%;" id="categoryDropdown">
                    <li><a class="dropdown-item text-left" href="#"
                            onclick="changeCategoryText('All Category')">All Category</a></li>
                    @foreach ($lessonCategories as $category)
                        <li><a class="dropdown-item text-left" href="#"
                                onclick="changeCategoryText('{{ $category->name }}')">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize current selected filters
    var currentSort = '{{ request()->query('sort', 'Latest') }}';
    var currentCategory = '{{ request()->query('category', 'All Category') }}';

    // Update the button text with current selected filters
    document.getElementById('currentSort').innerText = currentSort;
    document.getElementById('currentCategory').innerText = currentCategory;

    // Function to change the text of the sort button
    function changeSortText(selectedTextSort) {
        currentSort = selectedTextSort;
        document.getElementById('currentSort').innerText = currentSort;
        closeDropdown('sortDropdown'); // Close the sort dropdown after selection
        reloadPageWithFilters(); // Reload the page with selected filters
    }

    // Function to change the text of the category button
    function changeCategoryText(selectedTextCategory) {
        currentCategory = selectedTextCategory;
        document.getElementById('currentCategory').innerText = currentCategory;
        closeDropdown('categoryDropdown'); // Close the category dropdown after selection
        reloadPageWithFilters(); // Reload the page with selected filters
    }

    // Function to toggle the visibility of the sort dropdown
    function toggleSortDropdown() {
        toggleDropdown('sortDropdown');
    }

    // Function to toggle the visibility of the category dropdown
    function toggleCategoryDropdown() {
        toggleDropdown('categoryDropdown');
    }

    // Function to toggle the visibility of a dropdown
    function toggleDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    }

    // Function to close the dropdown
    function closeDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        dropdown.style.display = 'none';
    }

    // Function to reload the page with selected filters
    function reloadPageWithFilters() {
        // Construct the URL with the selected filters
        var newUrl = window.location.pathname + '?sort=' + currentSort + '&category=' + currentCategory;
        window.location.href = newUrl;
    }
</script>
