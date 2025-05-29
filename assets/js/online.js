$(document).ready(function () {
    function updateLastSeenTime() {
        $.post("actions/updating_last_seen.php");
    }

    updateLastSeenTime();
    setInterval(updateLastSeenTime, 300000);
});