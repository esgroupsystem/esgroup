@extends('layouts.master')
@section('content')
    <?php  
        $hour   = date ("G");
        $minute = date ("i");
        $second = date ("s");
        $msg = " Today is " . date ("l, M. d, Y.");

        if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59) {
            $greet = "Good Morning,";
        } else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59) {
            $greet = "Good Day,";
        } else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59) {
            $greet = "Good Afternoon,";
        } else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59) {
            $greet = "Good Evening,";
        } else {
            $greet = "Welcome,";
        }
    ?>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="welcome-box">
                        <div class="welcome-img">
                            <img src="{{ URL::to('/assets/images/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                        </div>
                        <div class="welcome-det">
                            <h3>{{ $greet }} Welcome, {{ Session::get('name') }}!</h3>
                            <p>{{ $todayDate }}</p>
                        </div>
                    </div>
                </div>
            </div>
                <div id="job-orders-refresh">
                    @include('dashboard.joborders_partial')
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->  	
@section('script')
<script>
    let refreshIn = 15;

    const interval = setInterval(() => {
        refreshIn--;

        if (refreshIn <= 0) {
            fetch("{{ route('joborders.refresh') }}")
                .then(response => response.text())
                .then(html => {
                    const target = document.getElementById("job-orders-refresh");
                    if (target) {
                        target.innerHTML = html;
                    }
                    refreshIn = 15;
                })
                .catch(err => console.error('Job orders refresh failed:', err));
        }
    }, 1000);
</script>
@endsection
@endsection


