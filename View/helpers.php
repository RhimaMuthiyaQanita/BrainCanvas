<?php
// Fungsi bantu View: aman menampilkan teks ke HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}