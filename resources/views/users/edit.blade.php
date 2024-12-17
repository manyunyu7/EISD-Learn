@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>
        {{-- <div class="form-group">
            <label for="email_verified_at">Email Verified At:</label>
            <input type="datetime-local" class="form-control" id="email_verified_at" name="email_verified_at" value="{{ $user->email_verified_at }}">
        </div> --}}
        {{-- <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="form-text text-muted">Leave blank to keep the current password.</small>
        </div> --}}
        {{-- <div class="form-group">
            <label for="profile_url">Profile URL:</label>
            <input type="text" class="form-control" id="profile_url" name="profile_url" value="{{ $user->profile_url }}">
        </div> --}}
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" class="form-control" id="role" name="role" value="{{ $user->role }}">
        </div>
        <div class="form-group">
            <label for="contact">Contact:</label>
            <input type="text" class="form-control" id="contact" name="contact" value="{{ $user->contact }}">
        </div>
        <div class="form-group">
            <label for="jobs">Jobs:</label>
            <input type="text" class="form-control" id="jobs" name="jobs" value="{{ $user->jobs }}">
        </div>
        <div class="form-group">
            <label for="institute">Institute:</label>
            <input type="text" class="form-control" id="institute" name="institute" value="{{ $user->institute }}">
        </div>
        {{-- <div class="form-group">
            <label for="mdln_username">MDLN Username:</label>
            <input type="text" class="form-control" id="mdln_username" name="mdln_username" value="{{ $user->mdln_username }}">
        </div> --}}
        <div class="form-group">
            <label for="motto">Motto:</label>
            <input type="text" class="form-control" id="motto" name="motto" value="{{ $user->motto }}">
        </div>
        <div class="form-group">
            <label for="university">University:</label>
            <input type="text" class="form-control" id="university" name="university" value="{{ $user->university }}">
        </div>
        <div class="form-group">
            <label for="major">Major:</label>
            <input type="text" class="form-control" id="major" name="major" value="{{ $user->major }}">
        </div>
        <div class="form-group">
            <label for="interest">Interest:</label>
            <input type="text" class="form-control" id="interest" name="interest" value="{{ $user->interest }}">
        </div>
        <div class="form-group">
            <label for="cv">CV:</label>
            <input type="text" class="form-control" id="cv" name="cv" value="{{ $user->cv }}">
        </div>
        {{-- <div class="form-group">
            <label for="sub_department">Sub Department:</label>
            <input type="text" class="form-control" id="sub_department" name="sub_department" value="{{ $user->sub_department }}">
        </div> --}}
        {{-- <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $user->location }}">
        </div> --}}
        <div class="form-group">
            <label for="url_personal_website">Personal Website URL:</label>
            <input type="text" class="form-control" id="url_personal_website" name="url_personal_website" value="{{ $user->url_personal_website }}">
        </div>
        <div class="form-group">
            <label for="url_facebook">Facebook URL:</label>
            <input type="text" class="form-control" id="url_facebook" name="url_facebook" value="{{ $user->url_facebook }}">
        </div>
        <div class="form-group">
            <label for="url_instagram">Instagram URL:</label>
            <input type="text" class="form-control" id="url_instagram" name="url_instagram" value="{{ $user->url_instagram }}">
        </div>
        <div class="form-group">
            <label for="url_linkedin">LinkedIn URL:</label>
            <input type="text" class="form-control" id="url_linkedin" name="url_linkedin" value="{{ $user->url_linkedin }}">
        </div>
        <div class="form-group">
            <label for="url_twitter">Twitter URL:</label>
            <input type="text" class="form-control" id="url_twitter" name="url_twitter" value="{{ $user->url_twitter }}">
        </div>
        <div class="form-group">
            <label for="url_whatsapp">WhatsApp URL:</label>
            <input type="text" class="form-control" id="url_whatsapp" name="url_whatsapp" value="{{ $user->url_whatsapp }}">
        </div>
        <div class="form-group">
            <label for="url_youtube">YouTube URL:</label>
            <input type="text" class="form-control" id="url_youtube" name="url_youtube" value="{{ $user->url_youtube }}">
        </div>
        {{-- <div class="form-group">
            <label for="department_name">Department Name:</label>
            <select class="form-control" id="department_id" name="department_id">
                @forelse ($departments as $department)
                    <option value="{{ $department->id }}"
                        @if ($user->department_id == $department->id) selected @endif>
                        {{ $department->name }}
                    </option>
                @empty
                    <option value="">No departments found</option>
                @endforelse
            </select>
        </div>
        <div class="form-group">
            <label for="position_id">Position ID:</label>
            <input type="number" class="form-control" id="position_id" name="position_id" value="{{ $user->position_id }}">
        </div> --}}
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
