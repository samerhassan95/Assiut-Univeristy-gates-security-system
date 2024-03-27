@extends('dashboard')

@section('title', 'تفاصيل البوابة')

@section('content')
    <h1>تفاصيل البوابة</h1>

    <p><strong>ID:</strong> {{ $gate->id }}</p>
    <p><strong>Name:</strong> {{ $gate->name }}</p>
    <!-- Other gate details -->
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('gates.edit', $gate->id) }}" class="btn btn-primary">تعديل</a>

    <form action="{{ route('gates.destroy', $gate->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
    @endif
@endsection
