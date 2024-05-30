<div class="container-fluid px-2 px-md-4">
    {{-- <div class="page-header min-height-500 border-radius-xl mt-4"
        style="background-image: url('https://images.unsplash.com/photo-1617463874381-85b513b3e991?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
        <img src="{{ asset('assets') }}/img/logos/icon.png" class="img-fluid" alt="">
        <span class="mask  bg-gradient-primary  opacity-1"></span>
    </div> --}}
    <div id="wowslider-container1">
        <div class="ws_images">
            <ul>
                <li><img src="data1/images/just_another_adventure___shot_by__muma_pix__make_up_by__glamby_kez_0jpg.jpg"
                        alt="Just another adventure ----‍♂️--__Shot by _muma_pix _Make up by _glamby_kez_0(JPG)"
                        title="Just another adventure ----‍♂️--__Shot by _muma_pix _Make up by _glamby_kez_0(JPG)"
                        id="wows1_0" /></li>
                <li><a href="http://wowslider.net"><img
                            src="data1/images/updated_my_profile__makeup_by__vc_glam_studio_0jpg.jpg" alt="css slideshow"
                            title="Updated my profile --❤️_Makeup by _vc_glam_studio_0(JPG)" id="wows1_1" /></a></li>
                <li><img src="data1/images/her_smile__joycemusoke__hair__meshbeautyparlour_0jpg.jpg"
                        alt="Her smile--__joycemusoke _Hair _meshbeautyparlour_0(JPG)"
                        title="Her smile--__joycemusoke _Hair _meshbeautyparlour_0(JPG)" id="wows1_2" /></li>
            </ul>
        </div>
        <div class="ws_bullets">
            <div>
                <a href="#"
                    title="Just another adventure ----‍♂️--__Shot by _muma_pix _Make up by _glamby_kez_0(JPG)"><span>1</span></a>
                <a href="#" title="Updated my profile --❤️_Makeup by _vc_glam_studio_0(JPG)"><span>2</span></a>
                <a href="#" title="Her smile--__joycemusoke _Hair _meshbeautyparlour_0(JPG)"><span>3</span></a>
            </div>
        </div>
        <div class="ws_script" style="position:absolute;left:-99%"><a href="http://wowslider.net">css slider</a> by
            WOWSlider.com v9.0m</div>
        <div class="ws_shadow"></div>
    </div>
    <script type="text/javascript" src="engine1/wowslider.js"></script>
    <script type="text/javascript" src="engine1/script.js"></script>

    {{-- Wow Slider end here --}}

    <div class="card card-body mx-3 mx-md-4 mb-5 mt-n2">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-2">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset('assets') }}/img/logos/icon.png" height="200" width="200"
                            alt="">
                    </div>
                    <div class="col-md-8 d-flex align-items-end">
                        <span class="h5 mb-3">Book Your Shoot today!</span>
                    </div>
                </div>
            </div>
            @if ($paymentStatus != null)
                <div class="alert alert-success alert-dismissible text-white" role="alert">
                    {{ $paymentStatus }}
                </div>
            @endif
            <div class="card-body p-3">
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
                            <button type="submit" class="btn m-3 bg-gradient-dark">Book!</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
