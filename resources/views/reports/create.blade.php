@extends('layouts.app')

@section('content')
<style>
    /* Glassmorphism styles */
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        padding: 20px;
        max-width: 700px;
        margin: 0 auto;
    }

    body {
        background: linear-gradient(135deg, #e2e5eb, #73777e);
        min-height: 100vh;
        color: #111010;
    }

    h2 {
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

    label {
        font-weight: 600;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #111010;
        border-radius: 8px;
        backdrop-filter: blur(6px);
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.35);
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
        color: #000;
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(6px);
        transition: all 0.3s ease;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #000;
    }
</style>

<div class="glass-card mt-5">
    <h2 class="mb-4">Submit New Report</h2>

    <form action="{{ route('reports.store') }}" method="POST">
        @csrf
        @if($errors->any())
    {!! implode('', $errors->all('<div>:message</div>')) !!}
@endif
        <div class="mb-3">
            <label>Designate To</label>
            <select name="designated_to" id="designated_to" class="form-control" required>
                <option value="rescue">Rescue</option>
                <option value="pnp">PNP</option>
                <option value="bfp">BFP</option>
            </select>
            @error('designated_to')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        </div>

        <div class="mb-3">
            <label>Level</label>
            <select name="level" class="form-control" required>
                <option value="1">Level 1 (Low)</option>
                <option value="2">Level 2 (Medium)</option>
                <option value="3">Level 3 (Severe)</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Report Type</label>
            <select name="report_type" id="report_type" class="form-control" required>
                <!-- Options will be populated by JS -->
            </select>
            @error('report_type')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>






        <div class="mb-3" id="description_container" style="display: none;">
            <label>Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
            @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        

        <button class="btn btn-glass">Submit Report</button>
        
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const designatedToSelect = document.getElementById('designated_to');
        const reportTypeSelect = document.getElementById('report_type');
        const descriptionContainer = document.getElementById('description_container');
        const descriptionTextarea = document.getElementById('description');

        const reportTypes = {
            'pnp': [
                'Robbery or theft',
                'Physical assault or street fight',
                'Domestic violence',
                'Gun-related incident',
                'Suspicious person or activity',
                'Missing person report',
                'Drug-related incident',
                'Traffic accident needing police assistance',
                'Vandalism or property damage',
                'Bomb threat or suspicious package',
                'Others'
            ],
            'rescue': [
                'Sudden flooding',
                'Strong earthquake',
                'Landslide occurrence',
                'Typhoon-related emergencies',
                'Flash floods',
                'Building collapse due to disaster',
                'Trapped or injured victims',
                'Fire spreading due to strong winds',
                'Road blockage affecting evacuation',
                'Others'
            ],
            'bfp': [
                'Structural or residential fire',
                'Electrical fire',
                'Grass or forest fire',
                'Gas leak or explosion',
                'Vehicle fire',
                'Rescue from burning buildings',
                'Fire alarm response',
                'Industrial or chemical fire',
                'Fire safety inspection',
                'Fire-related medical emergency',
                'Others'
            ]
        };

        function updateReportTypes() {
            const selectedDesignation = designatedToSelect.value;
            const options = reportTypes[selectedDesignation] || [];
            
            reportTypeSelect.innerHTML = '';
            
            options.forEach(type => {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type;
                reportTypeSelect.appendChild(option);
            });

            // Trigger change to update description visibility
            updateDescriptionVisibility();
        }

        function updateDescriptionVisibility() {
            if (reportTypeSelect.value === 'Others') {
                descriptionContainer.style.display = 'block';
                descriptionTextarea.required = true;
            } else {
                descriptionContainer.style.display = 'none';
                descriptionTextarea.required = false;
                descriptionTextarea.value = ''; // Optional: clear value when hidden
            }
        }

        designatedToSelect.addEventListener('change', updateReportTypes);
        reportTypeSelect.addEventListener('change', updateDescriptionVisibility);

        // Initialize on load
        updateReportTypes();
    });
</script>

@endsection


