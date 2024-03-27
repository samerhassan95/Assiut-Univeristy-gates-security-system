@extends('dashboard')

@section('title', 'تاريخ زيارات الموظف')

@section('content')
    <h1>تاريخ زيارات الموظف</h1>

    <form action="{{ route('staff_visits.show', ['staff_visit' => $nationalID]) }}" method="GET">
        <!-- Date and time filter fields -->
        <input type="datetime-local" name="from_datetime" placeholder="من">
        <input type="datetime-local" name="to_datetime" placeholder="إلى">

<input type="text" name="gate_name" placeholder="بحث بالبوابة">
<input type="text" name="status" placeholder="بحث بالحالة">
<input type="text" name="enter_outer" placeholder="بحث بالدخول او الخروج">

        <button type="submit" class="btn btn-primary">تصفية</button>
    </form>

    <!-- Display filtered data -->
    <table class="table">
        <thead>
            <tr>
                <th>الرقم القومي</th>
                <th>الإسم</th>
                <th>الوظيفة</th>
                <th>الوقت و التاريخ</th>
                <th>البوابة</th> 
                <th>الحالة</th> 
                <th>دخول/خروج</th><!-- Include the new table heading for Gate Name -->
            </tr>
        </thead>
        <tbody>
            @foreach($staffHistory as $visit)
                <tr>
                    <td>{{ $visit->nationalID }}</td>
                    <td>{{ $visit->name }}</td>
                    <td>{{ $visit->position }}</td>
                    <td>{{ date('Y-m-d H:i:s', strtotime($visit->visit_datetime)) }}</td>
                    <td>{{ $visit->gate_name }}</td>
                    <td>{{ $visit->status }}</td>
                    <td>{{ $visit->enter_outer }}</td>
                 <!-- Display the Gate Name -->
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
    {{ $staffHistory->links('vendor.pagination.bootstrap-4') }}
</div>
@endsection
