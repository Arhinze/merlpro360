<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Mail.php");

if(isset($_POST["quote"])) {
    $mail_admin = $cm->send_quick_mail("support@$site_url_short", "You just received a quote from $site_name", $admin_quote_sent_message);
    check_mail_status($mail_admin);
    $mail->clearAddresses();

    echo <<<HTML
        <div style="display:block;position:fixed;padding:15px;background-color:#0a9396;color:#fff;border:1px solid #fff;border-radius:9px;box-shadow:3px 3px 3px 0 #888;width:45%;height:15%;top:15%;left:15%;z-index:9" id="message_sent">
            <div style="text-align:right" onclick="close_message_sent()"><i class="la la-times"></i></div>
            <div style="text-align:center">Message Sent <i class="la la-check"></i></div>
        </div>
HTML;
}

class Index_Segments{
    public static function inject($obj) {
        Index_Segments::$pdo = $obj;
    }
    protected static $pdo;

    public static function main_header($site_name = SITE_NAME_SHORT) {
        return <<<HTML
         
HTML;
    }

    public static function site_menu(){
        return <<<HTML
            
HTML;
    }
    
    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $Hi_user = "", $title=SITE_NAME){
        $main_header = Index_Segments::main_header();
        $css_version = filemtime($_SERVER["DOCUMENT_ROOT"]."/static/style.css");

        echo <<<HTML

HTML;
       }

        public static function body($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){
            $site_name_uc = strtoupper($site_name);    
            $site_menu = Index_Segments::site_menu();
       
                echo <<<HTML
                   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>MERL PRO 360 | NGO Consultancy</title>

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
                <a href="/" class="logo">MERL PRO <span> 360</span>.</a>

                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>

                <ul class="nav-links">
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#impact">Our Impact</a></li>
                    <li><a href="/jobs-board">Jobs Board</a></li>
                    <li><a href="#contact" class="cta-header">Get Consultation</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-content">
            <h1>Empowering NGOs with<br>Data-Driven Insights.</h1>
            <p><strong>MERL PRO 360</strong> bridges the gap between donor expectations and on-the-ground reality through superior Monitoring, Evaluation, Research, and Learning.</p>
            <p><a href="#contact" class="btn btn-primary">Start a Project</a></p>
            <p><a href="#services" class="btn btn-outline">Explore Services</a></p>
        </div>
    </section>

    <section id="about" class="section-padding">
        <div class="container">
            <div class="about-grid">
                <div class="about-img">
                    <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=800&q=80" alt="Consulting Team">
                </div>
                <div class="about-text">
                    <h5 style="color: var(--accent); font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Who We Are</h5>
                    <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; line-height: 1.2;">We help non-profits navigate complex challenges.</h2>
                    <p style="margin-bottom: 20px;">At <strong>MERL PRO 360</strong>, we believe that good intentions need great strategy. We provide the operational expertise, fundraising strategies, and monitoring frameworks that allow NGOs to focus on what they do best: changing lives.</p>
                    <ul style="margin-bottom: 30px;">
                        <li style="margin-bottom: 10px;">✓ <strong>10+ Years</strong> of Sector Experience</li>
                        <li style="margin-bottom: 10px;">✓ <strong>Data-Driven</strong> Monitoring & Evaluation</li>
                        <li style="margin-bottom: 10px;">✓ <strong>Sustainable</strong> Fundraising Models</li>
                    </ul>
                    <a href="#contact" class="btn btn-primary" style="background: transparent; color: var(--primary); border: 2px solid var(--primary);">More About Us</a>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="section-padding services">
        <div class="container">
            <div class="section-title">
                <h2>Our Expertise</h2>
                <p>Tailored solutions designed to increase your organization's capacity and efficacy.</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon">📊</div>
                    <h3>Strategic Planning</h3>
                    <p>We help you define clear roadmaps, setting achievable goals and long-term vision that aligns with donor requirements.</p>

                    <p class="get-a-quote-div"><a href="#contact" class="get-a-quote-link">Get a quote <i class="la la-arrow-right"></i></a></p>
                </div>
                <div class="service-card">
                    <div class="icon">💰</div>
                    <h3>Fundraising Strategy</h3>
                    <p>Diversify your income streams with our expert grant writing, donor mapping, and corporate partnership strategies.</p>

                    <p class="get-a-quote-div"><a href="#contact" class="get-a-quote-link">Get a quote <i class="la la-arrow-right"></i></a></p>
                </div>
                <div class="service-card">
                    <div class="icon">📈</div>
                    <h3>Monitoring & Evaluation</h3>
                    <p>Prove your impact. We set up robust M&E frameworks to track data, measure success, and report to stakeholders.</p>

                    <p class="get-a-quote-div"><a href="#contact" class="get-a-quote-link">Get a quote <i class="la la-arrow-right"></i></a></p>
                </div>
                <div class="service-card">
                    <div class="icon">⚖️</div>
                    <h3>Governance & Compliance</h3>
                    <p>Ensure your NGO meets all legal standards and best practices for board governance and financial transparency.</p>

                    <p class="get-a-quote-div"><a href="#contact" class="get-a-quote-link">Get a quote <i class="la la-arrow-right"></i></a></p>
                </div>
                <div class="service-card">
                    <div class="icon">🤝</div>
                    <h3>Capacity Building</h3>
                    <p>Training workshops for your staff and volunteers to enhance operational efficiency and leadership skills.</p>

                    <p class="get-a-quote-div"><a href="#contact" class="get-a-quote-link">Get a quote <i class="la la-arrow-right"></i></a></p>
                </div>
                <div class="service-card">
                    <div class="icon">📢</div>
                    <h3>Advocacy & Comms</h3>
                    <p>Amplify your voice. We craft compelling narratives and advocacy campaigns that drive policy change.</p>

                    <p class="get-a-quote-div"><a href="#contact" class="get-a-quote-link">Get a quote <i class="la la-arrow-right"></i></a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="impact" class="section-padding impact">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>150+</h3>
                    <p>NGOs Consulted</p>
                </div>
                <div class="stat-item">
                    <h3>$50M+</h3>
                    <p>Funds Raised</p>
                </div>
                <div class="stat-item">
                    <h3>20+</h3>
                    <p>Countries Served</p>
                </div>
                <div class="stat-item">
                    <h3>100%</h3>
                    <p>Commitment</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section-padding">
        <div class="container">
            <div class="section-title">
                <h2>Ready to Scale Your Impact?</h2>
                <p>Let's discuss how we can support your mission.</p>
            </div>
            <div class="contact-container">
                <div class="contact-info">
                    <h3 style="margin-bottom: 20px;">Contact Information</h3>
                    <p style="margin-bottom: 20px;">Fill out the form or contact us directly. We answer all inquiries within 24 hours.</p>
                    <p style="margin-bottom: 10px;"><strong>📍 Address:</strong><br>House BO6, Lenzo Pacific, Pyakassa, Airport Road, Abuja, FCT, Nigeria.</p>
                    <p style="margin-bottom: 10px;"><strong>✉️ Email:</strong><br><a href="mailto:admin@merlpro360.com">admin@merlpro360.com</a></p>
                    <p style="margin-bottom: 10px;"><strong>Social Media:</strong><br></p>
                    <p><a href=""><i class="la la-instagram"></i> @merlpro360</a></p>
                    <p><a href=""><i class="la la-facebook"></i> @merlpro360</a></p>
                    <p><a href=""><img src="/static/images/x-icon.png"/> @merlpro360</a></p>
                    <p><a href=""><img src="/static/images/tiktok-icon.png"/> @merlpro360</a></p>
                </div>
                <div class="contact-form">
                    <form method="post" action="/#contact">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" placeholder="Your Name"/>
                        </div>
                        <div class="form-group">
                            <label>Organization Name</label>
                            <input type="text" name="organisation" placeholder="NGO / Company Name"/>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="abc@example.com"/>
                        </div>
                        <div class="form-group">
                            <label>How can we help?</label>
                            <textarea rows="4" name="message" placeholder="Briefly describe your needs..."></textarea>
                        </div>
                        <input type="hidden" name="quote" value="quote_sent"/>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="logo" style="margin-bottom: 20px;">MERL PRO <span>360</span>.</div>
                    <p>Professional consultancy services dedicated to the growth and sustainability of the non-profit sector.</p>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Services</a></li>
                        <li><a href="#">Case Studies</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#">Fundraising</a></li>
                        <li><a href="#">M&E Frameworks</a></li>
                        <li><a href="#">Strategic Planning</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Social Media Pages</h4>
                    <ul>
                        <li><a href=""><i class="la la-instagram"></i> @merlpro360</a></li>
                        <li><a href=""><i class="la la-facebook"></i> @merlpro360</a></li>
                        <li><a href=""><img src="/static/images/x-icon.png"/> @merlpro360</a></li>
                        <li><a href=""><img src="/static/images/tiktok-icon.png"/> @merlpro360</a></li>
                    </ul>
                </div>
            </div>
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

        //close messaage_sent div pop up when clicked:
        function close_message_sent(){
            document.getElementById("message_sent").style = "display:none";
        }
    </script>
</body>
</html>
HTML;
       }
                                                                
       public static function index_scripts(){
        echo <<<HTML

                                                                
HTML;
        }
                                                                
                                                                
        public static function footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = "", $whatsapp_chat = "on", $shopping_cart = "on"){ 
                                                                            
            $index_scripts = Index_Segments::index_scripts();    
                                                                     
        echo <<<HTML
        
HTML;
    }
}

Index_Segments::inject($pdo);
?>