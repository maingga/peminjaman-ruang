<?php
session_start(); // Pastikan session dimulai di awal

// Fungsi untuk cek apakah admin sudah login
function isAdminLoggedIn() {
    return isset($_SESSION['admin']);
}

// Fungsi untuk upload dan resize image
function upload_and_resize_image($file_input_name, $target_dir = 'uploads/', $max_width = 1200, $max_height = 700)
{
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] !== 0) {
        return false;
    }

    $tmp_name = $_FILES[$file_input_name]['tmp_name'];
    $original_name = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION));

    $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $original_name));
    $new_filename = $clean_name . '_' . uniqid() . '.' . $extension;
    $target_path = rtrim($target_dir, '/') . '/' . $new_filename;

    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

    $allowed_types = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($extension, $allowed_types)) return false;

    list($src_w, $src_h, $img_type) = getimagesize($tmp_name);

    switch ($img_type) {
        case IMAGETYPE_JPEG: $src_img = imagecreatefromjpeg($tmp_name); break;
        case IMAGETYPE_PNG: $src_img = imagecreatefrompng($tmp_name); break;
        case IMAGETYPE_GIF: $src_img = imagecreatefromgif($tmp_name); break;
        case IMAGETYPE_WEBP: $src_img = imagecreatefromwebp($tmp_name); break;
        default: return false;
    }

    $ratio = min($max_width / $src_w, $max_height / $src_h);
    $new_w = (int)($src_w * $ratio);
    $new_h = (int)($src_h * $ratio);

    $dst_img = imagecreatetruecolor($new_w, $new_h);

    if (in_array($img_type, [IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP])) {
        imagecolortransparent($dst_img, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
    }

    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);

    switch ($img_type) {
        case IMAGETYPE_JPEG: imagejpeg($dst_img, $target_path, 90); break;
        case IMAGETYPE_PNG: imagepng($dst_img, $target_path); break;
        case IMAGETYPE_GIF: imagegif($dst_img, $target_path); break;
        case IMAGETYPE_WEBP: imagewebp($dst_img, $target_path, 90); break;
    }

    imagedestroy($src_img);
    imagedestroy($dst_img);

    return $new_filename;
}
?>
