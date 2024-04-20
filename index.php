<?php
/**
 * Plugin Name: Database WP
 * Author: Rupom
 * Description: Plugin description
 * Version: 1.0
 */
require_once('class_table_list.php');
function dbdelta_callback(){
    global $wpdb;
    $table_name = $wpdb->prefix.'persons';
    $sql = "CREATE TABLE {$table_name}(
        id INT NOT NULL AUTO_INCREMENT,
        p_name VARCHAR(250),
        email VARCHAR(200),
        PRIMARY KEY (id)
    )" ;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    $wpdb->insert($table_name,[ 
        'p_name' => 'rupom',
        'email' => 'rupom@gmail.com'
    ]);
    $wpdb->insert($table_name,[
        'p_name' => 'rupom11',
        'email' => 'rupom11@gmail.com'
    ]);
}
 register_activation_hook(__FILE__, 'dbdelta_callback');

//  add menu page
add_action( 'admin_menu',function(){
    add_menu_page("Database_demo", "Database_demo", 'manage_options', 'database_demo', 'menu_details_callback');
});
function menu_details_callback(){
    if(isset($_GET['id'])){
        if(!isset($_GET['url']) || !wp_verify_nonce($_GET['url'], 'dbdemo_url')){
            echo 'sorry you are not author';
        }
    }
    global $wpdb;
    $table_name = $wpdb->prefix.'persons';
    $id = $_GET['id'] ?? 0;
    if(isset($_GET['action']) && $_GET['action'] == 'delete'){
        $wpdb->delete($table_name,['id'=> $_GET['id']]);
    }
    if($id){
        $result = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id='{$id}' ");
        if($result){
            echo "Name: {$result->p_name}";
            echo "Emails: {$result->email}";
        }
    }
    ?>
    <form action="" method="POST">
        <?php 
        wp_nonce_field('dbname', 'nonce');
        ?>
        <input type="text" name="name" id="" value="<?php echo $result->p_name ?>">
        <input type="text" name="email" id="" value="<?php echo $result->email ?>">
        <?php 
        if($id){
            echo "<input type='hidden' name='id' value=' ".$id." '>";
            submit_button('Update record');
        }else{
            submit_button('Add record');
        }
        ?>
    </form>
    <div class="db_content">
        <?php
        global $wpdb;
        $db_users = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);
        $dbtable = new dbuser_data($db_users);
        $dbtable->prepare_items();
        $dbtable->display();
        print_r($db_users);
        ?>
    </div>
    <?php
    if(isset($_POST['submit'])){
        $nonce = $_POST['nonce'];
        if(wp_verify_nonce($nonce,'dbname')){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $id = $_POST['id'];
            print_r($id);
            if($id){
                $wpdb->update($table_name,['p_name' => $name, 'email' => $email],['id'=>$id]);
            }else{
                $wpdb->insert($table_name,['p_name' => $name, 'email' => $email]);
            }
        }else{
            echo 'you are not allow this';
        }
    }
}

?> 