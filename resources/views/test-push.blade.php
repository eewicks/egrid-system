<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Push Notifications - SOLECO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-bell me-2"></i>
                            Web Push Notification Test
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This page tests the Web Push Notification system for SOLECO Energy Grid Monitoring.
                        </div>
                        
                        <div class="mb-4">
                            <h6>Push Status:</h6>
                            <span id="push-status" class="badge badge-warning">Checking...</span>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex">
                            <button class="btn btn-success" id="subscribe-btn" style="display: none;">
                                <i class="fas fa-bell me-2"></i>Enable Notifications
                            </button>
                            <button class="btn btn-warning" id="unsubscribe-btn" style="display: none;">
                                <i class="fas fa-bell-slash me-2"></i>Disable Notifications
                            </button>
                            <button class="btn btn-info" id="test-notification-btn">
                                <i class="fas fa-paper-plane me-2"></i>Test Notification
                            </button>
                            <button class="btn btn-danger" id="simulate-outage-btn">
                                <i class="fas fa-bolt me-2"></i>Simulate Outage
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Instructions:</h6>
                            <ol>
                                <li>Click "Enable Notifications" to subscribe to push notifications</li>
                                <li>Allow browser notifications when prompted</li>
                                <li>Click "Test Notification" to see a sample notification</li>
                                <li>Click "Simulate Outage" to trigger a real outage notification</li>
                            </ol>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('settings.alerts') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Alert Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/webpush.js') }}"></script>
    <script>
        // Add simulate outage functionality
        document.getElementById('simulate-outage-btn').addEventListener('click', async function() {
            try {
                const response = await fetch('/api/webpush/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    alert('Outage simulation sent! Check your notifications.');
                } else {
                    alert('Failed to send outage simulation.');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>
