<?php
// Add dropdown menu for selecting tag type
function custom_tag_fields_dropdown($term) {
    $custom_tag_field = get_term_meta($term->term_id, 'custom_tag_field', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="custom_tag_field"><?php _e('Informazioni aggiuntive:', 'custom_tag_fields'); ?></label></th>
        <td>
            <select name="custom_tag_field" id="custom_tag_field">
                <?php
                $options = array(
                    'none' => __('Nessuno', 'custom_tag_fields'),
                    'player' => __('Giocatrice', 'custom_tag_fields'),
                    'team' => __('Squadra', 'custom_tag_fields')
                );
                foreach ($options as $value => $label) {
                    printf('<option value="%s" %s>%s</option>', esc_attr($value), selected($custom_tag_field, $value, false), esc_html($label));
                }
                ?>
            </select>
        </td>
    </tr>
    <?php
}
add_action('post_tag_add_form_fields', 'custom_tag_fields_dropdown');
add_action('post_tag_edit_form_fields', 'custom_tag_fields_dropdown');

// Save custom field value when adding or editing a tag
function save_custom_tag_fields($term_id) {
    if (isset($_POST['custom_tag_field'])) {
        $custom_tag_field = sanitize_text_field($_POST['custom_tag_field']);
        update_term_meta($term_id, 'custom_tag_field', $custom_tag_field);

        if ($custom_tag_field == 'player') {
            $player_fields = array('player_photo', 'player_number', 'player_team', 'player_nationality', 'player_birth_date', 'player_role');
            foreach ($player_fields as $field) {
                if (isset($_POST[$field])) {
                    $value = ($field === 'player_number') ? intval($_POST[$field]) : sanitize_text_field($_POST[$field]);
                    update_term_meta($term_id, $field, $value);
                }
            }
        } elseif ($custom_tag_field == 'team') {
            $team_fields = array('team_photo', 'team_city', 'team_country', 'team_colors');
            foreach ($team_fields as $field) {
                if (isset($_POST[$field])) {
                    $value = sanitize_text_field($_POST[$field]);
                    update_term_meta($term_id, $field, $value);
                }
            }
        }
    }
}
add_action('created_post_tag', 'save_custom_tag_fields');
add_action('edited_post_tag', 'save_custom_tag_fields');

// Add additional fields for the selected tag type
function custom_tag_fields_additional($term) {
    $custom_tag_field = get_term_meta($term->term_id, 'custom_tag_field', true);
    if ($custom_tag_field == 'player') {
        custom_tag_fields_player($term);
    } elseif ($custom_tag_field == 'team') {
        custom_tag_fields_team($term);
    }
}
add_action('post_tag_edit_form_fields', 'custom_tag_fields_additional');
add_action('post_tag_add_form_fields', 'custom_tag_fields_additional');

function custom_tag_fields_player($term) {
    ?>
    <tr class="form-field">
        <th scope="row"><label for="player_photo"><?php _e('Foto', 'custom_tag_fields'); ?></label></th>
        <td>
            <?php $player_photo = get_term_meta($term->term_id, 'player_photo', true); ?>
            <input type="hidden" name="player_photo" id="player_photo" value="<?php echo esc_attr($player_photo); ?>">
            <input type="button" class="button button-secondary" id="player_photo_button" value="<?php _e('Carica Immagine', 'custom_tag_fields'); ?>">
            <div id="player_photo_preview">
                <?php if (!empty($player_photo)) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_url($player_photo)); ?>" style="max-width: 150px;">
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <script>
        jQuery(document).ready(function($) {
            $('#player_photo_button').click(function() {
                var custom_uploader;
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: '<?php _e('Seleziona o Carica Immagine', 'custom_tag_fields'); ?>',
                    button: {
                        text: '<?php _e('Seleziona Immagine', 'custom_tag_fields'); ?>'
                    },
                    multiple: false
                });
                custom_uploader.on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#player_photo').val(attachment.id);
                    $('#player_photo_preview').html('<img src="' + attachment.url + '" style="max-width: 150px;">');
                });
                custom_uploader.open();
            });
        });
    </script>
    <tr class="form-field">
        <th scope="row"><label for="player_nationality"><?php _e('Nazionalità', 'custom_tag_fields'); ?></label></th>
        <td><input type="text" name="player_nationality" id="player_nationality" value="<?php echo esc_attr(get_term_meta($term->term_id, 'player_nationality', true)); ?>"></td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="player_birth_date"><?php _e('Data di nascita', 'custom_tag_fields'); ?></label></th>
        <td><input type="date" name="player_birth_date" id="player_birth_date" value="<?php echo esc_attr(get_term_meta($term->term_id, 'player_birth_date', true)); ?>"></td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="player_role"><?php _e('Ruolo', 'custom_tag_fields'); ?></label></th>
        <td><input type="text" name="player_role" id="player_role" value="<?php echo esc_attr(get_term_meta($term->term_id, 'player_role', true)); ?>"></td>
    </tr>
    <?php
}

