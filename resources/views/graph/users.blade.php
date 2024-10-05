<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Users</h1>

    <!-- Search Input -->
    <div class="mb-4">
        <input type="text" class="form-control" id="searchInput" placeholder="Search users by name or email">
    </div>

    <!-- Flash Messages -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="usersTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Job Title</th>
                    <th>Mobile Phone</th>
                    <th>Office Location</th>
                    <th>OneDrive Folder</th> <!-- OneDrive Folder button column -->
                </tr>
            </thead>
            <tbody>
                @forelse ($allUsers as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td> <!-- User ID -->
                        <td>
                            <!-- User name as a clickable link to OneDrive folder -->
                            <a href="{{ request()->root() }}/onedrive?userId={{ $user['id'] }}" target="_blank">
                                {{ $user['displayName'] ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $user['mail'] ?? 'N/A' }}</td>
                        <td>{{ $user['jobTitle'] ?? 'N/A' }}</td>
                        <td>{{ $user['mobilePhone'] ?? 'N/A' }}</td>
                        <td>{{ $user['officeLocation'] ?? 'N/A' }}</td>
                        <td>
                            <!-- Button to open OneDrive folder in a new tab -->
                            <a href="{{request()->root()}}/onedrive?userId={{ $user['id'] }}" class="btn btn-primary btn-sm" target="_blank">
                                View Folder
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No users found.</td> <!-- Updated colspan -->
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('usersTable');
    const rows = table.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            const name = cells[1].textContent.toLowerCase(); // Index 1 for Name
            const email = cells[2].textContent.toLowerCase(); // Index 2 for Email

            if (name.includes(query) || email.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
