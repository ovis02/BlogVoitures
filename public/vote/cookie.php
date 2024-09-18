<?php
function createVoteCookie($value) {
    setcookie("voted", $value, time() + (86400 * 30), "/"); // Cookie valide pendant 30 jours
}

function getVoteCookie() {
    if (isset($_COOKIE['voted'])) {
        return $_COOKIE['voted'];
    } else {
        return null;
    }
}

function deleteVoteCookie() {
    setcookie("voted", "", time() - 3600, "/"); // Supprime le cookie en le rendant invalide
}
?>