function custom_tag_fields_team($term) {
    ?>
    <tr class="form-field">
        <th scope="row"><label for="team_photo"><?php _e('Foto', 'custom_tag_fields'); ?></label></th>
        <td>
            <?php $team_photo = get_term_meta($term->term_id, 'team_photo', true); ?>
            <input type="hidden" name="team_photo" id="team_photo" value="<?php echo esc_attr($team_photo); ?>">
            <input type="button" class="button button-secondary" id="team_photo_button" value="<?php _e('Carica Immagine', 'custom_tag_fields'); ?>">
            <div id="team_photo_preview">
                <?php if (!empty($team_photo)) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_url($team_photo)); ?>" style="max-width: 150px;">
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <script>
        jQuery(document).ready(function($) {
            $('#team_photo_button').click(function() {
                var custom_uploader;
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: '<?php _e('Seleziona o Carica Immagine', 'custom_tag_fields'); ?>',
                    button: {
                        text: '<?php _e('Seleziona Immagine', 'custom_tag_fields'); ?>'
                    },
                    multiple: false
                });
                custom_uploader.on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#team_photo').val(attachment.id);
                    $('#team_photo_preview').html('<img src="' + attachment.url + '" style="max-width: 150px;">');
                });
                custom_uploader.open();
            });
        });
    </script>
    <tr class="form-field">
        <th scope="row"><label for="team_city"><?php _e('Città', 'custom_tag_fields'); ?></label></th>
        <td><input type="text" name="team_city" id="team_city" value="<?php echo esc_attr(get_term_meta($term->term_id, 'team_city', true)); ?>"></td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="team_country"><?php _e('Paese', 'custom_tag_fields'); ?></label></th>
        <td><input type="text" name="team_country" id="team_country" value="<?php echo esc_attr(get_term_meta($term->term_id, 'team_country', true)); ?>"></td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="team_colors"><?php _e('Colori', 'custom_tag_fields'); ?></label></th>
        <td><input type="text" name="team_colors" id="team_colors" value="<?php echo esc_attr(get_term_meta($term->term_id, 'team_colors', true)); ?>"></td>
    </tr>
    <?php
}

// Add "Tipo" column to tag table before "Conteggio" column
function custom_tag_type_column($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'posts') {
            $new_columns['tag_type'] = __('Tipo', 'custom_tag_fields');
        }
    }

    return $new_columns;
}
add_filter('manage_edit-post_tag_columns', 'custom_tag_type_column');

// Display the value of the type in the "Tipo" column
function custom_tag_type_column_content($content, $column_name, $term_id) {
    if ($column_name === 'tag_type') {
        $custom_tag_field = get_term_meta($term_id, 'custom_tag_field', true);
        switch ($custom_tag_field) {
            case 'player':
                $content = __('Giocatrice', 'custom_tag_fields');
                break;
            case 'team':
                $content = __('Squadra', 'custom_tag_fields');
                break;
            case 'none':
                $content = __('Nessuno', 'custom_tag_fields');
                break;
            default:
                $content = __('N/D', 'custom_tag_fields');
                break;
        }
    }
    return $content;
}
add_filter('manage_post_tag_custom_column', 'custom_tag_type_column_content', 10, 3);

// Make the "Tipo" column sortable
function custom_tag_type_column_sortable($columns) {
    $columns['tag_type'] = 'tag_type';
    return $columns;
}
add_filter('manage_edit-post_tag_sortable_columns', 'custom_tag_type_column_sortable');

// Order tags by type
function custom_tag_type_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('tag_type' === $orderby) {
        $query->set('meta_key', 'custom_tag_field');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'custom_tag_type_column_orderby');

// Add "Tags+" menu to admin sidebar
function add_tags_plus_menu() {
    add_menu_page(
        __('Tags+', 'custom_tag_fields'),
        __('Tags+', 'custom_tag_fields'),
        'manage_options',
        'tags_plus',
        'display_tags_plus_page',
        'dashicons-tag',
        6
    );
}
add_action('admin_menu', 'add_tags_plus_menu');

