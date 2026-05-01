    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
        <div class="container">
            <!-- Brand / Logo linking to Home -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <span class="material-icons nav-brand-icon">pets</span>
                <span>PetConnect</span>
            </a>

            <!-- Mobile hamburger toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'active' : ''; ?>"
                           href="index.php">
                            <span class="material-icons nav-icon">home</span>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'pets.php') ? 'active' : ''; ?>"
                           href="pets.php">
                            <span class="material-icons nav-icon">menu</span>
                            Browse Pets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'gallery.php') ? 'active' : ''; ?>"
                           href="gallery.php">
                            <span class="material-icons nav-icon">photo_library</span>
                            Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-add <?php echo (basename($_SERVER['PHP_SELF']) === 'add.php') ? 'active' : ''; ?>"
                           href="add.php">
                            <span class="material-icons nav-icon">add_circle</span>
                            Add Pet
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
