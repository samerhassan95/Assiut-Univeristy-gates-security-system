@extends('dashboard')

@section('title', 'تفاصيل الموظف')

@section('content')
    <h1>تفاصيل الموظف</h1>

    <p><strong>ID:</strong> {{ $staff->id }}</p>
    <p><strong>الإسم:</strong> {{ $staff->name }}</p>
    <p><strong>الرقم القومي:</strong> {{ $staff->nationalID }}</p>
    <p><strong>الوظيفة:</strong> {{ $staff->job }}</p>
    <p><strong>الهيئة:</strong> {{ $staff->place }}</p>
    <p><strong>الحالة:</strong> {{ $staff->status }}</p>
    <!-- Other staff details -->
    @if(auth()->user()->role == 'admin')

    <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-primary">تعديل</a>

    <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
    @endif
@endsection
