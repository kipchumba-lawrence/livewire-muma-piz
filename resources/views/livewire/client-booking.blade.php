<div class="container-fluid px-2 px-md-4">
    <style>
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
    {{-- <div class="page-header min-height-500 border-radius-xl mt-4"
        style="background-image: url('https://images.unsplash.com/photo-1617463874381-85b513b3e991?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
        <img src="{{ asset('assets') }}/img/logos/icon.png" class="img-fluid" alt="">
        <span class="mask  bg-gradient-primary  opacity-1"></span>
    </div> --}}
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div id="carouselExampleControls" class="carousel slide border-radius-xl overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner" style="max-height: 600px;">
                        <div class="carousel-item active">
                            <img src="{{ asset('assets/img/carousel/uploaded_image_0_1763672612958.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 600px; object-position: center;" alt="Slide 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('assets/img/carousel/uploaded_image_1_1763672612958.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 600px; object-position: center;" alt="Slide 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('assets/img/carousel/uploaded_image_2_1763672612958.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 600px; object-position: center;" alt="Slide 3">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('assets/img/carousel/uploaded_image_3_1763672612958.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 600px; object-position: center;" alt="Slide 4">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('assets/img/carousel/uploaded_image_4_1763672612958.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 600px; object-position: center;" alt="Slide 5">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body mx-3 mx-md-4 mb-5 mt-n2 shadow-none border">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-2">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('assets') }}/img/logos/icon.png" class="img-fluid" style="max-height: 150px;" alt="Logo">
                    </div>
                    <div class="col-md-8 d-flex align-items-center">
                        <div>
                            <h3 class="mb-1 font-weight-bold text-primary">Book Your Shoot Today!</h3>
                            <p class="mb-0 text-muted">Choose your package and let us capture your best moments.</p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($bookingStatus != null)
                <div class="alert alert-success alert-dismissible text-white fade show my-3" role="alert">
                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-text"><strong>Success!</strong> {{ $bookingStatus }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('paymentStatus'))
                <div class="alert alert-info alert-dismissible text-white fade show my-3" role="alert">
                    <span class="alert-icon"><i class="ni ni-bell-55"></i></span>
                    <span class="alert-text"><strong>M-Pesa Payment!</strong> {{ session('paymentStatus') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible text-white fade show my-3" role="alert">
                    <span class="alert-icon"><i class="ni ni-fat-remove"></i></span>
                    <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card-body p-3">
                <div class="row mb-5">
                    <div class="col-12">
                        <h4 class="text-center mb-4 font-weight-light text-uppercase tracking-wide">Rate Card</h4>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body text-center p-4">
                                        <h6 class="text-uppercase text-muted text-xs font-weight-bolder mb-3">High-End Shoot</h6>
                                        <h2 class="mb-0 font-weight-bold">Ksh 1000</h2>
                                        <p class="text-xs text-muted mb-4">per photo</p>
                                        <hr class="horizontal dark my-3">
                                        <p class="mb-0 text-sm">Premium editing & retouching</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body text-center p-4">
                                        <h6 class="text-uppercase text-muted text-xs font-weight-bolder mb-3">Custom Shoot</h6>
                                        <h2 class="mb-0 font-weight-bold">Ksh 500</h2>
                                        <p class="text-xs text-muted mb-4">per photo</p>
                                        <hr class="horizontal dark my-3">
                                        <p class="mb-0 text-sm">Standard editing</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body text-center p-4">
                                        <h6 class="text-uppercase text-muted text-xs font-weight-bolder mb-3">Outdoor Shoot</h6>
                                        <h2 class="mb-0 font-weight-bold">Ksh 1000</h2>
                                        <p class="text-xs text-muted mb-4">per photo</p>
                                        <hr class="horizontal dark my-3">
                                        <p class="mb-0 text-sm">Minimum 15 photos</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-xs mb-1 text-muted">* Booking fees are non-refundable.</p>
                            <p class="text-xs mb-0 text-muted">* Submission of edited photos takes 3-5 business days.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent='save'>
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
                                <option value="walkin">Walk-in</option>
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
                            <div class="row">
                                <div class="col">
                                    Makeup
                                    <input type="checkbox" name="outfit" wire:model="makeup" id=""
                                        value="Booked">
                                </div>
                                <div class="col">
                                    Hair
                                    <input type="checkbox" wire:model="hair" name="hair" id=""
                                        value="Booked">
                                </div>
                                <div class="col">
                                    Outfit
                                    <input type="checkbox" name="outfit" wire:model="outfit" id=""
                                        value="Booked">
                                </div>

                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="socialConsentCheck" wire:model="socialConsent">
                                <label class="form-check-label" for="socialConsentCheck">
                                    I consent to my photos being shared on the company's social media platforms (optional)
                                </label>
                            </div>
                            <button type="submit" class="btn m-3 bg-gradient-dark">Book!</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
