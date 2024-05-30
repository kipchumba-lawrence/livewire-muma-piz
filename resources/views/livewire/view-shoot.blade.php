<div class="container-fluid py-4">
    <div class="row">
        <div class="card p-4">
            <h3>
                Shoot Details
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
                    <h5>Booked Time</h5>
                    <?php
                    // Convert the timestamp string to a DateTime object
                    $bookedTime = \Carbon\Carbon::parse($shoot->booked_time);
                    
                    // Display the human-readable difference
                    echo $bookedTime->diffForHumans();
                    ?>
                    ({{ $bookedTime->format('d-m-Y') }})
                    <h5>Note</h5>
                    {{ $shoot->note }}
                    <h5>Status</h5>
                    {{ $shoot->shoot_status }}
                    <br>
                    <Strong>Addons</Strong>
                    <br>
                    @if ($shoot->makeup != null)
                        Makeup
                    @endif
                    @if ($shoot->outfit != null)
                        Outfit
                    @endif
                    @if ($shoot->hair != null)
                        Hair
                    @endif
                </div>
                <div class="col-md-4">
                    @if ($shoot->edit_status == 'completed')
                        <div class="alert alert-success text-white" role="alert">
                            <strong>Success!</strong> Shoot already complete!
                        </div>
                    @else
                        <form action="" wire:submit='completeShoot'>
                            <input type="text" placeholder="Enter pixisite link here" wire:model="pixisit"
                                class="form-control my-3 p-2" required
                                style="border: solid; border-color: #ba7558;border-radius: 5px; border-width: 1.8px ">
                            <input type="number" class="my-2 p-2 form-control" style="border:solid; border-width: 1px;"
                                placeholder="Enter number of Pictures To Be Edited" wire:model.lazy='numberofpix'
                                required>
                            <button type="submit" class="btn btn-sm btn-success">Complete
                                Shoot</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
