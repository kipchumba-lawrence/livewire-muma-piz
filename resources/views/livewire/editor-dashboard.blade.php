<div class="container-fluid py-4">
    <div class="row">
        <h3>Pending Edits</h3>
        <hr>
    </div>

    <style>
        .edit-card {
            margin: 10px;
            border-radius: 20px !important;
            padding: 13px;
            background-color: rgba(224, 125, 125, 0.114);
            float: left;
            width: 250px;
            height: auto;
            cursor: pointer;
            transition: all .4s ease-in-out;
        }

        .edit-card:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="row">
        @foreach ($pending_edits as $shoot)
            <div class="card edit-card" id="edit-card-{{ $shoot->id }}">
                <a href="{{ route('view-edit', $shoot->id) }}">
                    <span class="text-bold">Name: {{ $shoot->customer_name }} </span>
                    <br>
                    <span class="text-sm">Countdown: <span class="countdown" data-editstart="{{ $shoot->editstart }}"
                            data-id="{{ $shoot->id }}"></span></span>
                    <div class="mt-2">
                        @if ($shoot->venue === 'walkin')
                            <span class="badge badge-sm bg-gradient-info">Walk-in</span>
                        @endif
                        @if ($shoot->social_consent)
                            <span class="badge badge-sm bg-gradient-success">Social OK</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end">
                        <span class="text-xxs text-end">click to view details</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <script>
        // Calculate countdown for each element
        document.addEventListener("DOMContentLoaded", function() {
            var countdownElements = document.querySelectorAll(".countdown");

            countdownElements.forEach(function(element) {
                var editStartTimestamp = element.getAttribute("data-editstart");
                var editStart = new Date(editStartTimestamp).getTime() / 1000;
                var countdownId = element.getAttribute("data-id");
                var countdownElement = document.getElementById("edit-card-" + countdownId).querySelector(
                    ".countdown");

                function updateCountdown() {
                    var now = Math.floor(new Date().getTime() / 1000);
                    var elapsed = now - editStart; // Time elapsed since edit started
                    var totalTime = 259200; // 72 hours in seconds
                    var remainingTime = totalTime - elapsed;

                    var cardElement = document.getElementById("edit-card-" + countdownId);

                    if (remainingTime <= 0) {
                        countdownElement.innerHTML = "<span class='text-danger font-weight-bold'>Overdue by " + formatTime(Math.abs(remainingTime)) + "</span>";
                        cardElement.style.backgroundColor = "rgba(220, 53, 69, 0.2)";
                    } else {
                        countdownElement.innerHTML = formatTime(remainingTime);
                        if (remainingTime < 21600) { // Less than 6 hours
                            cardElement.style.backgroundColor = "rgba(255, 193, 7, 0.2)";
                        }
                    }
                }

                function formatTime(seconds) {
                    var hours = Math.floor(seconds / 3600);
                    var minutes = Math.floor((seconds % 3600) / 60);
                    var secs = seconds % 60;
                    return hours + "h " + minutes + "m " + secs + "s";
                }

                // Update countdown every second
                setInterval(updateCountdown, 1000);

                // Initial update
                updateCountdown();
            });
        });
    </script>

</div>
