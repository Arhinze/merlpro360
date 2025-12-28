<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs | MERL PRO 360</title>
    <style>
        /* --- RESET & VARIABLES --- */
        :root {
            --primary: #005f73; /* Professional Teal */
            --secondary: #0a9396; /* Lighter Teal */
            --accent: #ee9b00; /* Energetic Coral/Gold */
            --dark: #1b263b;
            --light: #f8f9fa;
            --white: #ffffff;
            --grey: #6c757d;
            --shadow: 0 4px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--white);
            overflow-x: hidden; /* Prevents side scrolling */
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        img { max-width: 100%; display: block; }

        /* --- UTILITIES --- */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            transition: var(--transition);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: var(--accent);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: #ca8200;
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 2px solid var(--white);
            color: var(--white);
            margin-left: 10px;
        }

        .btn-outline:hover {
            background-color: var(--white);
            color: var(--primary);
        }

        .section-padding { padding: 80px 0; }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .section-title p {
            max-width: 600px;
            margin: 0 auto;
            color: var(--grey);
        }

        .get-a-quote-div {
            margin-top:15px
        }

        .get-a-quote-link {
            color: var(--primary);
            font-weight:bold;
        }

        /* --- HEADER & NAVIGATION --- */
        header {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
            position: relative;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
            z-index: 1001; /* Keeps logo above mobile menu */
        }

        .logo span { color: var(--accent); }

        .nav-links { display: flex; gap: 30px; align-items: center; }

        .nav-links a {
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--dark);
            transition: var(--transition);
        }

        .nav-links a:hover { color: var(--accent); }

        .cta-header {
            padding: 8px 20px;
            background: var(--primary);
            color: var(--white) !important;
            border-radius: 4px;
        }

        .cta-header:hover { background: var(--secondary); }

        /* --- HAMBURGER MENU STYLES --- */
        .hamburger {
            display: none; /* Hidden on desktop */
            cursor: pointer;
            z-index: 1001;
        }

        .bar {
            display: block;
            width: 25px;
            height: 3px;
            margin: 5px auto;
            transition: var(--transition);
            background-color: var(--dark);
        }

        /* --- HERO SECTION --- */
        .hero {
            background: linear-gradient(rgba(0, 95, 115, 0.9), rgba(0, 95, 115, 0.7)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 90vh;
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--white);
            margin-top: 80px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
        }

        /* --- ABOUT SECTION --- */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .about-img { position: relative; }
        .about-img img { border-radius: 10px; box-shadow: var(--shadow); }
        .about-img::before {
            content: '';
            position: absolute;
            top: -20px; left: -20px;
            width: 100px; height: 100px;
            background: var(--accent);
            z-index: -1;
            border-radius: 10px;
        }

        /* --- SERVICES CARDS --- */
        .services { background-color: var(--light); }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .service-card {
            background: var(--white);
            padding: 40px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border-bottom: 4px solid transparent;
        }
        .service-card:hover {
            transform: translateY(-10px);
            border-bottom: 4px solid var(--accent);
        }
        .icon { font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; }
        .service-card h3 { margin-bottom: 15px; color: var(--dark); }

        /* --- IMPACT STATS --- */
        .impact { background: var(--primary); color: var(--white); text-align: center; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .stat-item h3 { font-size: 3rem; font-weight: 700; color: var(--accent); margin-bottom: 10px; }

        /* --- CONTACT --- */
        .contact-container {
            display: flex;
            background: var(--white);
            box-shadow: var(--shadow);
            border-radius: 10px;
            overflow: hidden;
        }
        .contact-info { background: var(--primary); color: var(--white); padding: 50px; flex: 1; }
        .contact-form { padding: 50px; flex: 1.5; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: var(--light);
        }

        /* --- FOOTER --- */
        footer { background: var(--dark); color: #aaa; padding: 60px 0 20px; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 40px; }
        .footer-col h4 { color: var(--white); margin-bottom: 20px; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a:hover { color: var(--accent); }
        .copyright { text-align: center; border-top: 1px solid #333; padding-top: 20px; }

        /* --- MOBILE RESPONSIVENESS (UPDATED) --- */
        @media (max-width: 768px) {
            .hamburger {
                display: block; /* Show hamburger icon */
            }

            .hamburger.active .bar:nth-child(2) { opacity: 0; }
            .hamburger.active .bar:nth-child(1) { transform: translateY(8px) rotate(45deg); }
            .hamburger.active .bar:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

            .nav-links {
                position: fixed;
                left: -100%;
                top: 80px; /* Below header */
                gap: 0;
                flex-direction: column;
                background-color: var(--white);
                width: 100%;
                text-align: center;
                transition: 0.3s;
                box-shadow: 0 10px 10px rgba(0,0,0,0.1);
                padding: 20px 0;
            }

            .nav-links.active {
                left: 0; /* Slide in */
            }

            .nav-links li {
                margin: 16px 0;
            }

            .cta-header {
                display: inline-block; /* Fix button layout on mobile */
            }

            /* Adjust Hero Text for Mobile */
            .hero-content h1 { font-size: 2.2rem; }
            .hero-content p { font-size: 1rem; padding: 0 10px; }
            
            /* Stack grids */
            .about-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
            .contact-container { flex-direction: column; }
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <nav>
                <a href="/" class="logo">MERL PRO <span>360</span>.</a>

                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>

                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/#about">About Us</a></li>
                    <li><a href="" class="active">Jobs Board</a></li>
                    <li><a href="/#services">Services</a></li>
                    <li><a href="#" class="cta-header">Post a Job</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h1>Current Opportunities</h1>
            <p>Explore the latest Monitoring, Evaluation, Research, and Data Collection roles in the development sector.</p>
        </div>
    </section>

    <section class="jobs-section">
        <div class="container">
            
            <div class="filter-bar">
                <input type="text" placeholder="Search keywords (e.g., Data, M&E)...">
                <select>
                    <option>All Locations</option>
                    <option>Abuja</option>
                    <option>Lagos</option>
                    <option>Remote</option>
                    <option>Maiduguri</option>
                </select>
                <button class="btn btn-primary">Filter Jobs</button>
            </div>

            <a href = ""><div class="job-card">
                <div class="job-info">
                    <h3>Senior M&E Officer</h3>
                    <div class="job-meta">
                        <span>🏢 Save the Children</span>
                        <span>📍 Abuja, Nigeria</span>
                        <span>🕒 Full Time</span>
                    </div>
                    <div class="job-tags">
                        <span>M&E Frameworks</span>
                        <span>Health Sector</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary" id="apply">Apply Now</a>
            </div></a>

            <div class="job-card">
                <div class="job-info">
                    <h3>Field Data Enumerator (Hausa Speaking)</h3>
                    <div class="job-meta">
                        <span>🏢 Action Against Hunger</span>
                        <span>📍 Kano, Nigeria</span>
                        <span>🕒 Contract (3 Months)</span>
                    </div>
                    <div class="job-tags">
                        <span>Data Collection</span>
                        <span>ODK / Kobo</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Impact Evaluation Consultant</h3>
                    <div class="job-meta">
                        <span>🏢 Bill & Melinda Gates Foundation</span>
                        <span>📍 Remote / Hybrid</span>
                        <span>🕒 Consultancy</span>
                    </div>
                    <div class="job-tags">
                        <span>Analysis</span>
                        <span>Report Writing</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Data Quality Assurance Manager</h3>
                    <div class="job-meta">
                        <span>🏢 Malaria Consortium</span>
                        <span>📍 Lagos, Nigeria</span>
                        <span>🕒 Full Time</span>
                    </div>
                    <div class="job-tags">
                        <span>QA/QC</span>
                        <span>Health Data</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Qualitative Researcher</h3>
                    <div class="job-meta">
                        <span>🏢 Mercy Corps</span>
                        <span>📍 Maiduguri, Nigeria</span>
                        <span>🕒 Contract</span>
                    </div>
                    <div class="job-tags">
                        <span>FGDs</span>
                        <span>KIIs</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Regional MEL Advisor</h3>
                    <div class="job-meta">
                        <span>🏢 Plan International</span>
                        <span>📍 Nairobi, Kenya (Regional)</span>
                        <span>🕒 Full Time</span>
                    </div>
                    <div class="job-tags">
                        <span>Strategy</span>
                        <span>Leadership</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>GIS Mapping Specialist</h3>
                    <div class="job-meta">
                        <span>🏢 UN Habitat</span>
                        <span>📍 Abuja, Nigeria</span>
                        <span>🕒 Contract</span>
                    </div>
                    <div class="job-tags">
                        <span>ArcGIS</span>
                        <span>Spatial Analysis</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Quantitative Data Analyst</h3>
                    <div class="job-meta">
                        <span>🏢 Innovations for Poverty Action (IPA)</span>
                        <span>📍 Accra, Ghana</span>
                        <span>🕒 Full Time</span>
                    </div>
                    <div class="job-tags">
                        <span>Stata/R</span>
                        <span>Econometrics</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Field Supervisor (WASH Project)</h3>
                    <div class="job-meta">
                        <span>🏢 WaterAid</span>
                        <span>📍 Enugu, Nigeria</span>
                        <span>🕒 6 Month Contract</span>
                    </div>
                    <div class="job-tags">
                        <span>Team Lead</span>
                        <span>Field Work</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="job-card">
                <div class="job-info">
                    <h3>Database Management Officer</h3>
                    <div class="job-meta">
                        <span>🏢 International Rescue Committee</span>
                        <span>📍 Yola, Nigeria</span>
                        <span>🕒 Full Time</span>
                    </div>
                    <div class="job-tags">
                        <span>SQL</span>
                        <span>DHIS2</span>
                    </div>
                </div>
                <a href="#" class="btn btn-primary">Apply Now</a>
            </div>

        </div>
    </section>

    <footer>
        <div class="container">
            <div class="logo" style="margin-bottom: 20px;">MERL PRO <span>360</span>.</div>
            <p>Connecting development professionals with impact-driven opportunities.</p>
            <div class="copyright">
                <p>&copy; 2025 MERL PRO 360 Consultancy. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const hamburger = document.querySelector(".hamburger");
        const navLinks = document.querySelector(".nav-links");
        const links = document.querySelectorAll(".nav-link");

        // Toggle menu on click
        hamburger.addEventListener("click", () => {
            hamburger.classList.toggle("active");
            navLinks.classList.toggle("active");
        });

        // Close menu when a link is clicked
        links.forEach(link => {
            link.addEventListener("click", () => {
                hamburger.classList.remove("active");
                navLinks.classList.remove("active");
            })
        })
    </script>
</body>
</html>