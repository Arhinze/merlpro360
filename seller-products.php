<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/seller_Segments.php");

if(isset($_COOKIE["seller_username"]) && isset($_COOKIE["seller_password"])){
    $stmt = $pdo->prepare("SELECT * FROM `sellers` WHERE (seller_username = ? OR seller_email = ?) AND seller_password = ?");
    $stmt->execute([$_COOKIE["seller_username"], $_COOKIE["seller_username"], $_COOKIE["seller_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
        //that means seller is logged in
        seller_Segments::header();
        //print_r($_POST);
        //echo "<br /><br /><b>",print_r($_FILES),"</b>";

        //[array to loop through to upload multiple product images at once]:
        $images_array = ["image1","image2","image3","image4","image5","image6","image7","image8","image9","image10"];
        $img_output = "";

        //Categories Array: ~ replace later on from categories table in database
        $categories_array = ["women", "men", "xenx", "sports", "jewelry", "industrial", "electronics", "kids", "bags", "toy", "crafts", "beauty", "automotive", "garden", "office", "health", "baby", "household", "musical-appliances", "food", "books", "phones-and-accessories"];
        $categories = "";

        //former_price percentage array:
        $fp_array = [3, 4, 5, 6, 7, 8, 9];
        shuffle($fp_array);

        //To Insert Product:
        if(isset($_POST["new_product"])){
            //check if product already exists
            $add_stmt = $pdo->prepare("SELECT * FROM products WHERE product_url = ?");
            $add_stmt->execute([$_POST["new_url"]]);
    
            $add_data = $add_stmt->fetch(PDO::FETCH_OBJ);
            if(!$add_data){//that means this is a unique product url
                //add selected categories:
                foreach ($categories_array as $cat_arr) {
                    if(isset($_POST["add_category_".$cat_arr])) {
                        $categories .= htmlentities($_POST["add_category_".$cat_arr]).";";
                    }
                }

                //then insert new product data:
                $addi_stmt = $pdo->prepare("INSERT INTO products(product_name, product_url, price, former_price, `description`, category) VALUES(?,?,?,?,?,?)");
                
                $addi_stmt->execute([htmlentities($_POST["new_product_name"]), htmlentities($_POST["new_url"]), htmlentities($_POST["new_product_price"]),(htmlentities($_POST["new_product_price"]) + (($fp_array[0]/100)*htmlentities($_POST["new_product_price"]))),htmlentities($_POST["new_product_description"]),$categories]);

                echo "<h4 style='color:green'>Product: ", $_POST["new_product_name"], " added successfully.</h4>";
    
                //upload images:
                $img_i = 0;
                foreach($images_array as $images_ad) { //foreach loop - [images_array] starts
                    $img_i++;
                    if(!empty($_FILES["add_".$images_ad]["name"])){ //if (!empty($_FILES["add_".$images_ad])) starts
                        /* Image Upload Script starts */
                        $target_dir = "static/images/";
                        $target_basename = $_POST["new_url"]."_".time()."_".$img_i.".png";
                        $target_file = $target_dir.$target_basename;
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                        //Check if image file is a actual image or fake image
                        $check_img = getimagesize($_FILES["add_".$images_ad]["tmp_name"]);
                        if ($check_img !== false) {
                            //echo "image security test passed - ".$check_img["mime"].".<br/>";
                            $uploadOk = 1;
                        } else {
                            echo "<div class='invalid'>image security test failed - file is not an image</div>";
                            $uploadOk = 0;
                        }
                        if(file_exists($target_file)) {
                            echo "<div class='invalid'>Sorry, file already exists</div>";
                            $uploadOk = 0;
                        }
        
                        //Allow certain file formats:
                        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                            echo "<div class='invalid'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
                            $uploadOk = 0;
                        }
        
                        //Checking if any $uploadOk = 0 by an error:
                        if ($uploadOk == 0) {
                            echo "<div class='invalid'>Sorry, your file was not uploaded.</div>";
                        //if everything is ok, upload file
                        } else {
                            if (move_uploaded_file($_FILES["add_".$images_ad]["tmp_name"], $target_file)) {
                                //echo "The file ".$target_basename." has been uploaded.<br />";
                                
                                //insert(update) product image(s)
                                $up_stmt = $pdo->prepare("UPDATE products SET $images_ad = ? WHERE product_url = ?");
                                $up_stmt->execute([$target_basename, $_POST["new_url"]]);
                            } else {
                              echo "<div class='invalid'>Sorry, there was an error uploading your file.</div>";
                            }
                        }
                        /* Image Upload Script ends */
                    }//if(!empty($_FILES["add_".$images_ad])) ends
                }//foreach loop - looping around array to upload multiple product images at once ends
            } else {//if product_url exists:
                echo "<h4 style='color:red'>Error, Product: ", $add_data->product_url, " already exists</h4>";
            }
        }
        //end of php script to insert new product



        //To Edit Product: ~ start of PHP script to edit product
        if(isset($_POST["edit_product_id"])){
            //check if product still exists
            $edit_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
            $edit_stmt->execute([$_POST["edit_product_id"]]);
            
            $edit_data = $edit_stmt->fetch(PDO::FETCH_OBJ);
            if($edit_data){ //that means product exist. .
                //edit selected categories:
                foreach ($categories_array as $cat_arr) {
                    if(isset($_POST["edit_category_".$cat_arr])) {
                        $categories .= htmlentities($_POST["edit_category_".$cat_arr]).";";
                    }
                }

                //then edit(update) the product
                $edd_stmt = $pdo->prepare("UPDATE products SET product_name = ?, product_url = ?, category = ?, `description` = ?, price = ?, former_price = ? WHERE product_id = ?");

                $edd_stmt->execute([$_POST["product_name"], $_POST["url"], $categories, $_POST["product_description"], $_POST["product_price"], (htmlentities($_POST["product_price"]) + (($fp_array[0]/100)*htmlentities($_POST["product_price"]))), $_POST["edit_product_id"]]);
        
                echo "<h4 style='color:green'>Product: ", $edit_data->product_name, " has been Updated successfully</h4>";
        
                //Edit(Update) images:
                $img_i = 0;
                foreach($images_array as $images_ad) {//foreach loop - [images_array] starts
                    $img_i++;
                    if(!empty($_FILES["edit_".$images_ad]["name"])){ //if (!empty($_FILES["add_".$images_ad])) starts
                        /* Image Upload Script starts */
                        $target_dir = "static/images/";
                        $target_basename = $_POST["url"]."_".time()."_".$img_i.".png";
                        $target_file = $target_dir.$target_basename;
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                        //Check if image file is a actual image or fake image
                        $check_img = getimagesize($_FILES["edit_".$images_ad]["tmp_name"]);
                        if ($check_img !== false) {
                            //echo "image security test passed - ".$check_img["mime"].".";
                            $uploadOk = 1;
                        } else {
                            //echo "image security test failed - file is not an image";
                            $uploadOk = 0;
                        }
                        if(file_exists($target_file)) {
                            echo "Sorry, file already exists";
                            $uploadOk = 0;
                        }
        
                        //Allow certain file formats:
                        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                            $uploadOk = 0;
                        }
        
                        //Checking if any $uploadOk = 0 by an error:
                        if ($uploadOk == 0) {
                            echo "Sorry, your file was not uploaded.";
                        //if everything is ok, edit(update) file
                        } else {
                            if (move_uploaded_file($_FILES["edit_".$images_ad]["tmp_name"], $target_file)) {
                                //echo "<h3>The file ".$target_basename. " has been uploaded.</h3>";

                                //insert(update) product image(s)
                                $up_stmt = $pdo->prepare("UPDATE products SET $images_ad = ? WHERE product_id = ?");
                                $up_stmt->execute([$target_basename, $_POST["edit_product_id"]]);

                                //echo $target_basename." added to the database.<br />";
                                $img_output .= "image: <b>".$target_basename."</b> added and recorded.<br />";
                            } else {
                              echo "Sorry, there was an error uploading your file.<br />";
                            }
                        }
                        /* Image Upload Script ends */
                    } else {//if(!empty($_FILES["edit_".$images_ad])) ends
                        //echo "<h1>Totally empty HTML File Headers</h1>";
                        $img_output .= "";
                    }
                }//foreach loop - looping around array to upload multiple product images at once ends
            }
        }
        //End of PHP script To Edit Product:
        
        //To Delete Product:
        if(isset($_POST["remove_product"])){
            //check if product still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
            $ds_stmt->execute([$_POST["remove_product"]]);
            
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
                $dd_stmt->execute([$_POST["remove_product"]]);
        
                echo "<h4 style='color:red'>Product: ", $ds_data->product_name, " has been deleted successfully</h4>";
            } else {
                echo "<h4 style='color:red'>Error: Product not found.</h4>";
            }
        }
        //End of PHP script To Delete Product:
?>
    <div class="site_content"><!-- .site_content starts -->       
        
        <!-- HTML -->
        <div class="dashboard_div" style="margin:-30px 3% 10% 3%;">
        <h1 style="margin:12px 6px">Products - <?=$site_name?></h1>

        <!-- Add new product div starts -->
        <div>
            <div onclick="show_div('new_product')" style="background-color:green;color:#fff;font-weight:bold;padding:9px 12px;border-radius:6px;margin:12px 0 18px 0;width:fit-content"><span>Add New Product</span> <i class="fa fa-angle-down" style="margin-left:12px;font-size:21px"></i></div>

            <div id="new_product" style="display:none;padding:9px;background-color:#f3f3f3;border-radius:6px;border:1px dotted #000;box-shadow:0 0 6px 0 #ff9100">
                <form method="post" action="" enctype="multipart/form-data">
                    <!-- -->
                    <div style="position:relative"><input type="text" id="product_name<?=$i?>" class="edit_product_input" name="new_product_name" placeholder="Enter Product Name" required/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Name </span></div> 

                    <div style="position:relative"><input type="text" id="product_url<?=$i?>" class="edit_product_input"  name="new_url" placeholder="Enter Product URL" id = "new_url" onkeyup = "create_url('new_url')" required/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Url </span></div> 
                    <span>Only letters, numbers and hyphen (-) allowed.</span>

                    <!-- Add Image Starts -->
                    <div style="font-size:18px;margin:15px 0 9px 0"><b>Add Images:</b> <span style="font-size:12px;color:green">(image1 is required, others are optional)</span></div>

                    <div class="x_scroll"><!-- style .overflow-x:scroll starts -->
                        <div class="additional_product_images_div_container" style="width:fit-content;overflow:visible"><!-- .additional_product_images_div_container starts -->
<?php
                        foreach($images_array as $images_ad) {
?>
                            <div class="additional_product_images_div"><!-- img1 to img10 -->
                                <label for="add_<?=$images_ad?>_file_upload_tag"><img src="/static/images/add_image_icon.png" id="add_<?=$images_ad?>" class="additional_product_image"/><span class="additional_product_image_number"><?=str_replace("image", "", $images_ad)?></span></label>
                            </div>
<?php
                        }
?>
                        </div><!-- .additional_product_images_div_container ends -->
                    </div><!-- style .overflow-x:scroll ends -->

                    <!-- The input tags which does the work but remains hidden starts -->
<?php
                        foreach($images_array as $images_ad) {
?>
                            <input type="file" name="add_<?=$images_ad?>" id="add_<?=$images_ad?>_file_upload_tag" accept="image/*" style="display:none" onchange="loadFile(event, 'add_<?=$images_ad?>')"/><!-- file tag 1 to file tag 10 -->
<?php
                        }   
?>
                    <!-- The input tags which does the work but remains hidden ends -->
                    <!-- Add Image Ends -->

                    <div style="position:relative"><input type="number" id="product_price<?=$i?>" class="edit_product_input"  name="new_product_price" placeholder="Enter Product URL" required/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Price </span></div>

                    <div style="font-size:18px;margin:15px 0 9px 0"><b>Product Description:</b></div>
                    <textarea style="width:75%;height:100px;border-radius:4px" name="new_product_description" placeholder="sell this product in a maximum of 50 words."></textarea>

                    <div style="font-size:18px;margin:15px 0 9px 0"><b>Select Category:</b></div>
                    <div style="text-align:center;margin-bottom:21px">
                        <?php
                            foreach($categories_array as $ca) {
                        ?>
                                <span class="select_category"><label><?=ucfirst($ca)?> <input type="checkbox" name="add_category_<?=$ca?>" value="<?=$ca?>"/></label></span>
                        <?php
                            }
                        ?>
                    </div>

                    <div style="margin:12px 0">
                        <input type="submit" class="edit_product_action_button" style="background-color:green"/>
                        
                        <span class="edit_product_action_button" style="background-color:#ff9100" onclick="show_div('new_product')">Cancel</span>
                    </div>
                    <input type="hidden" name="new_product" value="new_product"/>
                </form>
            </div>
        </div>
        <!-- Add new product div ends -->

        <!-- searching for product -->
        <div style="margin-top:18px;padding:12px 9px;border:1px solid #000;border-radius:12px;background-color:#f3f3f3">
            <input type="text" onkeyup="ajax_search()" id="search_input" class="input" placeholder="Enter Product Name: try: abc" style="border:1px solid #000;width:75%"/>     
            <i class="fa fa-search" onclick ="search_icon()" style="padding:9px;border-radius:4px;font-size:16px;color:#fff;background-color:#000"></i>
            <!-- ajax div for printing out products searched in real time -->
            <div id="search" style="position:absolute;width:75%"></div>
        </div>
        
        <div style="margin-top:12px">    <!-- 'main' div starts -->
            <div class="table_row_div" style="margin-bottom:18px"><!-- first row (header) -->
                <div class="table_row" style="width:8%">#</div>
                <div class="table_row">Name</div>
                <!--<div class="table_row">Status</div>-->
                <div class="table_row">Edit / Delete</div>
            </div>





<?php
        //Select and view all users for easy decision making:
        //A Simple Pagination Algorithm:
        $p = 1;
        $num_of_rows = 10;

        if(isset($_GET["page"])) {
            $p = htmlentities($_GET["page"]);
            if(!is_numeric($p) || $p < 1){
                $p = 1;
            }
        }
        
        $page_to_call = ($p - 1)*$num_of_rows;

        //count entire users:
        $u_search_stmt = $pdo->prepare("SELECT * FROM products ORDER BY product_id DESC LIMIT ?, ?");
        $u_search_stmt->execute([0, 1000]);

        $num_of_users = count($u_search_stmt->fetchAll(PDO::FETCH_OBJ));

        $max = ceil($num_of_users/$num_of_rows);
        // -- end of pagination algorithm --

        
        //first check if seller searched for a product in particular
        if(isset($_GET["product"])) {
            $search_q = htmlentities($_GET["product"]);
            $u_search_stmt = $pdo->prepare("SELECT * FROM products WHERE product_url LIKE ? ORDER BY product_id DESC LIMIT ?, ?");
            $u_search_stmt->execute(["%$search_q%",$page_to_call, $num_of_rows]);

            $u_data = $u_search_stmt->fetchAll(PDO::FETCH_OBJ);
        }  else {//if no particular person is searched for, list out all products:
            $u_stmt = $pdo->prepare("SELECT * FROM products ORDER BY product_id DESC LIMIT ?, ?");
            $u_stmt->execute([$page_to_call, $num_of_rows]);
    
            $u_data = $u_stmt->fetchAll(PDO::FETCH_OBJ);
        }

        if(count($u_data)>0) {     
            $i = 0;
            foreach($u_data as $d){
                $i += 1;
?>

        <div class="everything-both-buttons-nd-hidden-divs"> 
            <div class="table_row_div">
                <div class="table_row" style="width:8%"><?=$i + (($p - 1)*$num_of_rows)?>. </div>
                <div class="table_row"><b><a href="/product/<?=$d->product_url?>"><?=$d->product_name?></a></b></div>
                <div class="table_row" style="font-size:fit-content">
                    <button onclick = "create_content('edit',<?=$i?>)" style="background-color:green"
                class="table_row_ED">
                        Edit &nbsp; <i class="fa fa-pencil"></i> 
                    </buton>
                    <button onclick = "create_content('remove',<?=$i?>)" style="background-color:red" class="table_row_ED">
                        <i class="fa fa-warning"></i> &nbsp; Remove </buton>
                </div>
            </div>


            <div class="clear">
            <div style="margin-top:12px;">

            <!-- To make all hidden div content to appear in the same spot on display: -->
            <div id="content_space<?=$i?>" class="calculator" style="display:none"> 
                <!-- style="display:block creates undesirable problems like making the div not to appear even onclick" -->
            </div>

            <!--hidden section 1: Edit -->
            <div id="edit<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;padding:9px;">
                <form method="post" action="" enctype="multipart/form-data">
                    <div style="position:relative"><input type="text" id="product_name<?=$i?>" class="edit_product_input" name="product_name" value="<?=$d->product_name?>" required/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Name </span></div> 

                    <div style="position:relative"><input type="text" id="product_url<?=$i?>" class="edit_product_input"  name="url" value="<?=$d->product_url?>" required/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Url </span></div> 
                    <?php
                        if(isset($_POST["edit_product_id"])) {
                            if($_POST["edit_product_id"] == $d->product_id) {
                                echo "<div style='color:green'>",$img_output,"</div>";
                            }
                        }
                    ?>
                    <div class="x_scroll"><!-- style .overflow-x:scroll starts -->
                        <div class="additional_product_images_div_container" style="width:fit-content;overflow:visible"><!-- .additional_product_images_div_container starts -->
<?php
                        foreach($images_array as $images_ad) {
?>
                            <div class="additional_product_images_div"><!-- img1 to img10 -->
                                <label for="edit_<?=$images_ad?>_<?=$d->product_id?>_file_upload_tag">
                                    <img src="/static/images/<?=$d->$images_ad?>" id="edit_<?=$images_ad.$d->product_id?>" class="additional_product_image"/><span class="additional_product_image_number"><?=str_replace("image","",$images_ad)?></span>
                                </label>
                            </div>
<?php
                        }
?>
                        </div><!-- .additional_product_images_div_container ends -->
                    </div><!-- style .overflow-x:scroll ends -->

                    <div style="position:relative"><input type="number" id="product_price<?=$i?>" class="edit_product_input" name="product_price" value="<?=$d->price?>" required/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Price </span></div> 

                    <div style="font-size:18px;margin:15px 0 9px 0"><b>Product Description:</b></div>
                    <textarea style="width:90%;height:100px;border-radius:4px" name="product_description"><?=$d->description?></textarea>

                    <div style="font-size:18px;margin:15px 0 9px 0"><b>Edit Category:</b></div>
                    <div style="text-align:center;margin-bottom:21px">
                        <?php 
                            $existing_categories_array = explode(";", $d->category);
                            foreach($categories_array as $ca) {
                                $checked_or_not = "not-checked";
                                if(in_array($ca, $existing_categories_array)) {
                                    $checked_or_not = "checked";
                                }
                        ?>
                                <span class="select_category"><label><?=ucfirst($ca)?> <input type="checkbox" name="edit_category_<?=$ca?>" value="<?=$ca?>" <?=$checked_or_not?>/></label></span>
                        <?php
                            }
                        ?>
                    </div>

                    <div style="margin:12px 0">
                        <input type="submit" class="edit_product_action_button" style="background-color:green"/>
          
                        <span class="edit_product_action_button" style="background-color:#ff9100" onclick="hide_content_space('edit',<?=$i?>)">Cancel</span>
                    </div>

                    <!-- For Editing Product Image ~ The input tags which does the work but remains hidden starts -->
<?php
                foreach($images_array as $images_ad) {
?>
                    <input type="file" name="<?='edit_'.$images_ad?>" id="edit_<?=$images_ad?>_<?=$d->product_id?>_file_upload_tag" accept="image/*" style="display:none" onchange="loadFile(event, 'edit_<?=$images_ad.$d->product_id?>')"/><!-- file tag 1 to 10 -->
<?php
                }
?>
                    <!-- For Editing Product Image ~ The input tags which does the work but remains hidden ends -->
                    <input type="hidden" name='<?="edit_product_id"?>' value="<?=$d->product_id?>"/><!-- hidden html input tag which is passed to php if statement to know if user wants to edit any product -->
                </form>
            </div>
            <!--End of Edit div-->



            <!-- hidden section 2: Remove Product -->    
            <div id="remove<?=$i?>" style="display:none;border:2px solid red;border-radius:6px;margin-top:12px;padding:3px">

            <form method="post" action="" id="message_form<?=$i?>" class="pop_up">
                <span style="text-align:center">Are you sure you want to remove user: <b style="font-size:18px;color:red;border-bottom:2px solid #fff"><?=$d->product_name?>?</b> &nbsp;
    
                <b>This can't be Undone</b></span><br /><br />
    
                <input type="hidden"  name="remove_product" value="<?=$d->product_id?>"/>
    
                <input type="submit" value="Remove" style="background-color:red;
                        padding:3px;margin:3px;border-radius:6px;color:#fff;border:none;height:24px;"/> 
    
                <!--Cancel "Remove Product" (Don't remove):-->
                <!--onclick = "show_div('remove <= $i >')"-->
                <span onclick="hide_content_space('remove',<?=$i?>)" style="background-color:#ff9100;
                        padding:3px;border-radius:6px;color:#fff;
                        margin-left:6px;text-align:center;height:24px;border:none">
                        Cancel 
                </span>    
            </form>

            </div> <!--End of remove<=i> div-->

            </div><!--End of hidden divs-->
            </div><!--End of hidden divs clear class-->   
        </div> <hr /><br /><!--End of Both Buttons and hidden divs-->
        
        <!--End of all - both buttons hidden divs-->

<?php
            }
        }
?>

        <!--Paginator-->
        <div class="clear" style="font-weight:bold;font-size:18px; margin-bottom:12px">
            <?php if($p > 1) { ?> 
                <div style="float:left">
                    <b>
                       <a href="?page=<?=$p-1?>" style="color:#000;font-weight:bold"><i class="fa fa-angle-left"></i> &nbsp; Previous</a>
                    </b>
                </div> 
            <?php } ?>

            <?php if($p < $max) { ?> 
                <div style="float:right">
                    <b>
                        <a href="?page=<?=$p+1?>" style="color:#000">Next &nbsp;<i class="fa fa-angle-right"></i></a>
                    </b>
                </div> 
            <?php } ?>
        </div> <!-- End of Paginator -->
        </div> <!-- End of class 'main_div' -->
        </div><!-- .site_content ends -->
        </div><!-- .site_content_and_menu ends -->
        </div><!-- .site_content_and_menu_sellers ends -->

<?php
    }else{
        //redirect
        header("location:/seller");
    }

    echo "</div>"; //end of 'main' div
    seller_Segments::footer();
} else {
    //redirect
    header("location:/seller");
}

?>