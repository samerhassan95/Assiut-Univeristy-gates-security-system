@extends('dashboard')

@section('title', 'البوابات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1>البوابات</h1>
        @if(auth()->user()->role == 'admin')
        <a href="{{ route('gates.create') }}" class="btn btn-success">إضافة بوابة</a>
        @endif
    </div>
    <table>
        <thead>
        <tr>
        <form action="{{ route('gates.index') }}" method="GET">
    <!-- Create input fields for each search filter -->
    <input type="text" name="name" placeholder="بحث بالبوابة">
    <button type="submit">بحث</button>
</form>
</tr>
            <tr>
                <th>ID</th>
                <th>البوابة</th>
                <th>تعديل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gates as $gate)
                <tr>
                    <td>{{ $gate->id }}</td>
                    <td>{{ $gate->name }}</td>
                    <td>
                        <!-- Action buttons -->
                        <a href="{{ route('gates.show', $gate->id) }}" class="btn btn-info btn-sm">عرض</a>
                        @if(auth()->user()->role == 'admin')
                        <a href="{{ route('gates.edit', $gate->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                        <form action="{{ route('gates.destroy', $gate->id) }}" method="POST" style="display: inline;">
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
