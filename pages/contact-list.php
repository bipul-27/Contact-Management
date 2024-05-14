<?php
   global $wpdb;
   $message = "";

   // Delete Block
   if($_SERVER['REQUEST_METHOD'] == "POST"){

     if(isset($_POST['cont_del_id']) && !empty($_POST['cont_del_id'])){

        $wpdb->delete("{$wpdb->prefix}ems_form_data", array(
            "id" => intval($_POST['cont_del_id'])
        ));

        $message = "Contact deleted successfully";
     }
   }

   $contacts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ems_form_data", ARRAY_A);
?>

<div class="container">
    <div class="row">
        <div class="col-sm-10">
            <h2>Contact List</h2>

            <div class="panel panel-primary">
                <div class="panel-heading">Contact List</div>
                <div class="panel-body">

                    <?php
                    if(!empty($message)){
                        ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                    <?php
                    }
                    ?>

                    <table class="table" id="tbl-employee">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>#Designation</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(count($contacts) > 0){

                                foreach($contacts as $contact){
                                    ?>
                            <tr>
                                <td><?php echo $contact['id'] ?></td>
                                <td><?php echo $contact['name'] ?></td>
                                <td><?php echo $contact['phoneNo'] ?></td>
                                <td><?php echo $contact['email'] ?></td>
                                <td><?php echo ucfirst($contact['gender']); ?></td>
                                <td><?php echo $contact['designation'] ?></td>
                                <td>
                                    <a href="admin.php?page=contact-management&action=edit&contId=<?php echo $contact['id'] ?>"
                                        class="btn btn-warning">Edit</a>

                                        <form id="frm-delete-contact-<?php echo $contact['id'] ?>" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=list-contact">
    <input type="hidden" name="cont_del_id" value="<?php echo $contact['id'] ?>">
</form>

<a href="javascript:void(0)" onclick="if(confirm('Are you sure want to delete?')){jQuery('#frm-delete-contact-<?php echo $contact['id'] ?>').submit();}" class="btn btn-danger">Delete</a>


                                    <a href="admin.php?page=contact-management&action=view&contId=<?php echo $contact['id'] ?>"
                                        class="btn btn-info">View</a>
                                </td>
                            </tr>
                            <?php
                                }
                            }else{

                                echo "No Contact found";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>