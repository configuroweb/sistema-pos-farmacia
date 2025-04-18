<?php

/********************************************************************
 * logout.php – Cierra la sesión y actualiza lastaccess/last_ip
 * (salvo que el usuario sea el super‑admin con ID = 1)
 ********************************************************************/

include 'topbar.php';          // ← abre sesión, define $dbh y $current_date

/* ---------- Función para obtener la IP pública del visitante ---------- */
function get_client_ip(): string
{
    foreach (
        [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ] as $key
    ) {
        if (!empty($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }
    return 'UNKNOWN';
}

$ip_address = get_client_ip();

/* ---------- Datos del usuario logueado ---------- */
$email = $_SESSION['login_email'] ?? null;
if (!$email) {                       // por si llaman directo a logout.php
    header('Location: index.php');
    exit;
}

/* Obtener el ID del usuario para saber si es el super‑admin (ID = 1) */
$stmt   = $dbh->prepare("SELECT ID FROM users WHERE email = ?");
$stmt->execute([$email]);
$userId = (int)$stmt->fetchColumn();

/* ---------- Guardar hora/IP de salida (excepto para el super‑admin) ---------- */
if ($userId !== 1) {                 // 1 → super‑administrador protegido
    $upd = $dbh->prepare(
        "UPDATE users
            SET lastaccess = :lastaccess,
                last_ip    = :ip
          WHERE ID = :id"
    );
    $upd->execute([
        ':lastaccess' => $current_date,
        ':ip'         => $ip_address,
        ':id'         => $userId
    ]);
}

/* ---------- Destruir sesión y redirigir ---------- */
session_destroy();                   // Vacía $_SESSION y elimina la cookie
header('Location: index.php');       // Vuelve a la pantalla de login
exit;
