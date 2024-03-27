@extends('dashboard')

@section('title', 'المستخدمين')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>المستخدمين</h1>
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('users.create') }}" class="btn btn-success">إضافة مستخدم</a>
        @endif
    </div>

    <table class="table">
        <thead>
            <tr>
                <form action="{{ route('users.index') }}" method="GET">
                    <input type="text" name="name" placeholder="Search by name">
                    <button type="submit">بحث</button>
                </form>
            </tr>
            <tr>
                <th>الإسم</th>
                <th>Email</th>
                <th>Role</th>
                <th>تعديل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @if(auth()->user()->role == 'admin' || auth()->id() == $user->id)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">عرض</a>
                        @if(auth()->user()->role == 'admin')
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
                                <form action="{{ route('admin.toggle', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        @if($user->role == 'user')
                                            ترقية إلى مدير
                                        @else
                                            ترقية إلى مستخدم
                                        @endif
                                    </button>
                                </form>
                                @if(!$user->approved)
                                    <form action="{{ route('admin.approve', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                @elseif(!$user->frozen)
                                    <form action="{{ route('admin.freeze', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">Freeze</button>
                                    </form>
                                @endif
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">تعديل</a>

                            @endif
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endsection
