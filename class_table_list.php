<?php
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
class dbuser_data extends WP_List_Table{
    function __construct($data){
        parent::__construct();
        $this->items = $data;
    }
    function get_columns(){
        return [
            'cb' => '<input type="checkbox">',
            'p_name' => 'Name',
            'email' => 'Email',
            'action' => 'Action'
        ];
    }
    function column_cb($item){
        return '<input type="checkbox" value="{'.$item["id"].'}">';
    }
    function column_action($item){
        $link = admin_url('?page=database_demo&id=' . $item['id']);
        return '<a href="' . $link . '">Edit</a>';
    }
    function prepare_items(){
        $this->_column_headers = array($this->get_columns(),[],[]);
    }
    function column_default($item,$column_name){
        return $item[$column_name];
    }
}
?>