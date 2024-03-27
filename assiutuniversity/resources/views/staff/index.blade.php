@extends('dashboard')

@section('title', 'العاملين')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>العاملين</h1>
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('staff.create') }}" class="btn btn-success">إضافة موظف</a>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <form action="{{ route('staff.index') }}" method="GET">
                    <!-- Create input fields for each search filter -->
                    <input type="text" name="name" placeholder="بحث بالإسم">
                    <input type="text" name="nationalID" placeholder="بحث بالرقم القومي">
                    <input type="text" name="job" placeholder="بحث بالوظيفة">
                    <input type="text" name="place" placeholder="بحث بالهيئة">
                    <input type="text" name="status" placeholder="بحث بالحالة">
                    <!-- Add more input fields for other columns as needed -->
                    <button type="submit">بحث</button>
                </form>
            </tr>
            <tr>
                <th>الإسم</th>
                <th>الرقم القومي</th>
                <th>الوظيفة</th>
                <th>الهيئة</th>
                <th>الحالة</th>
                <th>تعديل</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->nationalID }}</td>
                    <td>{{ $employee->job }}</td>
                    <td>{{ $employee->place }}</td>
                    <td>{{ $employee->status }}</td>
                    <td>
                        <!-- Action buttons -->
                        <a href="{{ route('staff.show', $employee->id) }}" class="btn btn-info btn-sm">عرض</a>
                        @if(auth()->user()->role == 'admin')
                        <a href="{{ route('staff.edit', $employee->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                        <form action="{{ route('staff.destroy', $employee->id) }}" method="POST" style="display: inline;">
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
    <div class="mt-4">
    {{ $staff->links('vendor.pagination.bootstrap-4') }}
</div>
@endsection
