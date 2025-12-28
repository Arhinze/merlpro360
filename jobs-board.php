<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs | MERL PRO 360</title>
    <style>
        /* --- REUSING CORE STYLES FROM MAIN PAGE --- */
        :root {
            --primary: #005f73; 
            --secondary: #0a9396; 
            --accent: #ee9b00; 
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
            background-color: var(--light); /* Slightly grey background for list contrast */
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: var(--accent);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: #ca8200;
            transform: translateY(-2px);
        }

        /* --- HEADER (Consistent) --- */
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
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -1px;
        }
        .logo span { color: var(--accent); }

        .nav-links { display: flex; gap: 30px; }
        .nav-links a { font-weight: 500; color: var(--dark); transition: var(--transition); }
        .nav-links a:hover, .nav-links a.active { color: var(--accent); }
        
        .cta-header {
            padding: 8px 20px;
            background: var(--primary);
            color: var(--white) !important;
            border-radius: 4px;
        }

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

        /* --- MINI HERO --- */
        .page-header {
            background: var(--primary);
            color: var(--white);
            padding: 140px 0 60px; /* Top padding accounts for fixed header */
            text-align: center;
        }

        .page-header h1 { font-size: 2.5rem; margin-bottom: 10px; }
        .page-header p { opacity: 0.9; max-width: 600px; margin: 0 auto; }

        /* --- JOBS LIST STYLES --- */
        .jobs-section { padding: 60px 0; }

        .filter-bar {
            background: var(--white);
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
            display: flex;
            gap: 15px;
        }

        .filter-bar input, .filter-bar select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            flex: 1;
        }

        .job-card {
            background: var(--white);
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid transparent;
            transition: var(--transition);
        }

        .job-card:hover {
            border-left: 5px solid var(--accent);
            transform: translateX(5px);
            box-shadow: var(--shadow);
        }

        .job-info h3 {
            color: var(--primary);
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .job-meta {
            display: flex;
            gap: 20px;
            color: var(--grey);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .job-meta span { display: flex; align-items: center; gap: 5px; }

        .job-tags span {
            background: #e0f2f1;
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* --- FOOTER (Consistent) --- */
        footer {
            background: var(--dark);
            color: #aaa;
            padding: 60px 0 20px;
            margin-top: auto;
        }
        .copyright { text-align: center; border-top: 1px solid #333; padding-top: 20px; margin-top: 40px;}

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .filter-bar { flex-direction: column; }
            .job-card { flex-direction: column; align-items: flex-start; gap: 20px; }
            .job-card .btn { width: 100%; text-align: center; }
            .job-meta { flex-direction: column; gap: 5px; }
        }

        .get-a-quote-div {
            margin-top:15px
        }

        .get-a-quote-link {
            color: var(--primary);
            font-weight:bold;
        }

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
                <a href="/" class="logo">MERL PRO <span> 360</span>.</a>

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
                <p>&copy; 2024 MERL PRO 360 Consultancy. All Rights Reserved.</p>
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