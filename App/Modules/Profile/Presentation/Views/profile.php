
<?php

$pageTitle = "My Profile";
$extraCss = "/career-guidance-system/Public/assets/css/profile.css";

require BASE_PATH . "/App/Views/header.php";



$image = !empty($profile['profile_image'])
    ? "/career-guidance-system/Public/assets/images/" . $profile['profile_image']
    : "/career-guidance-system/Public/assets/images/image.png";

?>

<main class="profile-page">

    <div class="profile-card">

        <!-- Header -->
        <div class="profile-header">

            <form
                action="index.php?page=update-profile-image"
                method="POST"
                enctype="multipart/form-data"
                id="imageForm">

                <label for="profileImage" class="avatar-upload">

                    <img
                        src="<?= htmlspecialchars($image) ?>"
                        class="profile-avatar"
                        id="avatarPreview"
                        alt="Profile">

                    <div class="avatar-overlay">
                        <i class="fas fa-camera"></i>
                    </div>

                </label>

                <input
                    type="file"
                    id="profileImage"
                    name="profile_image"
                    accept="image/*"
                    hidden>

            </form>

        </div>


        <!-- User Details -->
        <div class="profile-details">

            <h2><?= htmlspecialchars($profile['username']) ?></h2>

            <p><?= htmlspecialchars($profile['email']) ?></p>

            <span class="profile-role">Student Profile</span>

        </div>

        <!-- Information -->
        <div class="profile-info">

            <div class="info-row">
                <span><i class="fas fa-user"></i> Username</span>
                <strong><?= htmlspecialchars($profile['username']) ?></strong>
            </div>

            <div class="info-row">
                <span><i class="fas fa-envelope"></i> Email</span>
                <strong><?= htmlspecialchars($profile['email']) ?></strong>
            </div>

            <div class="info-row">
                <span><i class="fas fa-phone"></i> Phone</span>
                <strong><?= htmlspecialchars($profile['phone'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span><i class="fas fa-venus-mars"></i> Gender</span>
                <strong><?= htmlspecialchars($profile['gender'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span><i class="fas fa-graduation-cap"></i> Education</span>
                <strong><?= htmlspecialchars($profile['education_level'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span><i class="fas fa-calendar"></i> Date of Birth</span>
                <strong><?= htmlspecialchars($profile['date_of_birth'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span><i class="fas fa-map-marker-alt"></i> Address</span>
                <strong><?= htmlspecialchars($profile['address'] ?? '-') ?></strong>
            </div>

        </div>

        <!-- Buttons -->
        <div class="profile-buttons">

            <a href="index.php?page=edit-profile" class="btn-edit">
                <i class="fas fa-user-edit"></i>
                Edit Profile
            </a>

            <a href="index.php?page=change-password" class="btn-password">
                <i class="fas fa-key"></i>
                Change Password
            </a>

        </div>

    </div>

</main>

<?php require BASE_PATH . "/App/Views/footer.php"; ?>

<script src="/career-guidance-system/Public/assets/js/profile.js"></script>

