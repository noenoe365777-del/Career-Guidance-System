<?php

$pageTitle = "Home";

require BASE_PATH . "/App/Views/header.php";

?>



<section class="hero-container">
    <div class="hero-content">
        <h1>Discover the Right <br> Career Path for You</h1>

        <p class="hero-subtitle">
            Take assessments, explore career options, and get
            personalized guidance to build a successful future.
        </p>

        <div class="hero-actions">
            <a href="#" class="btn-primary">Take Assessment</a>
            <a href="#" class="btn-secondary">Explore Careers</a>
        </div>

        <div class="value-props">
            <div class="prop-item">
                <span class="icon-circle icon-blue">
                    <i class="fas fa-bars"></i>
                </span>
                <div>
                    <h4>Discover Your Strengths</h4>
                    <p>Scientifically designed assessments</p>
                </div>
            </div>

            <div class="prop-item">
                <span class="icon-circle icon-green">
                    <i class="fas fa-chart-bar"></i>
                </span>
                <div>
                    <h4>Personalized Analysis</h4>
                    <p>Detailed insights about your skills</p>
                </div>
            </div>

            <div class="prop-item">
                <span class="icon-circle icon-pink">
                    <i class="far fa-circle"></i>
                </span>
                <div>
                    <h4>Career Recommendations</h4>
                    <p>Find best paths that match profile</p>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-image">
        <img src="/career-guidance-system/Public/assets/images/home.png" alt="Graduate">
    </div>
</section>

<section class="how-it-works">
    <h2>How It Works</h2>

    <div class="steps-container">
        <div class="step-card">
            <span class="step-num step-1">1</span>
            <h3>Take Assessment</h3>
            <p>Answer questions about interests and personality.</p>
        </div>

        <div class="step-arrow">→</div>

        <div class="step-card">
            <span class="step-num step-2">2</span>
            <h3>Get Your Results</h3>
            <p>Our system analyzes your responses and generates metrics.</p>
        </div>

        <div class="step-arrow">→</div>

        <div class="step-card">
            <span class="step-num step-3">3</span>
            <h3>View Recommendations</h3>
            <p>Get personalized career options matching your profile.</p>
        </div>

        <div class="step-arrow">→</div>

        <div class="step-card">
            <span class="step-num step-4">4</span>
            <h3>Explore Career Details</h3>
            <p>Explore structural data, salaries, and growth trends.</p>
        </div>
    </div>
</section>


<?php require BASE_PATH . "/App/Views/footer.php"; ?>


