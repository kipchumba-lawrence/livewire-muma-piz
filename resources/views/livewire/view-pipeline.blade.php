<div class="container-fluid py-4">
    <div class="row">
        <div class="card p-4">
            <h3>
                Pipeline
            </h3>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <h5>Customer Name</h5>
                    {{ $pipeline->customer_name }}
                    <h5>Phone Number</h5>
                    {{ $pipeline->phone }}
                    <h5>Package</h5>
                    {{ $pipeline->package }}
                    <h5>Location</h5>
                    {{ $pipeline->venue }}
                    <h5>Booked Time</h5>
                    <?php
                    // Convert the timestamp string to a DateTime object
                    $bookedTime = \Carbon\Carbon::parse($pipeline->booked_time);
                    
                    // Display the human-readable difference
                    echo $bookedTime->toFormattedDateString();
                    ?>
                </div>
                <div class="col-md-4">

                    <h5>Email</h5>
                    {{ $pipeline->email }}
                    <h5>Note</h5>
                    {{ $pipeline->note }}
                    <h5>Amount Paid on Booking</h5>
                    {{ $pipeline->paid_amount }}
                    <h5>Total Amount</h5>
                    {{ $pipeline->total_amount }}
                    <h5>Shooting Status</h5>
                    @if ($pipeline->shoot_status == 'completed')
                        <span class="badge badge-pill bg-gradient-success">Complete</span>
                    @else
                        <span class="badge badge-pill bg-gradient-primary">Pending</span>
                    @endif
                    <h5>Editing Status</h5>
                    @if ($pipeline->editing_status == 'complete')
                        <span class="badge badge-pill bg-gradient-success">Complete</span>
                        <br>
                        <span class="text-bold"> Link: <a href="{{ $pipeline->export_link }}">
                                {{ $pipeline->export_link }}
                            </a></span>
                    @else
                        <span class="badge badge-pill bg-gradient-primary">Pending</span>
                    @endif
                </div>
                <div class="col-md-4">
                    @if ($pipeline->editing_status === 'complete')
                        <button class="btn btn-warning btn-md" wire:click="revert">Request Changes</button>
                    @endif
                    @if ($pipeline->editing_status === 'complete' && $pipeline->shoot_status === 'completed')
                        <button class="btn btn-success btn-md" wire:click="complete">Complete</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
