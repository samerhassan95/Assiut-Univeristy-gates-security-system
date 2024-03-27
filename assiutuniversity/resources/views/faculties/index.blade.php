@extends('dashboard')

@section('title', 'الكليات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1>الكليات</h1>
        @if(auth()->user()->role == 'admin')
        <a href="{{ route('faculties.create') }}" class="btn btn-success">إضافة كلية جديدة</a>
        @endif
    </div>
    <table>
        <thead>
        <tr>
        <form action="{{ route('faculties.index') }}" method="GET">
    <!-- Create input fields for each search filter -->
    <input type="text" name="name" placeholder="Search by name">
    <button type="submit">بحث</button>
</form>
</tr>
            <tr>
                <th>ID</th>
                <th>الكلية</th>
                <th>تعديل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faculties as $faculty)
                <tr>
                    <td>{{ $faculty->id }}</td>
                    <td>{{ $faculty->name }}</td>
                    <td>
                        <!-- Action buttons -->
                        <a href="{{ route('faculties.show', $faculty->id) }}" class="btn btn-info btn-sm">عرض</a>
                        @if(auth()->user()->role == 'admin')
                        <a href="{{ route('faculties.edit', $faculty->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                        <form action="{{ route('faculties.destroy', $faculty->id) }}" method="POST" style="display: inline;">
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
@endsection
