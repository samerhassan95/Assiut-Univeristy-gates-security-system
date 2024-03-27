@extends('dashboard')

@section('title', 'تفاصيل الكلية')

@section('content')
    <h1>تفاصيل الكلية</h1>

    <p><strong>ID:</strong> {{ $faculty->id }}</p>
    <p><strong>الكلية:</strong> {{ $faculty->name }}</p>
    <!-- Other faculty details -->
    @if(auth()->user()->role == 'admin')

    <a href="{{ route('faculties.edit', $faculty->id) }}" class="btn btn-primary">تعديل</a>

    <form action="{{ route('faculties.destroy', $faculty->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
    @endif
@endsection
