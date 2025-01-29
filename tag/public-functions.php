<?php
// Add this function to hide the title
function tags_plus_hide_title() {
    if (is_tag()) {
        $term = get_queried_object();
        $custom_tag_field = get_term_meta($term->term_id, 'custom_tag_field', true);
        if ($custom_tag_field === 'player' || $custom_tag_field === 'team') {
            ?>
            <style>
                .hero-section .page-title {
                    display: none;
                }
            </style>
            <?php
        }
    }
}
add_action('wp_head', 'tags_plus_hide_title');

// Modified display function with fixed image handling
function display_custom_tag_fields($content) {
    if (is_tag()) {
        $term = get_queried_object();
        $custom_tag_field = get_term_meta($term->term_id, 'custom_tag_field', true);
        if ($custom_tag_field) {
            $output = '';
            switch ($custom_tag_field) {
                case 'player':
                    $output .= '<div class="container">';
                    $output .= '<div class="banner player">';
                    
                    // Handle player photo
                    $player_photo_id = get_term_meta($term->term_id, 'player_photo', true);
                    if ($player_photo_id) {
                        $player_photo_url = wp_get_attachment_image_url($player_photo_id, 'full');
                    } else {
                        $player_photo_url = plugin_dir_url(__FILE__) . 'placeholder.png';
                    }
                    
                    $output .= sprintf(
                        '<img src="%s" data-src="%s" alt="Foto della giocatrice" class="lazy loaded" data-was-processed="true">',
                        esc_url($player_photo_url),
                        esc_url($player_photo_url)
                    );
                    
                    $output .= '</div>';
                    $output .= '<div class="info">';
                    $output .= '<h2>' . esc_html($term->name) . '</h2>';
                    $output .= '<div class="stats">';
                    $output .= '<div class="boxes">';

                    $player_fields = [
                        'Data di Nascita' => ['player_birth_date', function($value) { return $value ? date('d/m/Y', strtotime($value)) : '-'; }],
                        'Paese di Nascita' => ['player_nationality', function($value) { return $value ?: '-'; }],
                        'Ruolo' => ['player_role', function($value) { return $value ?: '-'; }]
                    ];

                    foreach ($player_fields as $label => [$field, $format]) {
                        $value = get_term_meta($term->term_id, $field, true);
                        $formatted_value = $format($value);
                        $output .= '<div class="info-box">';
                        $output .= '<div class="info-title"><b>' . esc_html($label) . '</b></div>';
                        $output .= '<div class="info-value">' . esc_html($formatted_value) . '</div>';
                        $output .= '</div>';
                    }

                    $output .= '</div></div></div></div>';
                    break;

                case 'team':
                    $output .= '<div class="container">';
                    $output .= '<div class="banner">';
                    
                    // Handle team photo
                    $team_photo_id = get_term_meta($term->term_id, 'team_photo', true);
                    if ($team_photo_id) {
                        $team_photo_url = wp_get_attachment_image_url($team_photo_id, 'full');
                    } else {
                        $team_photo_url = plugin_dir_url(__FILE__) . 'placeholder.jpg';
                    }
                    
                    $output .= sprintf(
                        '<img src="%s" data-src="%s" alt="Logo della squadra" class="lazy loaded" data-was-processed="true">',
                        esc_url($team_photo_url),
                        esc_url($team_photo_url)
                    );
                    
                    $output .= '</div>';
                    $output .= '<div class="info">';
                    $output .= '<h2>' . esc_html($term->name) . '</h2>';
                    $output .= '<div class="stats">';
                    $output .= '<div class="boxes">';

                    $team_fields = [
                        'Città' => 'team_city',
                        'Paese' => 'team_country',
                        'Colori' => 'team_colors'
                    ];

                    foreach ($team_fields as $label => $field) {
                        $value = get_term_meta($term->term_id, $field, true);
                        $output .= '<div class="info-box">';
                        $output .= '<div class="info-title"><b>' . esc_html($label) . '</b></div>';
                        $output .= '<div class="info-value">' . esc_html($value ?: '-') . '</div>';
                        $output .= '</div>';
                    }

                    $output .= '</div></div></div></div>';
                    break;

                default:
                    $output = '';
                    break;
            }
            return $output;
        }
    }
    return $content;
}
add_filter('term_description', 'display_custom_tag_fields');
?>
