<?php

/** @var string $pageTitle */
/** @var string $message */
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-5">
                        <div class="mb-4 text-danger">
                            <i class="bi bi-shield-exclamation" style="font-size: 4rem;"></i>
                        </div>
                        <h1 class="h3 mb-3">Access Denied</h1>
                        <p class="text-muted mb-4">You do not have the required permission to view or perform this action.</p>
                        <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard" class="btn btn-primary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
