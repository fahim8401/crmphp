<?php
// lib/csrf.php
// CSRF protection disabled as per user request

function csrf_token() {
    return '';
}

function csrf_field() {
    return '';
}

function validate_csrf() {
    // No-op
}
