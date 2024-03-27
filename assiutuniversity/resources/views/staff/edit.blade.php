@extends('dashboard')

@section('title', 'تعديل موظف')

@section('content')
    <h1>تعديل موظف</h1>

    <form action="{{ route('staff.update', $staff->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">الإسم</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $staff->name }}">
        </div>
        <div class="form-group">
            <label for="nationalID">الرقم القومي</label>
            <input type="text" class="form-control" id="nationalID" name="nationalID" value="{{ $staff->nationalID }}">
        </div>
        <div class="form-group">
            <label for="job">الوظيفة</label>
            <input type="text" class="form-control" id="job" name="job" value="{{ $staff->job }}">
        </div>
        <div class="form-group">
            <label for="place">الهيئة</label>
            <input type="text" class="form-control" id="place" name="place" value="{{ $staff->place }}">
        </div>
        <div class="form-group">
            <label for="status">الحالة</label>
            <input type="text" class="form-control" id="status" name="status" placeholder="Enter status">
        </div>
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">تعديل</button>
    </form>
@endsection
