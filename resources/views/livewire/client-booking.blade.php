<div class="container-fluid px-2 px-md-4">
    <div class="page-header min-height-300 border-radius-xl mt-4"
        style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
        <span class="mask  bg-gradient-primary  opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mb-5 mt-n9">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Booking Details</h5>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <form submit.prevent>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input wire:model="name" type="text" placeholder="Enter your name"
                                class="form-control border border-2 p-2" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Phone</label>
                            <input wire:model="phone" type="number" maxlength="12" placeholder="254727*****"
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
                            <input wire:model="time" type="time" class="form-control border border-2 p-2"
                                min="{{ date('H:i', strtotime('now')) }}" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="floatingTextarea2">Note</label>
                            <textarea wire:model="note" class="form-control border border-2 p-2"
                                placeholder="Any extra information for your booking" id="floatingTextarea2" rows="4"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-dark" wire:click="save">Submit</button>
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
