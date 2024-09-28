@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h5 class="mb-0">Edit Profile</h5>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Users</a></li>
                <li class="breadcrumb-item">Forms</li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        {{ Auth::user()->isEmployee() ? 'readonly' : '' }}
                        required
                    >
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        {{ Auth::user()->isEmployee() ? 'readonly' : '' }}
                        required
                    >
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password (Leave blank if you don't want to change it)</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                    >
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                    >
                    @error('password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @if(Auth::user()->isAdmin())
                    <div class="form-group mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select
                            id="department"
                            name="department"
                            class="form-select"
                            required
                        >
                            <option value="" disabled>Select Department</option>
                            @foreach($departments as $department)
                                <option
                                    value="{{ $department }}"
                                    {{ old('department', $user->department) == $department ? 'selected' : '' }}
                                >
                                    {{ $department }}
                                </option>
                            @endforeach
                        </select>
                        @error('department')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="department" value="{{ $user->department }}">
                @endif

                @if(Auth::user()->isAdmin())
                    <div class="form-group mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select
                            id="role"
                            name="role"
                            class="form-select"
                            required
                        >
                            <option value="" disabled>Select Role</option>
                            <option
                                value="employee"
                                {{ old('role') == 'employee' ? 'selected' : '' }}
                            >Employee</option>
                            <option
                                value="hr"
                                {{ old('role') == 'hr' ? 'selected' : '' }}
                            >HR</option>
                            <option
                                value="admin"
                                {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}
                            >Admin</option>
                            <option
                                value="hod"
                                {{ old('role', $user->role) == 'hod' ? 'selected' : '' }}
                            >HOD</option>
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @endif

                <button type="submit" class="btn" style="background-color: #AF1E23; color: #fff;">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
