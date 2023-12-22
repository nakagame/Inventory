@extends('layouts.app')

@section('title', 'Admin')
    
@section('content')
    {{-- search  --}}
    <form action="{{ route('admin.searchUser') }}" class="d-flex mb-3">
        <input type="search" class="form-control" placeholder="Find user here" name="search" value="{{ old('') }}">
        <button type="submit" class="btn btn-success ms-2">
            Search
        </button>
    </form>
    {{-- search End --}}
    <div class="row">
        <div class="col">
            <h1>Hello Admin User</h1>
        </div>
        {{-- dropdown - Role  --}}
        <div class="col text-end">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Role
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.index') }}">All</a></li>    
                    <li><a class="dropdown-item" href="{{ route('admin.index', ['role' => 3]) }}">Admin</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.index', ['role' => 2]) }}">Staff</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.index', ['role' => 1]) }}">User</a></li>
                </ul>
              </div>
        </div>
        {{-- dropdown - Status End --}}
    </div>
    
    <table class="table table-hover">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>UserPhoto</th>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($all_users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        @if ($user->image)
                            <img src="{{ asset('storage/profiles/'. $user->image) }}" alt="{{ $user->image }}" class="rounded circle" style="width: 50px;height: 50px;object-fit:contain;">
                        @else
                            No Image...
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>
                        @if ($user->role === 1)
                            User
                        @elseif($user->role === 2)
                            Staff
                        @elseif($user->role === 3)
                            Admin
                        @endif
                    </td>
                    <td>
                        @if (empty($user->deleted_at))
                            {{-- active --}}
                            <i class="fa-solid fa-user text-success fs-4"></i> Active
                        @else
                            {{-- inactive --}}
                            <i class="fa-solid fa-user text-danger fs-4"></i> Inactive
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                            @if (empty($user->deleted_at))
                                <a href="{{ route('profile.index', $user->id) }}" class="btn btn-outline-secondary me-2">
                                    <i class="fa-solid fa-pen"></i>
                                </a>   
                            @else
                                <a href="{{ route('profile.index', $user->id) }}" class="btn btn-outline-secondary me-2 disabled" >
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endif
                            <!-- Button trigger modal -->
                            @if (empty($user->deleted_at))
                                {{-- delete --}}
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#admin-delete-{{ $user->id }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @else
                                {{-- restore --}}
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#admin-delete-{{ $user->id }}">
                                    <i class="fa-solid fa-trash-can-arrow-up"></i>
                                </button>
                            @endif

                            <!-- Modal -->
                            <div class="modal fade" id="admin-delete-{{ $user->id }}" tabindex="-1" aria-labelledby="admin-delete-label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="admin-delete-label">User Delete</h1>
                                        </div>

                                        <div class="modal-body">
                                            <p class="font-monospace">
                                                Are you sure you want to 
                                                @if (empty($user->deleted_at))
                                                    DELETE 
                                                @else
                                                    RESTORE
                                                @endif
                                                {{ $user->name }} ?
                                            </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            @if (empty($user->deleted_at))
                                                {{-- active  --}}
                                                <form action="{{ route('admin.destroy', $user->id) }}" method="post">
                                                    @csrf
                                                    @method("DELETE")
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            @else
                                                {{-- Inactive --}}
                                                <form action="{{ route('admin.update', $user->id) }}" method="post">
                                                    @csrf
                                                    @method("PATCH")
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                        </div>
                    </td>
                    <td>
                        @if (empty($user->deleted_at))
                            {{-- active --}}
                            <form action="{{ route('admin.forceDeleteActive', $user->id) }}" method="post">
                                @csrf
                                @method("DELETE")

                                <button type="submit" class="btn btn-warning">
                                    Delete Account
                                </button>
                            </form>
                        @else
                           {{-- inactive --}}
                           <form action="{{ route('admin.forceDeleteInactive', $user->id) }}" method="post">
                                @csrf
                                @method("DELETE")

                                <button type="submit" class="btn btn-warning">
                                    Delete Account
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <td colspan="6">No Users...</td>
            @endforelse
        </tbody>
    </table>

    <div class="row justify-content-center">
        <div class="col-7">
            {{ $all_users->links() }}
        </div>
    </div>

@endsection