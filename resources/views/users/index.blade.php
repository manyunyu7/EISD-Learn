@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Management</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
    <div class="form-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Search...">
    </div>
    <table class="table" id="userTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Contact</th>
                <th>Jobs</th>
                <th>Institute</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->contact }}</td>
                    <td>{{ $user->jobs }}</td>
                    <td>{{ $user->institute }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var value = this.value.toLowerCase();
        var rows = document.querySelectorAll('#userTable tbody tr');

        rows.forEach(function(row) {
            var show = false;
            row.querySelectorAll('td').forEach(function(cell) {
                if (cell.textContent.toLowerCase().includes(value)) {
                    show = true;
                }
            });
            row.style.display = show ? '' : 'none';
        });
    });
</script>
@endsection
