@extends('layouts.admin')
@section('content')
    @php
        use App\Models\Setting;
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>Admin Settings</h4>
            </div>
            <div class="card-body">
                <form id="settingsForm">
                    @csrf

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="print_module" name="print_module" 
                               {{ Setting::getValue('print_module') == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="print_module">Activate Print Module</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="payment_module" name="payment_module" 
                               {{ Setting::getValue('payment_module') == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="payment_module">Activate Payment Module</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="smtp_module" name="smtp_module" 
                               {{ Setting::getValue('smtp_module') == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="smtp_module">Activate SMTP Module</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="price_module" name="price_module" 
                               {{ Setting::getValue('price_module') == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="price_module">Activate Price Module</label>
                    </div>

                    <button type="submit" class="btn btn-success mt-3 w-100">Save Settings</button>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#settingsForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: '{{ url("/admin/settings/update") }}',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    success: function(response) {
                        alert("✅ Settings updated successfully!");
                    },
                    error: function(xhr) {
                        alert("❌ Error updating settings!");
                    }
                });
            });
        });
    </script>
@endsection