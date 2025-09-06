<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hippo Coffee</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-brown: #6B4E3D;
            --light-beige: #F5F3ED;
            --dark-brown: #4A3529;
        }
        
        body {
            font-family: 'Georgia', serif;
            background-color: var(--light-beige);
        }
        
        .navbar-custom {
            background-color: var(--primary-brown);
            padding: 1rem 0;
            position: relative;
        }
        
        .navbar-custom .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }
        
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #D4AF37;
        }
        
        .navbar-custom .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
            background: transparent;
        }
        
        .navbar-custom .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            width: 1.5em;
            height: 1.5em;
        }
        
        .hero-section {
            height: 300px;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset("images/coffee-hero.jpg") }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        @media (min-width: 768px) {
            .hero-section {
                height: 400px;
            }
        }
        
        .logo-circle {
            width: 60px;
            height: 60px;
            background-color: var(--light-beige);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-brown);
            font-weight: bold;
        }
        
        /* Mobile sidebar styles */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100vh;
            background-color: var(--primary-brown);
            z-index: 1050;
            transition: left 0.3s ease;
            padding-top: 1rem;
        }
        
        .mobile-sidebar.show {
            left: 0;
        }
        
        .mobile-sidebar .nav-link {
            color: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }
        
        .mobile-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #D4AF37;
        }
        
        .mobile-sidebar .sidebar-header {
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            display: flex;
            justify-content: space-between; /* Corrected from 'between' */
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .mobile-sidebar .close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        /* Desktop navbar layout */
        @media (min-width: 992px) {
            .navbar-custom .container {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .navbar-nav-left {
                order: 1;
            }
            
            .desktop-logo {
                order: 2;
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }
            
            .navbar-nav-right {
                order: 3;
            }
        }
        
        .section-title {
            color: var(--primary-brown);
            font-size: 1.75rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        @media (min-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
        }
        
        .about-section {
            padding: 3rem 0;
            background-color: var(--light-beige);
        }
        
        .menu-section {
            padding: 3rem 0;
            background-color: var(--light-beige);
        }
        
        .location-section {
            padding: 3rem 0;
            background-color: var(--light-beige);
        }
        
        .contact-section {
            padding: 3rem 0;
            background-color: #F5F3ED;
        }
        
        @media (min-width: 768px) {
            .about-section,
            .menu-section,
            .location-section,
            .contact-section {
                padding: 4rem 0;
            }
        }
        
        .footer {
            background-color: var(--primary-brown);
            color: white;
            padding: 3rem 0 2rem 0;
        }
        
        .menu-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            height: 100%;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        
        .menu-image {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .menu-content {
            padding: 1.5rem;
        }
        
        .menu-price {
            color: var(--primary-brown);
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .location-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        
        .location-image {
            height: 200px;
            background-color: #C8C8C8;
            border-radius: 8px;
            margin-bottom: 1rem;
            background-size: cover;
            background-position: center;
        }
        
        .contact-banner {
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
            color: white;
            text-align: center;
            padding: 3rem 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        @media (min-width: 768px) {
            .contact-banner {
                padding: 4rem 2rem;
            }
        }
        
        .contact-banner h2 {
            font-size: 1.8rem;
        }
        
        @media (min-width: 768px) {
            .contact-banner h2 {
                font-size: 2.5rem;
            }
        }
        
        .btn-custom {
            background-color: var(--primary-brown);
            border-color: var(--primary-brown);
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 25px;
        }
        
        .btn-custom:hover {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
            color: white;
        }
        
        .carousel-item img {
            height: 300px;
            object-fit: cover;
        }
        
        @media (min-width: 768px) {
            .carousel-item img {
                height: 400px;
            }
        }
        
        .about-image {
            height: 200px;
            background-color: #C8C8C8;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            margin-top: 1rem;
        }
        
        @media (min-width: 768px) {
            .about-image {
                height: 250px;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-header">
            <div class="logo-circle">
                HC
            </div>
            <button class="close-btn" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="navbar-nav">
            <a class="nav-link" href="#home">Home</a>
            <a class="nav-link" href="#about">About Us</a>
            <a class="nav-link" href="#news">News</a>
            <a class="nav-link" href="#menu">Menu</a>
            <a class="nav-link" href="#location">Location</a>
            <a class="nav-link" href="#contact">Contact</a>
            <a class="nav-link" href="#login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </nav>
    </div>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <div class="navbar-nav navbar-nav-left d-none d-lg-flex">
                <a class="nav-link" href="#home">Home</a>
                <a class="nav-link" href="#about">About Us</a>
                <a class="nav-link" href="#news">News</a>
            </div>
            
            <div class="d-flex justify-content-between align-items-center w-100 d-lg-none">
                <button class="navbar-toggler" type="button" id="mobileToggler">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="logo-circle">
                    HC
                </div>
                
                <div style="width: 48px;"></div>
            </div>

            <div class="logo-circle desktop-logo d-none d-lg-block">
                HC
            </div>

            <div class="navbar-nav navbar-nav-right d-none d-lg-flex">
                <a class="nav-link" href="#menu">Menu</a>
                <a class="nav-link" href="#location">Location</a>
                <a class="nav-link" href="#contact">Contact</a>
                <button id="login-btn" class="nav-link">Login</button>
            </div>
        </div>
    </nav>

    <section id="home">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="d-block w-100" alt="Coffee Shop Interior">
                    <div class="carousel-caption d-none d-sm-block">
                        <h1 class="display-6 display-md-4">Welcome to Hippo Coffee</h1>
                        <p class="lead d-none d-md-block">Experience the finest coffee in town</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="d-block w-100" alt="Fresh Coffee Beans">
                    <div class="carousel-caption d-none d-sm-block">
                        <h1 class="display-6 display-md-4">Fresh Roasted Daily</h1>
                        <p class="lead d-none d-md-block">Premium beans from around the world</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="lead">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed justo a
                        lorem tempor fermentum. Mauris in lorem nisl. Maecenas ultricies facilisis
                        leo libero pharetra, mattis lectus vel, mattis nisl. Sed sed eros massa.
                        Cras malesuada eros sem, non facilisis dolor porta sed. Ut vel facilisis
                        lacus. Fusce non nibh dictum, placerat odio sed, scelerisque nibh. Sed in
                        hendrerit eros. Morbi convallis suscipit arcu. Donec molestie
                        consectetur tellus, quis laoreet sapien ultrices in. Aenean pellentesque,
                        nisi in lobortis ullamcorper, purus felis pharetra eros, non blandit lorem
                        velit quis arcu. Pellentesque habitant morbi tristique senectus et netus.
                        Nunc luctus neque vitae nibh vulputate lacuilis. Pellentesque tempor
                        neque a felis mollis, at elementum diam ultrices.
                    </p>
                    <button class="btn btn-custom mt-3">Read More</button>
                </div>
                <div class="col-md-4">
                    <div class="about-image" style="background-image: url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="menu-section">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <h2 class="section-title mb-3 mb-md-0">Our Menu</h2>
                <a href="#" class="text-decoration-none">View More +</a>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="menu-card">
                        <div class="menu-image" style="background-image: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <div class="menu-content">
                            <h5 class="mb-2">Espresso</h5>
                            <p class="text-muted mb-3">Rich and bold espresso shot made from premium arabica beans</p>
                            <div class="menu-price">Rp 15.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="menu-card">
                        <div class="menu-image" style="background-image: url('https://images.unsplash.com/photo-1497515114629-f71d768fd07c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <div class="menu-content">
                            <h5 class="mb-2">Cappuccino</h5>
                            <p class="text-muted mb-3">Perfect blend of espresso, steamed milk, and microfoam art</p>
                            <div class="menu-price">Rp 25.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="menu-card">
                        <div class="menu-image" style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <div class="menu-content">
                            <h5 class="mb-2">Cafe Latte</h5>
                            <p class="text-muted mb-3">Smooth espresso with steamed milk, topped with light foam</p>
                            <div class="menu-price">Rp 28.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="menu-card">
                        <div class="menu-image" style="background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <div class="menu-content">
                            <h5 class="mb-2">Americano</h5>
                            <p class="text-muted mb-3">Classic black coffee with hot water, strong and aromatic</p>
                            <div class="menu-price">Rp 20.000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="location" class="location-section">
        <div class="container">
            <h2 class="section-title">Our Location</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="location-card">
                        <div class="location-image" style="background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <h5>Hippo Coffee Cilacap</h5>
                        <p class="text-muted mb-2">Alamat:</p>
                        <p class="mb-3">Jl. Ahmad Yani No.123, Cilacap, Jawa Tengah</p>
                        <a href="#" class="btn btn-custom btn-sm">View On Maps +</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="location-card">
                        <div class="location-image" style="background-image: url('https://images.unsplash.com/photo-1521017432531-fbd92d768814?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <h5>Hippo Coffee Purwokerto</h5>
                        <p class="text-muted mb-2">Alamat:</p>
                        <p class="mb-3">Jl. Jenderal Sudirman No.456, Purwokerto, Jawa Tengah</p>
                        <a href="#" class="btn btn-custom btn-sm">View On Maps +</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="location-card">
                        <div class="location-image" style="background-image: url('https://images.unsplash.com/photo-1442975631115-c4f7b05b8a2c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')"></div>
                        <h5>Hippo Coffee Yogyakarta</h5>
                        <p class="text-muted mb-2">Alamat:</p>
                        <p class="mb-3">Jl. Malioboro No.789, Yogyakarta, DIY</p>
                        <a href="#" class="btn btn-custom btn-sm">View On Maps +</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="contact-banner">
                        <h2 class="display-5 mb-4">Contact Us If You Need Something</h2>
                        <p class="lead mb-4">We're here to help you with any questions or concerns you might have about our coffee and services.</p>
                        <div class="row justify-content-center">
                            <div class="col-sm-4 mb-3">
                                <i class="fas fa-phone fa-2x mb-3"></i>
                                <h5>Call Us</h5>
                                <p>+62 123 456 7890</p>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <i class="fas fa-envelope fa-2x mb-3"></i>
                                <h5>Email Us</h5>
                                <p>info@hippocoffee.com</p>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <i class="fas fa-map-marker-alt fa-2x mb-3"></i>
                                <h5>Visit Us</h5>
                                <p>Multiple Locations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="logo-circle mb-3">
                        HC
                    </div>
                </div>
                <div class="col-md-3">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="#about" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="#news" class="text-light text-decoration-none">News</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><a href="#menu" class="text-light text-decoration-none">Menu</a></li>
                        <li><a href="#location" class="text-light text-decoration-none">Location</a></li>
                        <li><a href="#contact" class="text-light text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Contacts</h5>
                    <p class="mb-1">
                        <a href="mailto:email@gmail.com" class="text-light text-decoration-none">email@gmail.com</a>
                    </p>
                </div>
                <div class="col-md-2">
                    <h5>Social</h5>
                    <p class="mb-1">
                        <a href="#" class="text-light text-decoration-none">hippocoffee</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar functionality
        const mobileToggler = document.getElementById('mobileToggler');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const closeSidebar = document.getElementById('closeSidebar');

        // Open sidebar
        mobileToggler.addEventListener('click', function() {
            mobileSidebar.classList.add('show');
            mobileOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });

        // Close sidebar
        function closeSidebarFunc() {
            mobileSidebar.classList.remove('show');
            mobileOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        closeSidebar.addEventListener('click', closeSidebarFunc);
        mobileOverlay.addEventListener('click', closeSidebarFunc);

        // Close sidebar when clicking on nav links
        const sidebarLinks = mobileSidebar.querySelectorAll('.nav-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', closeSidebarFunc);
        });
    </script>
    <script>
        document.getElementById('login-btn').addEventListener('click', function() {
            window.location.href = '/login';
        });
    </script>
</body>
</html>