<header class="header-area">
    <!-- Top Header -->
    <div class="top-header bg-primary py-2 d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <!-- Contact Info -->
                <div class="col-lg-8 col-md-8">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item me-4">
                            <i class="fas fa-envelope me-1 text-white"></i>
                            <span class="text-white">Contact@la-ptite-imprimerie.com</span>
                        </li>
                        <li class="list-inline-item">
                            <i class="fas fa-phone me-1 text-white"></i>
                            <span class="text-white">04 75 87 49 80</span>
                        </li>
                    </ul>
                </div>
                <!-- Social Media Links -->
                <div class="col-lg-4 col-md-4 text-end">
                    <a href="https://www.facebook.com/" class="text-white me-3" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/" class="text-white me-3" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Navbar -->
    <div class="sticky-header">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #E50051;">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="index.html">
                    <img src="{{ asset('images/calltop.png') }}" alt="logo" height="50">
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link text-white" href="front/index.html">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="front/about.html">A propos</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="front/services.html">Services</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="front/blog.html">Blog</a></li>

                        <!-- Dropdown Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="front/contact.html" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown">
                                Contact
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="front/contact.html">Contact</a></li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Call Section -->
                    <div class="d-none d-lg-flex align-items-center">
                        <div class="me-2">
                            <img src="{{ asset('images/calltop.png') }}" alt="calltop" height="40">
                        </div>
                        <div>
                            <span class="d-block text-white">Appelez-nous !</span>
                            <strong class="text-white">04 75 87 49 80</strong>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
