<?php

function showErrorAlert() {
    if (isset($_GET['error'])) {
        $errorType = $_GET['error'];
        $requestedApp = isset($_GET['requested']) ? $_GET['requested'] : '';
        
        $errorMessages = [
            'app_not_found' => "The application '" . htmlspecialchars($requestedApp) . "' could not be found.",
            'app_cannot_open' => "Unable to open the application. It may be undergoing maintenance.",
            'access_denied' => "You don't have permission to access this application.",
            'server_error' => "A server error occurred. Please try again later."
        ];
        
        $message = $errorMessages[$errorType] ?? "An unexpected error occurred: " . $errorType;
        
        return "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('Application Error', `{$message}`);
                const cleanUrl = window.location.pathname;
                window.history.replaceState(null, '', cleanUrl);
            });
        </script>";
    }
    return '';
}

echo showErrorAlert();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a237e, #4a148c);
            color: white;
            min-height: 100vh;
            padding-top: 60px;
        }

        .status-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(26, 35, 126, 0.9);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .time-date {
            font-size: 16px;
            font-weight: 500;
        }

        .login-dropdown {
            position: relative;
            display: inline-block;
        }

        .login-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-icon {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }

        .login-dropdown .dropdown-arrow {
            transform: rotate(0deg);
        }

        .login-dropdown.active .dropdown-arrow {
            transform: rotate(180deg);
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(40, 53, 147, 0.95);
            min-width: 180px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .login-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(5px);
        }

        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
            color: #ff5252;
            font-weight: 500;
        }

        .dropdown-menu a:last-child:hover {
            background: rgba(255, 82, 82, 0.2);
        }

        .apps-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 30px;
            justify-items: center;
        }

        .app-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s;
            width: 140px;
        }

        .app-icon:hover {
            transform: translateY(-5px);
        }

        .app-icon-box {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: background 0.3s;
        }

        .app-icon:hover .app-icon-box {
            background: rgba(255, 255, 255, 0.15);
        }

        .app-icon i {
            font-size: 40px;
            color: rgba(255, 255, 255, 0.8);
        }

        .app-name {
            font-size: 16px;
            font-weight: 500;
            text-align: center;
        }

        /* Custom icons */
        .file-icon {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            width: 70px;
            height: 60px;
            border-radius: 8px 8px 5px 5px;
            position: relative;
            overflow: hidden;
        }

        .file-icon:before {
            content: '';
            position: absolute;
            top: -8px;
            left: 5px;
            width: 60px;
            height: 10px;
            background: #1976D2;
            border-radius: 5px 5px 0 0;
        }

        .file-icon:after {
            content: '';
            position: absolute;
            top: 15px;
            left: 10px;
            width: 15px;
            height: 10px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        .photos-icon {
            background: linear-gradient(135deg, #E91E63, #C2185B);
            width: 70px;
            height: 70px;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        .photos-icon:before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
        }

        .photos-icon:after {
            content: '';
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.4);
            transform: rotate(15deg);
        }

        .server-icon {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            position: relative;
        }

        .server-icon:before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
        }

        .server-icon:after {
            content: '';
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
        }
        .server-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 21px;
            font-weight: 560;
        }
        .elegant-alert {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .elegant-alert.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .alert-content {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 90%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .alert-content h3 {
            margin-bottom: 15px;
            color: #ecf0f1;
            font-size: 24px;
            font-weight: 600;
        }

        .alert-content p {
            color: #bdc3c7;
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.5;
        }

        .alert-btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .alert-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @media (max-width: 600px) {
            .apps-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .status-bar {
                padding: 12px 15px;
            }
            
            .time-date {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Status Bar -->
    <div class="status-bar">
        <div class="time-date" id="time-date">Loading...</div>
        <div class="server-title"><a href="/index.php" style="color:white;text-decoration:none;">My Server</a></div>
        <div class="login-dropdown" id="login-dropdown">
            <button class="login-btn">
                <div class="user-icon">IY</div>
                <span>Isaac Y</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <div class="dropdown-menu">
                <a href="#">About Server</a>
                <a href="#">Settings</a>
                <a href="#">Log Out</a>
            </div>
        </div>
    </div>

    <!-- App Icons -->
    <div class="apps-container">
        <div class="app-icon">
            <div class="app-icon-box">
                <div class="file-icon"></div>
            </div>
            <div class="app-name">File Manager</div>
        </div>
        
        <div class="app-icon">
            <div class="app-icon-box">
                <div class="photos-icon"></div>
            </div>
            <div class="app-name">Photos</div>
        </div>
        
        <div class="app-icon">
            <div class="app-icon-box">
                <div class="server-icon"></div>
            </div>
            <div class="app-name">Server Manager</div>
        </div>
    </div>
    <div id="elegantAlert" class="elegant-alert">
        <div class="alert-content">
            <h3 id="alertTitle">Alert</h3>
            <p id="alertMessage">This is an alert message</p>
            <button id="alertOk" class="alert-btn">OK</button>
        </div>
    </div>
    <script>
        // Update time and date
        function updateTimeDate() {
            const now = new Date();
            const time = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            const date = now.toLocaleDateString([], { weekday: 'short', month: 'short', day: 'numeric' });
            document.getElementById('time-date').innerHTML = `<span style="font-size: 18px; font-weight: 540;">${time}</span> <span style="position: relative; left: 0.2em; font-size: 14px; opacity: 0.8;">${date}</span>`;
        }
        // Update time immediately and then every minute
        updateTimeDate();
        setInterval(updateTimeDate, 60000);
        // Elegant alert function
        function showAlert(title, message) {
            const alert = document.getElementById('elegantAlert');
            const alertTitle = document.getElementById('alertTitle');
            const alertMessage = document.getElementById('alertMessage');
            
            alertTitle.textContent = title;
            alertMessage.textContent = message;
            alert.classList.add('show');
        }

        // Close alert function
        function closeAlert() {
            const alert = document.getElementById('elegantAlert');
            alert.classList.remove('show');
        }

        // Event listeners for the alert
        document.getElementById('alertOk').addEventListener('click', closeAlert);

        // Close alert when clicking outside
        document.getElementById('elegantAlert').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAlert();
            }
        });

        // Close alert with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAlert();
            }
        });

        // Toggle dropdown menu
        document.getElementById('login-dropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });
        
        // Close dropdown when clicking elsewhere
        document.addEventListener('click', function() {
            document.getElementById('login-dropdown').classList.remove('active');
        });
        
        // App icon click handlers
        document.querySelectorAll('.app-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const appName = this.querySelector('.app-name').textContent;
                const redirectPath = "/redirects.php?appname=" + appName.toLowerCase().replace(/\s/g, '');
                try {
                    window.location.href = redirectPath;
                } catch (error) {
                    console.error('Failed to open page:', error);
                    showAlert('Failed to open app', `Failed to open ${appName}: ${error}`);
                }
            });
        });
    </script>
</body>
</html>
