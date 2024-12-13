<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SharePoint Sites</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f2ea 0%, #d1cfce 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 100%;
            color: #fff;
        }

        h1 {
            font-weight: 300;
            color: black;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
        }

        .search-input {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 15px;
            font-size: 1.2rem;
            border-radius: 10px;
            margin-bottom: 30px;
            transition: background-color 0.3s;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input:focus {
            background-color: rgba(255, 255, 255, 0.3);
            outline: none;
        }

        .site-card {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(12px);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .site-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .site-card a {
            display: block;
            padding: 20px;
            font-size: 1.2rem;
            font-weight: 500;
            color: #fff;
            text-decoration: none;
            position: relative;
            transition: color 0.2s;
        }

        .site-card a:hover {
            color: #ffdd59;
        }

        .site-card a i {
            margin-right: 10px;
            color: #f6d365;
        }

        .no-results {
            display: none;
            color: #ff6b6b;
            font-weight: 600;
            text-align: center;
            margin-top: 20px;
        }

        #searchInput::placeholder {
            color: black;
        }

        .site-card a::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2);
            transition: opacity 0.3s;
            opacity: 0;
        }

        .site-card a:hover::after {
            opacity: 1;
        }

        @media (max-width: 576px) {
            .search-input {
                font-size: 1rem;
                padding: 10px;
            }

            .site-card a {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>SharePoint Sites</h1>

        <!-- Search Bar -->
        <input style="color:black" type="text" id="searchInput" class="form-control search-input"
            placeholder="Search for a SharePoint site...">


        <!-- Site List -->
        <div id="siteList">
            @foreach ($sites as $site)
                <div class="card site-card">
                    <a style="color: black" href="{{ route('drives', ['siteId' => $site['id']]) }}">
                        <i class="fas fa-folder"></i>{{ $site['name'] }}
                    </a>
                </div>
            @endforeach
        </div>

        <!-- No results message -->
        <p id="noResults" class="no-results">No matching sites found.</p>

        <!-- Error Handling -->
        @if ($errors->any())
            <div class="alert alert-danger mt-3" role="alert">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Search Functionality -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let siteList = document.getElementById('siteList');
            let sites = siteList.getElementsByClassName('site-card');
            let hasResults = false;

            for (let i = 0; i < sites.length; i++) {
                let siteName = sites[i].textContent || sites[i].innerText;
                if (siteName.toLowerCase().indexOf(filter) > -1) {
                    sites[i].style.display = '';
                    hasResults = true;
                } else {
                    sites[i].style.display = 'none';
                }
            }

            document.getElementById('noResults').style.display = hasResults ? 'none' : 'block';
        });
    </script>
</body>

</html>
