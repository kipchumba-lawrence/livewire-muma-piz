<div class="container-fluid py-4">
    <div class="row">
        <h3>Pipeline Overview</h3>
        <hr>
    </div>
    <style>
        #edit-card {
            margin: 10px;
            border-radius: 20px !important;
            padding: 13px;
            background-color: rgba(125, 224, 136, 0.114);
            float: left;
            width: 250px;
            height: auto;
            cursor: pointer;
            transition: all .4s ease-in-out;
        }

        #edit-card:hover {
            transform: scale(1.05);
        }
    </style>
    <div class="row">
        @foreach ($pipelines as $pipeline)
            <div class="card" id="edit-card">
                <a href="{{ Route('view-pipeline', $pipeline->id) }}">
                    <span class="text-bold">Name: {{ $pipeline->customer_name }} </span>
                    <br>
                    <span class="text-sm">Time: <?php
                    // Convert the timestamp string to a DateTime object
                    $bookedTime = \Carbon\Carbon::parse($pipeline->booked_time);
                    
                    // Display the human-readable difference
                    echo $bookedTime->diffForHumans();
                    ?></span>

                    <div class="d-flex justify-content-end">
                        <span class="text-xxs text-end">click to view details</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
