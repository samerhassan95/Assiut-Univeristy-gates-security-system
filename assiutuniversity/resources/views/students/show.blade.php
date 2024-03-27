@extends('dashboard')

@section('title', 'تفاصيل الطالب')

@section('content')
    <h1>تفاصيل الطالب</h1>

    <p><strong>ID:</strong> {{ $student->id }}</p>
    <p><strong>الإسم:</strong> {{ $student->name }}</p>
    <p><strong>الرقم القومي:</strong> {{ $student->nationalID }}</p>
    <p><strong>الكلية:</strong> {{ $student->faculty }}</p>
    <p><strong>المستوى:</strong> {{ $student->level }}</p>
    <p><strong>الحالة:</strong> {{ $student->status }}</p>
    <!-- <p><strong>Image:</strong> <img src="{{ asset('images/' . $student->image) }}" alt="{{ $student->name }}" width="100"></p> -->
    <!-- Other student details -->
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">تعديل</a>

    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
    @endif
@endsection
