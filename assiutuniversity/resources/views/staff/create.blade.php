@extends('dashboard')

@section('title', 'إنشاء موظف')

@section('content')
    <h1>إنشاء موظف</h1>

    <form action="{{ route('staff.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">الإسم</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label for="nationalID">الرقم القومي</label>
            <input type="text" class="form-control" id="nationalID" name="nationalID" placeholder="Enter national ID">
        </div>
        <div class="form-group">
            <label for="job">الوظيفة</label>
            <input type="text" class="form-control" id="job" name="job" placeholder="Enter job">
        </div>
        <div class="form-group">
            <label for="place">الهيئة</label>
            <input type="text" class="form-control" id="place" name="place" placeholder="Enter place">
        </div>
        <div class="form-group">
            <label for="status">الحالة</label>
            <input type="text" class="form-control" id="status" name="status" placeholder="Enter status">
        </div>
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">إنشاء</button>
    </form>
@endsection
