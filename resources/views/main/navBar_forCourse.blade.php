<nav class="navbar navbar-header navbar-expand-lg" style="background-color: #1D2026">

    <div class="container-fluid">
        @auth
        
        <div class="row" >
            <div class="col-auto">
                <div class="nav-toggle">
                    <button class="btn btn-toggle" onclick="btnBack('{{ url('/class/my-class') }}')" style="margin-right: -35px;">
                        <img style="width: 30%; height:auto" src="{{ url('/HomeIcons/arrow-left-solid.svg') }}"  alt="Back Icon">
                    </button>
                </div>
                <script>
                    function btnBack(url) {
                        window.location.href = url;
                    }
                </script>
            </div>
            <div class="col">
                <div class="text" style="color: rgb(255, 255, 255)">
                    <small id="greeting">Good Morning,</small>
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
        
                    <h4><b>Test Open Course Navbar!</b></h4>
                </div>
            </div>
        </div>
        
        @endauth
    </div>
</nav>
