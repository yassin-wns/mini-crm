        </main>

        <footer class="app-footer-bar">
            <div class="footer-left">
                &copy; <?php echo date('Y'); ?> MiniCRM Technologies Inc. All rights reserved. &bull; Enterprise Cloud Platform
            </div>
            <div class="footer-links">
                <a href="#docs" onclick="alert('MiniCRM Documentation & API Reference: All endpoints documented in repository.'); return false;">Documentation</a>
                <span class="footer-sep">&bull;</span>
                <a href="#status" onclick="alert('System Status: All services operating at 99.98% uptime.'); return false;">System Status</a>
                <span class="footer-sep">&bull;</span>
                <a href="#privacy" onclick="alert('Security & Compliance: Built with modern encryption and CSRF protection.'); return false;">Security &amp; Privacy</a>
            </div>
        </footer>
    </div>
</div>

<script src="<?php echo asset('js/app.js'); ?>"></script>
</body>
</html>
