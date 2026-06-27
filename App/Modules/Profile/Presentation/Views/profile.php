<?php require BASE_PATH."/App/Views/header.php"; ?>

<?php

$image = !empty($profile['profile_image'])
    ? "/career-guidance-system/Public/assets/uploads/profile/".$profile['profile_image']
    : "/career-guidance-system/Public/assets/uploads/profile/default.png";

?>

<main class="profile-page">

<div class="profile-card">

    <div class="profile-header">

        <img
        src="<?= htmlspecialchars($image) ?>"
        alt="Profile"
        class="profile-avatar">

        <h2><?= htmlspecialchars($profile['username']) ?></h2>

        <p><?= htmlspecialchars($profile['email']) ?></p>

    </div>


    <div class="profile-info">

        <div class="info-row">
            <span>Username</span>
            <strong><?= htmlspecialchars($profile['username']) ?></strong>
        </div>

        <div class="info-row">
            <span>Email</span>
            <strong><?= htmlspecialchars($profile['email']) ?></strong>
        </div>

        <div class="info-row">
            <span>Phone</span>
            <strong><?= htmlspecialchars($profile['phone'] ?? '-') ?></strong>
        </div>

        <div class="info-row">
            <span>Gender</span>
            <strong><?= htmlspecialchars($profile['gender'] ?? '-') ?></strong>
        </div>

        <div class="info-row">
            <span>Education</span>
            <strong><?= htmlspecialchars($profile['education_level'] ?? '-') ?></strong>
        </div>

        <div class="info-row">
            <span>Date of Birth</span>
            <strong><?= htmlspecialchars($profile['date_of_birth'] ?? '-') ?></strong>
        </div>

        <div class="info-row">
            <span>Address</span>
            <strong><?= htmlspecialchars($profile['address'] ?? '-') ?></strong>
        </div>

    </div>


    <div class="profile-buttons">

        <a href="index.php?page=edit-profile" class="btn-edit">
            <i class="fas fa-user-edit"></i>
            Edit Profile
        </a>

        <a href="#" class="btn-password">
            <i class="fas fa-key"></i>
            Change Password
        </a>

    </div>

</div>

</main>

<?php require BASE_PATH."/App/Views/footer.php"; ?>