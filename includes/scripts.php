<!-- <script src="./assets/bootstrap/js/jquery.slim.min.js"></script> -->
<script src="./assets/bootstrap/js/jquery.min.js"></script>
<script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // Disable right-click context menu
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Disable F12, Ctrl+Shift+I, Ctrl+U (common shortcuts for opening developer tools)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && (e.key === 'i' || e.key === 'u'))) {
            e.preventDefault();
        }
    });
</script>