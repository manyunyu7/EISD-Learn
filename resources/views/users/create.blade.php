@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create User</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="email_verified_at">Email Verified At:</label>
            <input type="datetime-local" class="form-control" id="email_verified_at" name="email_verified_at">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="profile_url">Profile URL:</label>
            <input type="text" class="form-control" id="profile_url" name="profile_url">
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" class="form-control" id="role" name="role">
        </div>
        <div class="form-group">
            <label for="contact">Contact:</label>
            <input type="text" class="form-control" id="contact" name="contact">
        </div>
        <div class="form-group">
            <label for="jobs">Jobs:</label>
            <input type="text" class="form-control" id="jobs" name="jobs">
        </div>
        <div class="form-group">
            <label for="institute">Institute:</label>
            <input type="text" class="form-control" id="institute" name="institute">
        </div>
        <div class="form-group">
            <label for="mdln_username">MDLN Username:</label>
            <input type="text" class="form-control" id="mdln_username" name="mdln_username">
        </div>
        <div class="form-group">
            <label for="motto">Motto:</label>
            <input type="text" class="form-control" id="motto" name="motto">
        </div>
        <div class="form-group">
            <label for="university">University:</label>
            <input type="text" class="form-control" id="university" name="university">
        </div>
        <div class="form-group">
            <label for="major">Major:</label>
            <input type="text" class="form-control" id="major" name="major">
        </div>
        <div class="form-group">
            <label for="interest">Interest:</label>
            <input type="text" class="form-control" id="interest" name="interest">
        </div>
        <div class="form-group">
            <label for="cv">CV:</label>
            <input type="text" class="form-control" id="cv" name="cv">
        </div>
        <div class="form-group">
            <label for="sub_department">Sub Department:</label>
            <input type="text" class="form-control" id="sub_department" name="sub_department">
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" id="location" name="location">
        </div>
        <div class="form-group">
            <label for="url_personal_website">Personal Website URL:</label>
            <input type="text" class="form-control" id="url_personal_website" name="url_personal_website">
        </div>
        <div class="form-group">
            <label for="url_facebook">Facebook URL:</label>
            <input type="text" class="form-control" id="url_facebook" name="url_facebook">
        </div>
        <div class="form-group">
            <label for="url_instagram">Instagram URL:</label>
            <input type="text" class="form-control" id="url_instagram" name="url_instagram">
        </div>
        <div class="form-group">
            <label for="url_linkedin">LinkedIn URL:</label>
            <input type="text" class="form-control" id="url_linkedin" name="url_linkedin">
        </div>
        <div class="form-group">
            <label for="url_twitter">Twitter URL:</label>
            <input type="text" class="form-control" id="url_twitter" name="url_twitter">
        </div>
        <div class="form-group">
            <label for="url_whatsapp">WhatsApp URL:</label>
            <input type="text" class="form-control" id="url_whatsapp" name="url_whatsapp">
        </div>
        <div class="form-group">
            <label for="url_youtube">YouTube URL:</label>
            <input type="text" class="form-control" id="url_youtube" name="url_youtube">
        </div>

        <div class="form-group">
            <label for="department_name">Department Name:</label>
            <select class="form-control" id="department_id" name="department_id">
                @forelse ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @empty
                    <option value="">No departments found</option>
                @endforelse
            </select>
        </div>
        <div class="form-group">
            <label for="position_id">Position ID:</label>
            <input type="number" class="form-control" id="position_id" name="position_id">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
