<?php
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
class dbuser_data extends WP_List_Table{
    private $_items;
    function __construct($data){
        parent::__construct();
        $this->_items = $data;
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
        $e_link = wp_nonce_url( admin_url('?page=database_demo&id=' . $item['id']), 'dbdemo_url', 'url');
        $d_link = wp_nonce_url( admin_url('?page=database_demo&action=delete&id=' . $item['id']), 'dbdemo_url', 'url');
        $edit = '<a href="' . $e_link . '">Edit</a>';
        $delete = '<a href="' . $d_link . '">Delete</a>';
        return $edit ." ". $delete;
    }
    function prepare_items(){
        $per_page = 2;
        $current_page = $this->get_pagenum();
        print_r($current_page);
        $total_items = count($this->_items);
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
        ]);
        $data = array_slice($this->_items, ($current_page - 1) * $per_page, $per_page);
        $this->items = $data;
        $this->_column_headers = array($this->get_columns(),[],[]);
    }
    function column_default($item,$column_name){
        return $item[$column_name];
    }
}
?>