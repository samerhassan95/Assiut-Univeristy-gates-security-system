@extends('dashboard')

@section('title', 'إضافة كلية')

@section('content')
    <h1>إضافة كلية</h1>

    <form action="{{ route('faculties.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">الكلية</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
        </div>
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">إنشاء</button>
    </form>
@endsection
