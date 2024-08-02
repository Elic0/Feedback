<header>
    <nav>
        <img src="../../../images/Mercantec-Logo.png" alt="Mercantec Logo">
        <div class="button-container">
                <?php if ($userRole === 'admin') : ?>
                    <button type="button" id="adminPanelButton" class="admin-panel-button" onclick="openAdminPanel()">
                        <ion-icon name="desktop-outline"></ion-icon>
                    </button>
                <?php endif; ?>
		<?php if (isset($_SESSION['username'])) : ?>
                	<?php include 'sidebar.php'; ?>
            	<?php endif; ?>
                <button type="button" id="themeToggle" class="theme-toggle" onclick="toggleTheme()">
                    <i class="gg-sun"></i>
                </button>
                <button type="button" id="loginButton" class="login-button" onclick="logout()">
                    <ion-icon name="exit-outline"></ion-icon>
                </button>
        </div>
    </nav>
</header>
