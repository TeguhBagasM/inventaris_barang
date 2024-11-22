@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h4 class="">Edit User</h4>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $user->name }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $user->email }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="admin" @if ($user->level == 'admin') selected @endif>Admin</option>
                                    <option value="petugas 1" @if ($user->level == 'petugas 1') selected @endif>Petugas 1</option>
                                    <option value="petugas 2" @if ($user->level == 'petugas 2') selected @endif>Petugas 2</option>
                                    <option value="petugas 3" @if ($user->level == 'petugas 3') selected @endif>Petugas 3</option>
                                    <option value="guru" @if ($user->level == 'guru') selected @endif>Guru</option>
                                    <option value="siswa" @if ($user->level == 'siswa') selected @endif>Siswa</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password (leave blank if not changing)</label>
                                <input type="password" class="form-control" id="password" name="password">
                                @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
