@extends('dashboard')

@section('title', 'إضافة طالب')

@section('content')
    <h1>إضافة طالب</h1>

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
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
            <label for="faculty">الكلية</label>
            <input type="text" class="form-control" id="faculty" name="faculty" placeholder="Enter faculty">
        </div>
        <div class="form-group">
            <label for="level">المستوى</label>
            <input type="text" class="form-control" id="level" name="level" placeholder="Enter level">
        </div>
        <div class="form-group">
            <label for="status">الحالة</label>
            <input type="text" class="form-control" id="status" name="status" placeholder="Enter status">
        </div>
        <!-- <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div> -->
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
@endsection
