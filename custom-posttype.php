<?php


function create_specialties_post_type() {
    register_post_type('specialities',
    array(
        'labels' => array(
            'name' => __('Specialties'),
            'singular_name' => __('Specialty')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'specialities-cpt', 'with_front' => false), // 'with_front' => false ensures no prefix from permalink structure
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
		'show_in_rest' => true, 
    )
);

}
add_action('init', 'create_specialties_post_type');

function create_specialties_taxonomies() {
    // Location Taxonomy
    register_taxonomy(
        'location',
        'specialities',
        array(
            'label' => __('Location'),
            'rewrite' => array('slug' => 'location'),
            'hierarchical' => true,
        )
    );

    // Treatment Category Taxonomy
    register_taxonomy(
        'treatment_category',
        'specialities',
        array(
            'label' => __('Treatment Category'),
            'rewrite' => array('slug' => 'treatment-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_specialties_taxonomies');

function custom_specialties_permalink($permalink, $post) {
    if ($post->post_type == 'specialities') {
        $terms = wp_get_post_terms($post->ID, 'location');
        $location = !empty($terms) ? $terms[0]->slug : 'no-location';
        
        $terms = wp_get_post_terms($post->ID, 'treatment_category');
        $category = !empty($terms) ? $terms[0]->slug : 'no-category';

        return home_url('/specialities/' . $location . '/' . $category . '/' . $post->post_name . '/');
    }
    return $permalink;
}
add_filter('post_type_link', 'custom_specialties_permalink', 10, 2);

function custom_specialities_rewrite_rules() {
    add_rewrite_rule(
        '^specialities/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=specialities&location=$matches[1]&treatment_category=$matches[2]&name=$matches[3]',
        'top'
    );
}
add_action('init', 'custom_specialities_rewrite_rules');
