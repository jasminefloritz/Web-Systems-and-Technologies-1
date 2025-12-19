<?php
// admin/header.php - shared admin navigation
// Assumes session is started and $_SESSION['user'] is available
if (!isset($_SESSION)) session_start();
$adminName = $_SESSION['user']['full_name'] ?? 'Admin';
?>

<nav class="sidebar">
    <span><?= htmlspecialchars($adminName); ?></span>
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Manage Students</a>
    <a href="theses.php">Manage Theses</a>
    <a href="profile.php">Profile</a>
    <a href="../auth/logout.php">Logout</a>
</nav>
<style>
    nav.sidebar {
        background-color: #003A8F;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        flex-wrap: wrap;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }


    nav.sidebar .nav-links a {
        text-decoration: none;
        background-color: #FDB913;
        color: #000;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: bold;
        margin-left: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.2s;
    }

    nav.sidebar .nav-links a:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
    }


    @media (max-width: 768px) {
        nav.sidebar {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        nav.sidebar .nav-links a {
            margin-left: 0;
            width: 100%;
            text-align: center;
        }
    }


    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f2f5;
    }


    nav.sidebar {
        background-color: #007bff;

        padding: 1rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    nav.sidebar a {
        text-decoration: none;
        background-color: #ffd700;

        color: #000;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.2s;
    }

    nav.sidebar a:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
    }


    main {
        padding: 2rem;
        max-width: 1200px;
        margin: auto;
    }

    main h2,
    main h3 {
        color: #333;
    }


    form {
        margin: 1rem 0 2rem 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    form input[type="file"],
    form button {
        padding: 0.5rem;
        font-size: 1rem;
    }

    form button {
        width: fit-content;
        background-color: #ffd700;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.2s;
    }

    form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
    }


    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 0.75rem;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f7f7f7;
    }

    tr:hover {
        background-color: #e0e7ff;
    }


    img {
        border-radius: 6px;
    }


    @media (max-width: 768px) {
        nav.sidebar {
            flex-direction: column;
            align-items: center;
        }

        nav.sidebar a {
            width: 80%;
            text-align: center;
        }
    }
</style>