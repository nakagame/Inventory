@extends('layouts.app')

@section('title', 'Profile')
    
@section('content')
    <h1>Welcome {{ $user->name }}</h1>

    <form action="{{ route('profile.update', $user->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <input type="text" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        @if (Auth::user()->role === 3)
            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role_user" value="1" 
                    <?= ($user->role === 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="role_user">User</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role_staff" value="2"
                    <?= ($user->role === 2) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="role_staff">Staff</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role_admin" value="3"
                    <?= ($user->role === 3) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="role_admin">Admin</label>
                </div>
            </div>
        @endif

        <div class="mb-3">
            <div class="row">
                <div class="col">
                    <input type="file" name="image" id="image" class="form-control">
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    @if ($user->image)
                        <img src="{{ asset('storage/profiles/'. $user->image) }}" alt="{{ $user->image }}" class="img-thumbnail">
                    @else
                        <img src="https://thumb.ac-illust.com/73/7387030e5a5600726e5309496353969a_t.jpeg" alt="No Image" class="img-thumbnail">
                    @endif
                </div>
            </div>
        </div>

        <a href="{{ route('index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-pen"></i> Save
        </button>
    </form>
@endsection