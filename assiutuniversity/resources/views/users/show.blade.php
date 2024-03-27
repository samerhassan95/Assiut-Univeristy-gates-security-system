@extends('dashboard')

@section('title', 'تفاصيل المستخدم')

@section('content')
    <h1>تفاصيل المستخدم</h1>

    <p><strong>الإسم:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <!-- Other user details -->
    @if(auth()->user()->role == 'admin')

    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">تعديل</a>

    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
    @endif
@endsection
