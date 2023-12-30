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
                    var distance = editStart - now;
                    var totalTime = 259200; // 72 hours in seconds
                    var remainingTime = totalTime - distance;

                    if (remainingTime <= 0) {
                        countdownElement.innerHTML = "Overdue";
                    } else {
                        var hours = Math.floor(remainingTime / 3600);
                        var minutes = Math.floor((remainingTime % 3600) / 60);
                        var seconds = remainingTime % 60;

                        countdownElement.innerHTML = hours + "h " + minutes + "m " + seconds + "s ";
                    }
                }

                // Update countdown every second
                setInterval(updateCountdown, 1000);

                // Initial update
                updateCountdown();
            });
        });
    </script>

</div>
