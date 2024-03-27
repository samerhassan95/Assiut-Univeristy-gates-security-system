@extends('dashboard')

@section('title', 'الطلاب')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>الطلاب</h1>
        @if(auth()->user()->role == 'admin') <!-- Replace 'admin' with your admin role name -->
            <a href="{{ route('students.create') }}" class="btn btn-success">إضافة طالب</a>
        @endif
    </div>

    <form action="{{ route('students.index') }}" method="GET">
        <!-- Create input fields for each search filter -->
        <input type="text" name="name" placeholder="بحث بالإسم">
        <input type="text" name="nationalID" placeholder="بحث بالرقم القومي">
        <input type="text" name="faculty" placeholder="بحث بالكلية">
        <input type="text" name="level" placeholder="بحث بالمستوى">
        <input type="text" name="status" placeholder="بحث بالحالة">
        <!-- Add more input fields for other columns as needed -->
        <button type="submit">بحث</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>الإسم</th>
                <th>الرقم القومي</th>
                <th>الكلية</th>
                <th>المستوي</th>
                <th>الحالة</th>
                <!-- <th>Image</th> -->
                <th>تعديل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->nationalID }}</td>
                    <td>{{ $student->faculty }}</td>
                    <td>{{ $student->level }}</td>
                    <td>{{ $student->status }}</td>
                    <!-- <td><img src="{{ asset('images/' . $student->image) }}" alt="{{ $student->name }}" width="50"></td> -->
                    <td>
                        <!-- Action buttons -->
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">عرض</a>
                        @if(auth()->user()->role == 'admin')
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Display pagination links -->
    <!-- Pagination links -->
<div class="mt-4">
    {{ $students->links('vendor.pagination.bootstrap-4') }}
</div>

@endsection
