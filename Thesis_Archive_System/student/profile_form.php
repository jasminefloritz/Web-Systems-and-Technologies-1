<?php

?>
<div class="profile-card">
    <div class="profile-section stacked-profile">
        <div class="profile-item">
            <h4 class="profile-label">Profile Picture</h4>
            <?php if (!empty($student['profile_picture'])): ?>
                <img src="../uploads/profiles/<?= htmlspecialchars($student['profile_picture']); ?>" alt="Profile" class="profile-pic">
            <?php else: ?>
                <div class="profile-placeholder">No Profile Picture</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="profile_picture" accept="image/*">
                <button type="submit" name="upload_profile" class="upload-btn">Upload Profile Picture</button>
            </form>
        </div>

        <hr class="profile-divider">

        <div class="profile-item">
            <h4 class="profile-label">Signature</h4>
            <?php if (!empty($student['signature'])): ?>
                <img src="../uploads/signatures/<?= htmlspecialchars($student['signature']); ?>" alt="Signature" class="signature-img">
            <?php else: ?>
                <div class="profile-placeholder">No Signature</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="signature" accept="image/*">
                <button type="submit" name="upload_signature" class="upload-btn">Upload Signature</button>
            </form>
        </div>
    </div>
</div>