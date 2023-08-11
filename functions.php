<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme        = wp_get_theme();
    wp_enqueue_style( $parenthandle,
        get_template_directory_uri() . '/style.css',
        array(),  // If the parent theme code has a dependency, copy it to here.
        $theme->parent()->get( 'Version' )
    );
    wp_enqueue_style( 'child-style',
        get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get( 'Version' ) // This only works if you have Version defined in the style header.
    );
}

// Personnalisation du thème
function montheme_customize_register($wp_customize)
{
    // Ajout d'une section pour le logo personnalisé
    $wp_customize->add_section('montheme_logo_section', array(
        'title'      => __('Image perso', 'montheme'),
        'priority'   => 30,
    ));

    // Ajout de la fonctionnalité de logo personnalisé
    $wp_customize->add_setting('montheme_logo');

    // Ajout du contrôle pour téléverser le logo personnalisé
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'montheme_logo', array(
        'label'    => __('Téléverser votre logo', 'montheme'),
        'section'  => 'montheme_logo_section',
        'settings' => 'montheme_logo',
    )));
}
add_action('customize_register', 'montheme_customize_register');

// On utilise le hook wp_nav_menu_items pour ajouter le lien "Admin" dans le menu
function ajouter_lien_admin($items, $args)
{
    // On vérifie si l'utilisateur est connecté
    if (is_user_logged_in() && $args->theme_location == 'header') {
        // Si oui, on ajoute le lien "Admin" 
        $items .= '<li><a href="' . admin_url() . '">Admin</a></li>';
    }
    // On retourne la liste d'items
    return $items;
}
// On ajoute la fonction "ajouter_lien_admin" au hook wp_nav_menu_items
add_filter('wp_nav_menu_items', 'ajouter_lien_admin', 10, 2);

// Enregistrement des menus
function theme_register_menus()
{
    register_nav_menus(array(
        'header' => __('Principalle'),
        'footer' => __('Pied de page'),
    ));
}
add_action('init', 'theme_register_menus');