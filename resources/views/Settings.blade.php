<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Settings</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            @include('components.user.UserHeader')

            <!-- SETTINGS PAGE – uses only existing classes from dashboard.css -->
            <div class="p-8">
                <div class="top-header mb-10">
                    <h1 class="text-4xl font-bold">Account Settings</h1>
                </div>

                <div class="settings-container">

                    <!-- Profile Information -->
                    <div class="settings-section">
                        <h3 class="text-2xl font-bold mb-6">Profile Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" value="John Smith" placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" value="john.smith@example.com" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" value="+63 912 345 6789" placeholder="Phone">
                            </div>
                        </div>
                    </div>


                        <div class="flex items-center justify-between mt-6">
                            <span class="font-semibold text-gray-700">Push Notifications</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="settings-section">
                        <h3 class="text-2xl font-bold mb-6">Delivery Address</h3>
                        <div class="form-group">
                            <textarea rows="4" placeholder="Enter your complete delivery address">123 Main Street, Quezon City, Metro Manila, Philippines</textarea>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="text-center mt-10">
                        <button class="save-btn">
                            Save Changes
                        </button>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>