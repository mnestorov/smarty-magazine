<?php
/**
 * Watermark Existing Images
 *
 * This script applies a watermark to all existing images in the WordPress media library.
 * It uses Imagick to add a text watermark to images. You can customize the watermark text,
 * font, size, and opacity. Make sure to backup your images before running this script.
 *
 * Usage: Upload this file to your root directory (where wp-config.php is) and run it from your browser.
 * Note: This script is for demonstration purposes and should be used with caution.
 */

// Load WordPress environment
require_once(dirname(__FILE__) . '/wp-load.php');

// Check if Imagick is available
if (!extension_loaded('imagick') || !class_exists('Imagick')) {
    die('Imagick is not installed. Please install it or use GD with TTF.');
}

// Define watermark settings
$watermark_text = "CRYPTOPOINT.BG"; // Watermark text
$font = get_template_directory() . '/assets/fonts/Bicubik.OTF';
$font_size = 24;
$opacity = 50; // 0-100

if (!file_exists($font)) {
    die('Font file not found at: ' . $font);
}

// Function to apply watermark to an image
function apply_watermark($image_path, $font, $watermark_text, $font_size, $opacity) {
    $image = new Imagick($image_path);
    $draw = new ImagickDraw();

    // Set font properties
    $draw->setFont($font);
    $draw->setFontSize($font_size);
    $draw->setFillColor('white');
    $draw->setFillOpacity($opacity / 100);

    // Calculate position (bottom-right with padding)
    $metrics = $image->queryFontMetrics($draw, $watermark_text);
    $x = $image->getImageWidth() - $metrics['textWidth'] - 10;
    $y = $image->getImageHeight() - $metrics['textHeight'] + $metrics['ascender'] - 10;

    // Add the watermark
    $image->annotateImage($draw, $x, $y, 0, $watermark_text);

    // Save the image
    $image->writeImage($image_path);
    $image->destroy();
}

// Query all image attachments
$args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => -1, // Get all images
);
$attachments = new WP_Query($args);

if ($attachments->have_posts()) {
    $processed = 0;
    while ($attachments->have_posts()) {
        $attachments->the_post();
        $attachment_id = get_the_ID();

        // Get the full-size image path
        $image_path = get_attached_file($attachment_id);
        if (file_exists($image_path)) {
            echo "Processing: $image_path\n";
            apply_watermark($image_path, $font, $watermark_text, $font_size, $opacity);
        }

        // Process all registered sizes
        $metadata = wp_get_attachment_metadata($attachment_id);
        if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
            $upload_dir = wp_upload_dir();
            foreach ($metadata['sizes'] as $size => $size_info) {
                $size_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']) . '/' . $size_info['file'];
                if (file_exists($size_path)) {
                    echo "Processing size ($size): $size_path\n";
                    apply_watermark($size_path, $font, $watermark_text, $font_size, $opacity);
                }
            }
        }

        $processed++;
        // Optional: Limit processing to avoid timeouts (remove or adjust as needed)
        if ($processed >= 50) {
            echo "Processed $processed images. Stopping for now.\n";
            break;
        }
    }
    echo "Done! Processed $processed images.\n";
} else {
    echo "No images found in the media library.\n";
}

// Reset post data
wp_reset_postdata();