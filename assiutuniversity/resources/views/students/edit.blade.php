@extends('dashboard')

@section('title', 'تعديل طالب')

@section('content')
    <h1>تعديل طالب</h1>

    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">الإسم</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}">
        </div>
        <div class="form-group">
            <label for="nationalID">الرقم القومي</label>
            <input type="text" class="form-control" id="nationalID" name="nationalID" value="{{ $student->nationalID }}">
        </div>
        <div class="form-group">
            <label for="faculty">الكلية</label>
            <input type="text" class="form-control" id="faculty" name="faculty" value="{{ $student->faculty }}">
        </div>
        <div class="form-group">
            <label for="level">المستوى</label>
            <input type="text" class="form-control" id="level" name="level" value="{{ $student->level }}">
        </div>
        <div class="form-group">
            <label for="status">الحالة</label>
            <input type="text" class="form-control" id="status" name="status" value="{{ $student->status }}">
        </div>
        <div class="form-group">
            <label for="serial_number">الرقم التسلسلي للبنك</label>
            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ $student->serial_number }}">
        </div>
        <!-- <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div> -->
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">تعديل</button>
    </form>
@endsection
