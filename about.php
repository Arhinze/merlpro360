<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

Index_Segments::header(); 
?>

<section class="page-header">
    <div class="container">
        <h1>About MERL PRO 360</h1>
        <p>Architects of Impact. Guardians of Data.</p>
    </div>
</section>

<section class="section-padding" style="padding-top: 0;">
    <div class="container">
        <div class="mvv-grid">
            <div class="mvv-card">
                <h3>Our Mission</h3>
                <p>To empower African NGOs with the data-driven strategies and operational excellence needed to secure funding and maximize social impact.</p>
            </div>
            <div class="mvv-card">
                <h3>Our Vision</h3>
                <p>A development sector where every decision is backed by evidence, and every non-profit has the capacity to sustain its own future.</p>
            </div>
            <div class="mvv-card">
                <h3>Our Core Values</h3>
                <p>Integrity in Data. Transparency in Action. Excellence in Delivery. We believe that true impact must be measurable.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="story-section">
            <div class="story-image">
                <img src="https://images.unsplash.com/photo-1531545514256-b1400bc00f31?auto=format&fit=crop&w=800&q=80" alt="Team working on strategy">
            </div>
            <div class="story-content">
                <h5 style="color: var(--accent); font-weight: 700; text-transform: uppercase;">Our Story</h5>
                <h2 style="font-size: 2.2rem; color: var(--primary); margin-bottom: 20px;">Bridging the Gap Between Passion and Proof.</h2>
                <p>MERL PRO 360 was founded on a simple observation: Many NGOs do incredible work but struggle to prove it. They have the passion, but often lack the technical Monitoring, Evaluation, Research, and Learning (MERL) systems that major donors require.</p>
                <div class="story-highlight">
                    "We didn't just want to be consultants. We wanted to be partners who build systems that last long after we leave."
                </div>
                <p>Since 2018, we have transitioned from a small data-collection team to a full-service consultancy, helping organizations across West Africa secure over $50M in funding through better data storytelling.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding approach-section">
    <div class="container">
        <div class="section-title">
            <h2>The "360" Methodology</h2>
            <p>Our holistic cycle ensures your project is covered from concept to close-out.</p>
        </div>
        <div class="process-grid">
            <div class="process-step">
                <div class="step-number">1</div>
                <h3>Monitor</h3>
                <p>Real-time data tracking systems to keep your project on course.</p>
            </div>
            <div class="process-step">
                <div class="step-number">2</div>
                <h3>Evaluate</h3>
                <p>Mid-term and end-line assessments to measure tangible success.</p>
            </div>
            <div class="process-step">
                <div class="step-number">3</div>
                <h3>Research</h3>
                <p>Deep-dive studies to understand the root causes of community issues.</p>
            </div>
            <div class="process-step">
                <div class="step-number">4</div>
                <h3>Learn</h3>
                <p>Feedback loops that turn data into lessons for future growth.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Meet the Experts</h2>
            <p>A diverse team of data scientists, development practitioners, and strategists.</p>
        </div>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-img">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=500&q=80" alt="CEO">
                </div>
                <div class="team-info">
                    <h4>Dr. Adebayo Johnson</h4>
                    <span>Lead Consultant / Founder</span>
                    <p>PhD in Development Economics with 15 years experience in USAID and UN projects.</p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-img">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=500&q=80" alt="Head of Ops">
                </div>
                <div class="team-info">
                    <h4>Sarah Williams</h4>
                    <span>Head of Research</span>
                    <p>Expert in qualitative research and community-led data collection methods.</p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-img">
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=500&q=80" alt="Data Analyst">
                </div>
                <div class="team-info">
                    <h4>Emmanuel Okon</h4>
                    <span>Senior Data Analyst</span>
                    <p>Specialist in ODK, PowerBI, and GIS mapping for complex field environments.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding" style="background: var(--primary); color: var(--white); text-align: center;">
    <div class="container">
        <h2 style="margin-bottom: 20px;">Ready to professionalize your impact?</h2>
        <p style="margin-bottom: 30px; opacity: 0.9;">Join the 150+ NGOs who trust MERL PRO 360 with their data strategy.</p>
        <a href="index.html#contact" class="btn" style="background: var(--accent); color: var(--white);">Contact Us Today</a>
    </div>
</section>

<?php
Index_Segments::footer(); 
?>