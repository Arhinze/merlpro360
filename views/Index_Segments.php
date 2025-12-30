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
 
    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $title=SITE_NAME){
        
        $css_version = filemtime($_SERVER["DOCUMENT_ROOT"]."/static/style.css");

        echo <<<HTML

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="/static/style.css?$css_version"/>
        <title>MERL PRO 360 | NGO Consultancy</title>
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
HTML;
       }

        public static function body($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){
            $site_name_uc = strtoupper($site_name);    
            $site_menu = Index_Segments::site_menu();
       
            echo <<<HTML

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
                    <p><a href="https://www.instagram.com/merlpro360"><i class="la la-instagram"></i> @merlpro360</a></p>
                    <p><a href=""><i class="la la-facebook"></i> @merlpro360</a></p>
                    <p><a href="https://x.com/merlpro360"><i class="fa-brands fa-x-twitter"></i> @merlpro360</a></p>
                    <p><a href="https://www.tiktok.com/@merlpro360"><i class="fab fa-tiktok"></i> @merlpro360</a></p>
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
HTML;
       }
                                                            
                                                                
                                                                
        public static function footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){ 
                                                                     
        echo <<<HTML
        
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
                        <li><a href="https://www.instagram.com/merlpro360"><i class="la la-instagram"></i> @merlpro360</a></li>
                        <li><a href=""><i class="la la-facebook"></i> @merlpro360</a></li>
                        <li><a href="https://x.com/merlpro360"><i class="fa-brands fa-x-twitter"></i> @merlpro360</a></li>
                        <li><a href="https://www.tiktok.com/@merlpro360"><i class="fab fa-tiktok"></i> @merlpro360</a></li>
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
}

Index_Segments::inject($pdo);
?>