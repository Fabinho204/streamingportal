<!-- Nav-Bar Starts -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Culture in Lifestyle</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../views/homepage.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <!-- Admin Dashboard Link -->
            <?php
                // Check if the user is logged in
                if (isset($_SESSION['userlogin'])) {
                echo '<li class="nav-item">
                <a class="nav-link" href="../views/cms_movies_list.php">Editing Dashboard</a>
                </li>';
                }
            ?>
        </ul>
        
        <!-- Search Form in header.php -->
        <form class="form-inline my-2 my-lg-0" id="searchBar" method="GET" action="homepage.php"> <!-- The action points to the PHP page -->
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="searchBarInput" name="query" required>
            <button class="btn my-2 my-sm-0" type="submit">Search</button>
        </form>

        <!-- Login / Logout Button -->
        <?php
        if (isset($_SESSION['userlogin']) && $_SESSION['userlogin'] === true) {
            // Display Logout button when logged in
            echo '<a class="btn btn-outline-danger ml-3" href="../elements/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>';
        } else {
            // Display Login button when not logged in
            echo '<a class="btn btn-outline-success ml-3" href="../elements/login.php">
                    <i class="fas fa-sign-in-alt"></i> Login
                  </a>';
        }
        ?>
    </div>
</nav>
<!-- Nav-Bar Ends -->

