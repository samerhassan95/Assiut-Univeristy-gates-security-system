@extends('dashboard')

@section('title', 'إنشاء مستخدم')

@section('content')
    <h1>إنشاء مستخدم</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">الإسم</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
        </div>
        <!-- Other fields such as password, role, etc. -->
        <button type="submit" class="btn btn-primary">إنشاء</button>
    </form>
@endsection
