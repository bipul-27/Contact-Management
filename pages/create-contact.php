
<?php

  $message = "";
  $status = "";
  $action = "";
  $contId = "";

  // Find request for View and Edit
  if(isset($_GET['action']) && isset($_GET['contId'])){

    global $wpdb;
    $contId = $_GET['contId'];

    // Action: Edit
    if($_GET['action'] == "edit"){
        $action = "edit";
    }

    // Action: View
    if($_GET['action'] == "view"){
        $action = "view";
    }

    // Single contact information
    $contact = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ems_form_data WHERE id = %d", $contId), ARRAY_A
    );
  }
   
  // Save Form data
   if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["btn_submit"])){

      // Form submitted
      global $wpdb;

      $name = sanitize_text_field($_POST['name']);
      $phoneNo = sanitize_text_field($_POST['phoneNo']);
      $email = sanitize_text_field($_POST['email']);
      $gender = sanitize_text_field($_POST['gender']);
      $designation = sanitize_text_field($_POST['designation']);

      // Action type
      if(isset($_GET['action'])){

        $contId = $_GET['contId'];

        // Edit operation
        $wpdb->update("{$wpdb->prefix}ems_form_data", array(
            "name" => $name,
            "phoneNo" => $phoneNo,
            "email" => $email,
            "gender" => $gender,
            "designation" => $designation
        ), array(
            "id" => $contId
        ));

        $message = "Contact updated successfully";
        $status = 1;
      }else{

        // Add Operation
        // Insert command
        $wpdb->insert("{$wpdb->prefix}ems_form_data", array(
            "name" => $name,
            "phoneNo" => $phoneNo,
            "email" => $email,
            
            "gender" => $gender,
            "designation" => $designation
        ));

        $last_inserted_id = $wpdb->insert_id;

        if($last_inserted_id > 0){

            $message = "Contact saved successfully";
            $status = 1;
        }else{
            $message = "Failed to save contact";
            $status = 0;
        }
      }
   }

?>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <h2>
                <?php 
                if($action == "view"){
                    echo "View Contact";
                }elseif($action == "edit"){
                    echo "Update Contact";
                }else{
                    echo "Add Contact";
                }
            ?>

            </h2>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php 
                if($action == "view"){
                    echo "View Contact";
                }elseif($action == "edit"){
                    echo "Update Contact";
                }else{
                    echo "Add Contact";
                }
            ?>
                </div>
                <div class="panel-body">

                    <?php 
                        if(!empty($message)) {

                            if($status == 1){

                                ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                    <?php
                            }else{

                                ?>
                    <div class="alert alert-danger">
                        <?php echo $message; ?>
                    </div>
                    <?php
                            }
                        }
                    ?>

                    <form action='<?php if($action == "edit"){
                        echo "admin.php?page=contact-management&action=edit&contId=".$contId;
                    }else{
                        echo "admin.php?page=contact-management";
                    } ?>' method="post" id="ems-frm-add-contact">

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text"
                                value="<?php if($action == 'view' || $action == 'edit'){ echo $contact['name']; } ?>"
                                required <?php if($action == "view"){ echo "readonly='readonly'"; } ?>
                                class="form-control" id="name" placeholder="Enter name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="phoneNo">Phone No:</label>
                            <input type="text"
                                value="<?php if($action == 'view' || $action == 'edit'){ echo $contact['phoneNo']; } ?>"
                                class="form-control" id="phoneNo"
                                <?php if($action == "view"){ echo "readonly='readonly'"; } ?>
                                placeholder="Enter phone number" name="phoneNo">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email"
                                value="<?php if($action == 'view' || $action == 'edit'){ echo $contact['email']; } ?>"
                                required class="form-control"
                                <?php if($action == "view"){ echo "readonly='readonly'"; } ?> id="email"
                                placeholder="Enter email" name="email">
                        </div>
                        
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select <?php if($action == "view") {echo "disabled";} ?> name="gender" id="gender"
                                class="form-control">
                                <option value="">Select gender</option>
                                <option value="male"
                                    <?php if(($action == "view" || $action == "edit") && $contact['gender'] == "male"){ echo "selected"; } ?>>
                                    Male</option>
                                <option
                                    <?php if(($action == "view" || $action == "edit") && $contact['gender'] == "female"){ echo "selected"; } ?>
                                    value="female">Female</option>
                                <option
                                    <?php if(($action == "view" || $action == "edit") && $contact['gender'] == "other"){ echo "selected"; } ?>
                                    value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="designation">Designation:</label>
                            <input type="text" required
                                value="<?php if($action == 'view' || $action == 'edit'){ echo $contact['designation']; } ?>"
                                class="form-control" id="designation"
                                <?php if($action == "view"){ echo "readonly='readonly'"; } ?>
                                placeholder="Enter designation" name="designation">
                        </div>

                        <?php 
                        if($action == "view"){
                            // no button
                        }elseif($action == "edit"){
                            ?>
                        <button type="submit" class="btn btn-success" name="btn_submit">Update</button>
                        <?php
                        }else{
                            ?>
                        <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                        <?php
                        }
                        ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
