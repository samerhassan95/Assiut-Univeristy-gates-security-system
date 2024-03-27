@extends('dashboard')

@section('title', 'تعديل مستخدم')

@section('content')
    <h1>تعديل مستخدم</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">الإسم</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
        </div>
        <!-- Other fields such as password, role, etc. -->
        <button type="submit" class="btn btn-primary">تعديل</button>
    </form>
@endsection