// Display custom page for tag management
function display_tags_plus_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Tags+', 'custom_tag_fields'); ?></h1>
        <a href="<?php echo admin_url('edit-tags.php?taxonomy=post_tag'); ?>" class="button button-primary" style="margin-bottom: 10px;"><?php _e('Crea nuovo tag', 'custom_tag_fields'); ?></a>
        <form method="get" action="" style="display: flex; align-items: center; margin-bottom: 20px;">
            <input type="hidden" name="page" value="tags_plus">
            <label for="filter_tag_type" style="margin-right: 10px;"><?php _e('Filtra per tipo:', 'custom_tag_fields'); ?></label>
            <select name="filter_tag_type" id="filter_tag_type" style="margin-right: 20px;">
                <option value=""><?php _e('Tutti', 'custom_tag_fields'); ?></option>
                <option value="player" <?php selected(isset($_GET['filter_tag_type']) && $_GET['filter_tag_type'] === 'player'); ?>><?php _e('Giocatrice', 'custom_tag_fields'); ?></option>
                <option value="team" <?php selected(isset($_GET['filter_tag_type']) && $_GET['filter_tag_type'] === 'team'); ?>><?php _e('Squadra', 'custom_tag_fields'); ?></option>
                <option value="none" <?php selected(isset($_GET['filter_tag_type']) && $_GET['filter_tag_type'] === 'none'); ?>><?php _e('Nessuno', 'custom_tag_fields'); ?></option>
            </select>

            <label for="filter_tag_characteristic" style="margin-right: 10px;"><?php _e('Cerca tag:', 'custom_tag_fields'); ?></label>
            <input type="text" name="filter_tag_characteristic" id="filter_tag_characteristic" value="<?php echo isset($_GET['filter_tag_characteristic']) ? esc_attr($_GET['filter_tag_characteristic']) : ''; ?>" style="margin-right: 20px;">

            <input type="submit" class="button button-primary" value="<?php _e('Filtra', 'custom_tag_fields'); ?>">
        </form>

        <?php
        $args = array(
            'taxonomy' => 'post_tag',
            'hide_empty' => false,
            'number' => 0, // Set to 0 to retrieve all tags
        );

        if (isset($_GET['filter_tag_type']) && $_GET['filter_tag_type'] !== '') {
            $args['meta_query'][] = array(
                'key' => 'custom_tag_field',
                'value' => sanitize_text_field($_GET['filter_tag_type']),
            );
        }

        if (isset($_GET['filter_tag_characteristic']) && $_GET['filter_tag_characteristic'] !== '') {
            $args['search'] = sanitize_text_field($_GET['filter_tag_characteristic']);
        }

        $tags = get_terms($args);
        $total_tags = wp_count_terms('post_tag', array_merge($args, array('fields' => 'count')));

        if (!empty($tags)) {
            echo '<table class="wp-list-table widefat fixed striped tags">';
            echo '<thead><tr><th>' . __('Tag', 'custom_tag_fields') . '</th><th>' . __('Tipo', 'custom_tag_fields') . '</th><th>' . __('Stato', 'custom_tag_fields') . '</th><th>' . __('Azioni', 'custom_tag_fields') . '</th></tr></thead>';
            echo '<tbody>';
            foreach ($tags as $tag) {
                $tag_type = get_term_meta($tag->term_id, 'custom_tag_field', true);
                switch ($tag_type) {
                    case 'player':
                        $type_label = __('Giocatrice', 'custom_tag_fields');
                        break;
                    case 'team':
                        $type_label = __('Squadra', 'custom_tag_fields');
                        break;
                    case 'none':
                        $type_label = __('Nessuno', 'custom_tag_fields');
                        break;
                    default:
                        $type_label = __('N/D', 'custom_tag_fields');
                        break;
                }
                echo '<tr>';
                echo '<td>' . esc_html($tag->name) . '</td>';
                echo '<td>' . esc_html($type_label) . '</td>';
                echo '<td>' . check_custom_fields_status($tag) . '</td>';
                echo '<td>';
                echo '<a href="' . esc_url(get_edit_term_link($tag->term_id, 'post_tag')) . '">' . __('Modifica', 'custom_tag_fields') . '</a> | ';
                echo '<a href="' . esc_url(get_term_link($tag->term_id)) . '" target="_blank">' . __('Visualizza', 'custom_tag_fields') . '</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>' . __('Nessun tag trovato.', 'custom_tag_fields') . '</p>';
        }
        ?>
    </div>
    <?php
}

// Function to check the status of custom fields
// Function to check the status of custom fields
function check_custom_fields_status($tag) {
    $custom_tag_field = get_term_meta($tag->term_id, 'custom_tag_field', true);

    if ($custom_tag_field == 'player') {
        $player_fields = array('player_photo', 'player_nationality', 'player_birth_date', 'player_role');
        $missing_fields = array();

        foreach ($player_fields as $field) {
            if (empty(get_term_meta($tag->term_id, $field, true))) {
                $missing_fields[] = $field;
            }
        }

        if (empty($missing_fields)) {
            return '<span style="color: green;">' . __('Tutti i campi sono stati compilati', 'custom_tag_fields') . '</span>';
        } else {
            return '<span style="color: red;">' . __('Manca il campo: ', 'custom_tag_fields') . implode(', ', $missing_fields) . '</span>';
        }
    } elseif ($custom_tag_field == 'team') {
        $team_fields = array('team_photo', 'team_city', 'team_country', 'team_colors');
        $missing_fields = array();

        foreach ($team_fields as $field) {
            if (empty(get_term_meta($tag->term_id, $field, true))) {
                $missing_fields[] = $field;
            }
        }

        if (empty($missing_fields)) {
            return '<span style="color: green;">' . __('Tutti i campi sono stati compilati', 'custom_tag_fields') . '</span>';
        } else {
            return '<span style="color: red;">' . __('Manca il campo: ', 'custom_tag_fields') . implode(', ', $missing_fields) . '</span>';
        }
    } else {
        return '<span style="color: green;">' . __('Nessun campo aggiuntivo richiesto', 'custom_tag_fields') . '</span>';
    }
}
?>
