@extends('dashboard')

@section('title', 'تاريخ زيارات الطالب')

@section('content')
    <h1>تاريخ زيارات الطالب</h1>

    <form action="{{ route('student_visits.show', ['student_visit' => $nationalID]) }}" method="GET">
    <!-- Date and time filter fields -->
    <input type="datetime-local" name="from_datetime" placeholder="من">
    <input type="datetime-local" name="to_datetime" placeholder="إلى">
    <input type="text" name="gate_name" placeholder="بحث بالبوابة">
<input type="text" name="enter_outer" placeholder=" بحث بالدخول او الخروج">
    <button type="submit" class="btn btn-primary">تصفية</button>
</form>
    <table class="table">
        <thead>
            <tr>
                <th>الرقم القومي</th>
                <th>الإسم</th>
                <th>الكلية</th>
                <!-- <th>الحالة</th> -->
                <th>الوقت و التاريخ</th>
                <th>البوابة</th> 
                <th>دخول/خروج</th>

            </tr>
        </thead>
        <tbody>
            @foreach($studentHistory as $visit)
                <tr>
                    <td>{{ $visit->nationalID }}</td>
                    <td>{{ $visit->name }}</td>
                    <td>{{ $visit->faculty }}</td>
                    <!-- <td>{{ $visit->status }}</td> -->
                    <td>{{ date('Y-m-d H:i:s', strtotime($visit->visit_datetime)) }}</td>
                    <td>{{ $visit->gate_name }}</td>
                    <td>{{ $visit->enter_outer }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
    {{ $studentHistory->links('vendor.pagination.bootstrap-4') }}
</div>
@endsection
