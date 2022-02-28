<?php

add_role(
    'mfsp_employee',
    __('Employee'),
    array(
        'read'         => true,
        'edit_posts'   => true,
    )
);

// function mfsp_roles_init() {
//     // gets the author role
//     $role = get_role( 'author' );
 
//     // This only works, because it accesses the class instance.
//     // would allow the author to edit others' posts for current theme only
//     $role->add_cap( 'edit_others_posts' ); 
// }

// add_action( 'admin_init', 'mfsp_roles_init');


// $capabilities = array( 'cap_1', 'cap_2', 'cap_3' );
// $role = get_role( 'editor' );

// foreach( $capabilities as $cap ) {
//         $role->add_cap( $cap );
// }