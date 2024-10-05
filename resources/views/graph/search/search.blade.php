<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Search</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Styles for list view */
        .list-view .table th,
        .list-view .table td {
            vertical-align: middle; /* Center content vertically */
        }

        /* Styles for images in list view */
        .list-view img {
            max-width: 50px; /* Smaller image size */
            margin-right: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Search Documents</h2>
        <form id="searchForm">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="query" placeholder="Search for documents..." required>
                <select class="form-select" id="location">
                    <option value="onedrive">OneDrive</option>
                    <option value="sharepoint">SharePoint</option>
                    <option value="folder">Folder</option>
                </select>
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Mode Toggle Buttons -->
        <div class="mb-3">
            <button id="listViewBtn" class="btn btn-secondary">List View</button>
            <button id="gridViewBtn" class="btn btn-secondary">Grid View</button>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="text-center" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="sr-only d-none">Loading...</span>
            </div>
            <p>Loading results...</p>
        </div>

        <div id="results" class="mt-4"></div>
    </div>

    <script>
        // Default view mode
        let currentViewMode = 'grid'; // Default to grid view
        let currentResults = []; // Store search results

        // Use fetch to send data to the backend
        document.getElementById('searchForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            let query = document.getElementById('query').value;
            let location = document.getElementById('location').value;

            // Show loading indicator
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').innerHTML = ''; // Clear previous results

            try {
                let response = await fetch('/365/search-documents', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        query: query,
                        location: location
                    })
                });

                let result = await response.json();

                // Check for successful response
                if (response.ok) {
                    currentResults = result.value; // Store results in currentResults
                    sortResultsByDate(currentResults); // Sort results by date
                    displayResults(currentResults, query); // Pass query for highlighting
                } else {
                    throw new Error(result.error || 'Error fetching results');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('results').innerHTML =
                    `<div class="alert alert-danger">Error: ${error.message}</div>`;
            } finally {
                // Hide loading indicator
                document.getElementById('loading').style.display = 'none';
            }
        });

        // Function to sort results by last modified date
        function sortResultsByDate(results) {
            results.sort((a, b) => {
                const aLastModified = new Date(a.hitsContainers[0].hits[0].resource.lastModifiedDateTime);
                const bLastModified = new Date(b.hitsContainers[0].hits[0].resource.lastModifiedDateTime);
                return bLastModified - aLastModified; // Sort in descending order
            });
        }

        // Display results based on the current view mode
        function displayResults(result, query) {
            let output = '';
            if (result && result.length > 0) {
                if (currentViewMode === 'grid') {
                    // Grid view
                    output += '<div class="row">';
                    result.forEach(item => {
                        item.hitsContainers.forEach(container => {
                            container.hits.forEach(hit => {
                                const highlights = hit.highlights ? hit.highlights.join('...') : 'No snippet available';
                                const summary = highlightQueryWords(hit.summary, query); // Highlight query words in summary

                                // Constructing URL
                                const baseUrl = window.location.origin; // Get current URL base
                                const driveId = hit.resource.parentReference.driveId; // Get drive ID
                                const itemId = hit.resource.parentReference.id; // Get item ID
                                const fileName = hit.resource.name; // Get file name
                                const fileUrl = `${baseUrl}/365/all-memories?driveId=${driveId}&itemId=${itemId}&fileName=${fileName}`;

                                output += `
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="${getFileTypeImage(hit.resource.name)}" class="card-img-top" alt="${hit.resource.name}">
                                        <div class="card-body">
                                            <h5 class="card-title">${hit.resource.name}</h5>
                                            <p class="card-text">Created by: ${hit.resource.createdBy.user.displayName}</p>
                                            <p class="card-text">Last modified: ${new Date(hit.resource.lastModifiedDateTime).toLocaleString()}</p>
                                            <p class="card-text"><strong>Snippet:</strong> ${highlights}</p>
                                            <p class="card-text"><strong>Summary:</strong> ${summary}</p> <!-- Display summary -->
                                            <a href="${fileUrl}" class="btn btn-primary" target="_blank">Open Document</a>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                        });
                    });
                    output += '</div>';
                } else {
                    // List view - Using a responsive table
                    output += `
                    <div class="table-responsive">
                        <table class="table table-striped list-view">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Created By</th>
                                    <th>Last Modified</th>
                                    <th>Snippet</th>
                                    <th>Summary</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>`;
                    result.forEach(item => {
                        item.hitsContainers.forEach(container => {
                            container.hits.forEach(hit => {
                                const highlights = hit.highlights ? hit.highlights.join('...') : 'No snippet available';
                                const summary = highlightQueryWords(hit.summary, query); // Highlight query words in summary

                                // Constructing URL
                                const baseUrl = window.location.origin; // Get current URL base
                                const driveId = hit.resource.parentReference.driveId; // Get drive ID
                                const itemId = hit.resource.parentReference.itemId; // Get item ID
                                const fileName = hit.resource.name; // Get file name
                                const fileUrl = `${baseUrl}/365/all-memories?driveId=${driveId}&itemId=${itemId}&filename=${fileName}`;

                                output += `
                                <tr>
                                    <td>
                                        <img src="${getFileTypeImage(hit.resource.name)}" alt="${hit.resource.name}" class="img-thumbnail" style="width: 50px;">
                                        ${hit.resource.name}
                                    </td>
                                    <td>${hit.resource.createdBy.user.displayName}</td>
                                    <td>${new Date(hit.resource.lastModifiedDateTime).toLocaleString()}</td>
                                    <td>${highlights}</td>
                                    <td>${summary}</td>
                                    <td><a href="${fileUrl}" class="btn btn-primary btn-sm" target="_blank">Open</a></td>
                                </tr>
                                `;
                            });
                        });
                    });
                    output += `</tbody></table></div>`;
                }
            } else {
                output = '<div class="col-12"><p>No results found.</p></div>';
            }
            document.getElementById('results').innerHTML = output;
        }

        // Function to highlight query words in the summary
        function highlightQueryWords(text, query) {
            if (!query) return text; // Return original text if no query
            const regex = new RegExp(`(${query})`, 'gi'); // Case-insensitive matching
            return text.replace(regex, '<strong>$1</strong>'); // Wrap matches in <strong> tags
        }

        // Function to get the appropriate file type image
        function getFileTypeImage(fileName) {
            // Check if fileName is defined and is a string
            if (typeof fileName !== 'string' || fileName.length === 0) {
                return 'https://via.placeholder.com/150/CCCCCC/000000/?text=File'; // Default file image
            }
            const extension = fileName.split('.').pop().toLowerCase(); // Get the file extension
            switch (extension) {
                case 'pdf':
                    return 'https://via.placeholder.com/150/FF0000/FFFFFF/?text=PDF'; // PDF image placeholder
                case 'doc':
                case 'docx':
                    return 'https://via.placeholder.com/150/0000FF/FFFFFF/?text=DOC'; // DOC image placeholder
                case 'xls':
                case 'xlsx':
                    return 'https://via.placeholder.com/150/00FF00/FFFFFF/?text=XLS'; // XLS image placeholder
                case 'ppt':
                case 'pptx':
                    return 'https://via.placeholder.com/150/FFFF00/000000/?text=PPT'; // PPT image placeholder
                default:
                    return 'https://via.placeholder.com/150/CCCCCC/000000/?text=File'; // Default file image
            }
        }

        // Event listeners for view mode buttons
        document.getElementById('listViewBtn').addEventListener('click', function () {
            currentViewMode = 'list'; // Set to list view
            displayResults(currentResults, document.getElementById('query').value); // Re-display results
        });

        document.getElementById('gridViewBtn').addEventListener('click', function () {
            currentViewMode = 'grid'; // Set to grid view
            displayResults(currentResults, document.getElementById('query').value); // Re-display results
        });
    </script>
</body>

</html>
