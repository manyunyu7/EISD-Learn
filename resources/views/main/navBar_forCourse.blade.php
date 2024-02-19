<nav class="navbar navbar-header navbar-expand-lg" style="background-color: #1D2026">

    <div class="container-fluid">
        @auth

        <div class="text" >
            <small id="greeting" style="color: rgb(112, 112, 112)">Good Morning,</small>
            <script>
                function updateGreeting() {
                    var currentTime = new Date();
                    var currentHour = currentTime.getHours();

                    var greetingElement = document.getElementById('greeting');

                    if (currentHour >= 5 && currentHour < 12) {
                        greetingElement.textContent = 'Good Morning,';
                    } else if (currentHour >= 12 && currentHour < 18) {
                        greetingElement.textContent = 'Good Afternoon,';
                    } else {
                        greetingElement.textContent = 'Good Evening,';
                    }
                }

                // Update greeting initially
                updateGreeting();

                // Update greeting every minute (adjust the interval as needed)
                setInterval(updateGreeting, 60000);
            </script>

            <h4 style="color: #ffffff"><b>Test Open Course Navbar!</b></h4>
        </div>
        @endauth
    </div>
</nav>
