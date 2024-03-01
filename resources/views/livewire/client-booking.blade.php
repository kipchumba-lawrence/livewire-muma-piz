<div class="container-fluid px-2 px-md-4">
    <div class="page-header min-height-500 border-radius-xl mt-4"
        style="background-image: url('https://images.unsplash.com/photo-1617463874381-85b513b3e991?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
        <img src="{{ asset('assets') }}/img/logos/icon.png" class="img-fluid" alt="">
        <span class="mask  bg-gradient-primary  opacity-1"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mb-5 mt-n9">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Booking Your Shoot today!</h5>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <form>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Name</label>
                            <input wire:model="name" type="text" placeholder="Enter your name"
                                class="form-control border border-2 p-2" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Phone (07*******)</label>
                            <input wire:model="phone" type="text" maxlength="10" pattern="[0-9]{10}"
                                placeholder="0727*****" class="form-control border border-2 p-2" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Email</label>
                            <input wire:model="email" type="email" placeholder="name@email.com"
                                class="form-control border border-2 p-2" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Location</label>
                            <select wire:model="venue" class="form-control border border-2 p-2">
                                <option value="">Select venue</option>
                                <option value="indoor">Indoor</option>
                                <option value="outdoor">Outdoor</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Package</label>
                            <select wire:model="package" class="form-select border border-2 form-control p-2">
                                <option value="">Select Package</option>
                                <option value="Custom">Custom</option>
                                <option value="High-End">High-End</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Schedule Date</label>
                            <input wire:model="scheduleDate" type="date" class="form-control border border-2 p-2"
                                min="{{ date('Y-m-d', strtotime('today')) }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Time</label>
                            <input wire:model="time" type="time" class="form-control border border-2 p-2" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="floatingTextarea2">Note</label>
                            <textarea wire:model="note" class="form-control border border-2 p-2"
                                placeholder="Any extra information for your booking" id="floatingTextarea2" rows="2"></textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <button type="submit" class="btn m-3 bg-gradient-dark" wire:click="save">Book!</button>
                        </div>
                    </div>
                </form>
                @if ($paymentStatus != null)
                    <div class="alert alert-success alert-dismissible text-white" role="alert">
                        {{ $paymentStatus }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
