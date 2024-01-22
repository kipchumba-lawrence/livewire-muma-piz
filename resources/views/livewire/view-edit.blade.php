<div class="container-fluid py-4">
    <div class="row">
        <div class="card p-4">
            <h3>
                Edit Details
            </h3>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <h5>Customer Name</h5>
                    {{ $shoot->customer_name }}
                    <h5>Phone Number</h5>
                    {{ $shoot->phone }}
                    <h5>Package</h5>
                    {{ $shoot->package }}
                    <h5>Location</h5>
                    {{ $shoot->venue }}
                </div>
                <div class="col-md-4">
                    <h5>Countdown</h5>
                    <span id="countdown-{{ $shoot->id }}"></span>
                    <h5>Note</h5>
                    {{ $shoot->note }}
                    <h5>Number of Pictures</h5>
                    {{ $shoot->numberofpix }}
                    <h5>Pixisite Link</h5>
                    <a href="{{ $shoot->image_collection }}" target="_blank"><span class="text-success text-bold">
                            Photo Collection</span></a>
                </div>
                <div class="col-md-4">
                    @if ($shoot->editing_status == 'complete')
                        <div class="alert alert-success text-white" role="alert">
                            <strong>Success!</strong> Edit already complete!
                        </div>
                    @else
                        <form action="" wire:submit.prevent='completeEdit'>
                            <input type="text" class="my-3 p-2 form-control" style="border:solid; border-width: 1px;"
                                placeholder="Enter export Client Link" wire:model.lazy='link' required>
                            <button type="submit" class="btn btn-sm btn-success">Complete Edit</button>
                        </form>
                    @endif
                </div>

                <script>
                    // Calculate countdown
                    var editStart = new Date("{{ $shoot->editstart }}").getTime() / 1000; // convert to seconds
                    var countdownElement = document.getElementById("countdown-{{ $shoot->id }}");

                    function updateCountdown() {
                        var now = Math.floor(new Date().getTime() / 1000);
                        var distance = now - editStart;
                        var totalTime = 259200;
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
                </script>
            </div>
        </div>
    </div>
</div>
