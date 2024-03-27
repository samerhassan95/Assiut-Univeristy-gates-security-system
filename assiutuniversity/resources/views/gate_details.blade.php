@extends('dashboard')

@section('content')
<div class="container" dir="rtl" id="auto-refresh-container">
        <h2>{{ $gate->name }} تفاصيل</h2>
        @if(request()->has('start_date') && request()->has('end_date'))
            @php
                $startDate = request('start_date');
                $endDate = request('end_date');
            @endphp

            <h3>تفاصيل الزيارات من يوم {{ $startDate }} حتى يوم {{ $endDate }}</h3>
            @php
    $enteredCounts = $gate->totalEnteredVisitsInRange($startDate, $endDate);
    $exitedCounts = $gate->totalExitedVisitsInRange($startDate, $endDate);

    $totalEntered = $enteredCounts['studentEnterCount'] + $enteredCounts['staffEnterCount'];
    $totalExited = $exitedCounts['studentExitCount'] + $exitedCounts['staffExitCount'];
@endphp

<h3>إجمالي مرات الدخول: {{ $totalEntered }}</h3>
<h3>إجمالي مرات الخروج : {{ $totalExited }}</h3>

 <h3>عدد المتواجدين:</h3>
 <p>عدد الطلاب والموظفين المتواجدين في الفترة المحددة: <span id="currentlyInsideCount_{{ $gate->id }}"></span>{{ $gate->currentlyInsideCount($startDate, $endDate) }}</p>
               

        @else
            <h3>زبارات اليوم</h3>
            <p>مجموع الزيارات (دخول) اليوم: <span id="totalEnteredVisitsCount_{{ $gate->id }}">{{ $gate->studentEnteredToday()->count() + $gate->staffEnteredToday()->count() }}</span></p>
            <p>مجموع الزيارات (خروج) اليوم: <span id="totalExitedVisitsCount_{{ $gate->id }}">{{ $gate->studentOuterToday()->count() + $gate->staffOuterToday()->count() }}</span></p>
        @endif

        <h3>تفاصيل زيارات الطلاب</h3>
        <ul>
            <li>عدد الطلاب دخول: {{ $gate->studentEnteredByFaculty->count() }}</li>
            <li>عدد الطلاب خروج: {{ $gate->studentExitedByFaculty->count() }}</li>

            <h4>عدد الطلاب من كل كلية دخول</h4>
            <ul>
        @foreach($studentEnteredByFaculty as $entry)
            <li>{{ $entry->faculty }} دخول: {{ $entry->count }}</li>
        @endforeach
    </ul>

            <h4>عدد الطلاب من كل كلية خروج</h4>
            <ul>
                @foreach($studentExitedByFaculty as $exit)
                    <li>{{ $exit->faculty }}: {{ $exit->count }}</li>
                @endforeach
            </ul>
        </ul>

        <h3>تفاصيل زيارات الموظفين</h3>
        <ul>
            <li>عدد الموظفين دخول: {{ $gate->staffEnteredCount->count() }}</li>
            <li>عدد الموظفين خروج: {{ $gate->staffExitedCount->count() }}</li>

        </ul>
    </div>
    <script>
    // Function to fetch and update gate details every 2 seconds
    function autoRefreshGateDetails() {
        setInterval(function() {
            // Fetch the updated gate details using AJAX
            $.ajax({
                url: "{{ route('gate.details', ['gateId' => $gate->id]) }}",
                method: 'GET',
                success: function(response) {
                    $('#auto-refresh-container').html(response);
                }
            });
        }, 2000); // Refresh every 2 seconds (2000 milliseconds)
    }

    // Call the function when the document is ready
    $(document).ready(function() {
        autoRefreshGateDetails();
    });
</script>
@endsection
