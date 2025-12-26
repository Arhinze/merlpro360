<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

//if user wants to delete an order:
//~~placing this here so that changes can be reflected on the header cart count immediately any deletion is made
if(isset($_POST["remove_product"])) {
    $product = htmlentities($_POST["remove_product"]);
    //ensure product exists first:
    $pre_del_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE orders_processor_id = ? LIMIT ?, ?");
    $pre_del_stmt->execute([$product, 0, 1]);
    $pre_del_data = $pre_del_stmt->fetch(PDO::FETCH_OBJ);

    if($pre_del_data) {//that means order still exist ~ proceed to delete:
        $del_order_stmt = $pdo->prepare("DELETE FROM orders_processor WHERE orders_processor_id = ?");
        $del_order_stmt->execute([$product]);
    }
}

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$customer_id = $user_unique_id;
$total_amount = 0;
$cart_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id =  ? AND qty > ? LIMIT ?, ?");
$cart_stmt->execute([$customer_id, 0, 0, 25]);
$cart_data = $cart_stmt->fetchAll(PDO::FETCH_OBJ);
$cart_count = count($cart_data);

//state_array:
$state_array = ["abia","adamawa", "akwa ibom", "anambra", "bauchi", "bayelsa", "benue","borno", "cross river"];

//user_details values for new users:
$realName = "";
$phoneNumber = "";
$email = "";
$address = "";
$state = "";
$lga = "";
$postalCode = "";

if($cart_count > 0) {//if cart is not empty:
    foreach($cart_data as $cc){
        $p_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $p_stmt->execute([$cc->product_id]);
        $p_data = $p_stmt->fetch(PDO::FETCH_OBJ);

        $total_amount += $p_data->price*$cc->qty;
    }

    //check if user is already existing and bring out existing user details:
    $state = ""; //defining state here so I can use the selected_or_not() function
    $new_user_stmt = $pdo->prepare("SELECT * FROM customers WHERE unique_id =  ? LIMIT ?, ?");
    $new_user_stmt->execute([$customer_id, 0, 1]);
    $new_user_data = $new_user_stmt->fetch(PDO::FETCH_OBJ);
    if($new_user_data){//default user details previously used:
        $realName = $new_user_data->customer_realname;
        $phoneNumber = $new_user_data->phone_number;
        $email = $new_user_data->customer_email;
        $address = $new_user_data->address;
        $state = $new_user_data->state;
        $lga = $new_user_data->LGA;
        $postalCode = $new_user_data->postal_code;
    }
}
Index_Segments::header(); 
?>

<div class="main_body"><!-- .main_body starts -->
<div class="site_content_and_menu"><!-- .site_content_and_menu starts -->

<?php
echo Index_Segments::site_menu();
echo "<div class='site_content'><!-- .site_content_and_menu starts -->";

echo "<div style='margin:12px;font-weight:bold'><a href='/' style='color:#000'><i class='fa fa-angle-left' style='font-size:18px'></i></a>&nbsp; Cart ($cart_count)</div>";
if (count($cart_data) > 0) {//that means user has an item or more in cart -- list them out:
    echo "<div class='site_cart'><!-- .site_cart starts -->";
    foreach($cart_data as $cart_d) {
        $prod_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $prod_stmt->execute([$cart_d->product_id]);
        $cart_prod_data = $prod_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach($cart_prod_data as $cpd) {
            $short_description = substr($cpd->description,0,36)."... ";
?>
        <div class="cart_div" style="display:flex;margin:12px"><!-- cart starts -->
            <div class="cart_image_div"><!-- cart img starts -->
                <img src="/static/images/<?=$cpd->image1?>" class="cart_image"/>
            </div><!-- cart img ends -->
            <div><!-- cart details starts -->
                <div style="font-size:12px"><a href="/product/<?=$cpd->product_url?>" style="color:#888"><?=$short_description?></a></div>
                <div style="margin:9px 0">
                    <span class="qty">
                        <b style="font-size:24px" onclick='ajax_qty("<?=$cart_d->product_id?>","decrease","qty<?=$cart_d->orders_processor_id?>")'>-</b>&nbsp;&nbsp;
                        <span id="qty<?=$cart_d->orders_processor_id?>"><?=$cart_d->qty?></span>&nbsp;&nbsp;
                        <b style="font-size:18px" onclick='ajax_qty("<?=$cart_d->product_id?>","increase","qty<?=$cart_d->orders_processor_id?>")'>+</b>
                    </span>
                </div>
                <div style="margin-top:16px">
                    <b>NG N<?=number_format($cpd->price)?></b> &nbsp; <s>N<?=number_format($cpd->former_price)?></s>
                </div>
            </div><!-- cart details ends -->
            <div style="width:12px;margin-top:42px;margin-right:15px" onclick="show_div('remove_cart_item_<?=$cart_d->orders_processor_id?>')"><span class="x_remove"><i class="fa fa-times"></i></span></div>
        </div><!-- cart ends -->

        <!-- hidden prompt for user to confirm order delete starts -->
        <div id="remove_cart_item_<?=$cart_d->orders_processor_id?>" style="display:none;border:2px solid red;border-radius:6px;margin-top:12px;padding:3px">
            <form method="post" action="" class="pop_up">
                <span style="text-align:center">Are you sure you want to remove product: <b><a href="/product/<?=$cpd->product_url?>" style="font-size:18px;color:red;border-bottom:2px solid #fff"><?=$cpd->product_name?>?</a></b> &nbsp;
    
                <br /><br />
    
                <input type="hidden" name="remove_product" value="<?=$cart_d->orders_processor_id?>"/>
    
                <input type="submit" value="Remove" style="background-color:red;
                        padding:3px;margin:3px;border-radius:6px;color:#fff;border:none;height:24px;"/> 
    
                <!--Cancel "Remove Product" (Don't remove):-->
                <!--onclick = "show_div('remove <= $i >')"-->
                <span onclick="show_div('remove_cart_item_<?=$cart_d->orders_processor_id?>')" style="background-color:#ff9100;
                        padding:3px;border-radius:6px;color:#fff;
                        margin-left:6px;text-align:center;height:24px;border:none">
                        Cancel 
                </span>    
            </form>
        </div><!-- hidden prompt for user to confirm order delete ends -->
   
<?php
        }
    }
    echo "</div><!-- .site_cart ends -->";
?>
    <div class="below_product_images"><!-- .below_product_images starts -->
        <form method = "post" action = "/order-confirmation">
            <div style="font-weight:bold">Contact information</div>    
            <div style="margin:6px 0">
                <input name="name" class="input" placeholder="Enter Your Name in Full" value = "<?=$realName?>" minlength="3" required/>
            </div>
            <div style="display:flex">
                <div style="border:1px solid #888;border-radius:6px;box-shadow:0 0 3px 0 #888 inset;padding:7px 9px 0 9px;margin-right:3px">+234</div>
                <div style="width:75%">
                    <input name="phone_number" class="input" type="number" style="width:100%" placeholder="enter your phone number" value = "<?=$phoneNumber?>" min="99999999" max="9999999999" required/>
                </div>
            </div>
            <div style="margin:6px 0">
                <input name="email" class="input" type="email" placeholder="Enter Your Email Address" value = "<?=$email?>" minlength="3" required/>
            </div>
        
            <!-- Address: -->
    
            <div class="shipping_details" style="font-weight:bold">Address</div> 
            <div class="shipping_details">
                <input class="input" name="address" placeholder="Enter a full descriptive address" value = "<?=$address?>" maxlength="1500" required/>
            </div>
            <div class="shipping_details">
                <select name="customer_state" class="input" required>
                    <option> -- Enter the State -- </option>
                    <?php
                        function selected_or_not($var, $ste) {
                            if($var == $ste) {
                                return "selected";
                            } else {
                                return "";
                            }
                        }
                        foreach($state_array as $sa) {
                    ?>
                        <option value='<?=$sa?>' <?=selected_or_not($sa, $state)?>> 
                            <?=ucfirst($sa)?> 
                        </option>      
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="shipping_details">
                <input class="input" name="lga" type="text" placeholder="Enter the LGA" value = "<?=$lga?>" required/>
            </div>
            <div class="shipping_details">
                <input type = "number" class="input" name="postal_code" placeholder="Enter Postal Code" value = "<?=$postalCode?>"  min="99" max="99999999" required/>
            </div>
            <input type="hidden" name="shipping_details"/>
            <input type="submit" id="submit_order" style="display:none"/>
        </form>
    </div><!-- .below_product_images ends -->

    <div class="below_product_images"></div>

<?php
} else {//if user has no item in cart:
    echo "<div style='font-weight:bold;text-align:center;margin:24px 6px'>Sorry, No item is in your cart. Kindly add Item to cart to continue.</div>";
}
?>

    <div class="add_to_my_picks" style="display:flex;padding-bottom:18px"><!-- .add_to_my_picks starts -->
        <div style="margin:9px 15px 0 12%;font-weight:bold">
            NG N<span id="total_amount"><?=number_format($total_amount)?></span> &nbsp; <i class="fa fa-angle-up"></i>
        </div>
        <div class="long_action_button" style="background-color:#ff9100;box-shadow: 0 0 6px #888 inset;width:fit-content;padding:9px 18px">
            <label for="submit_order"><b>Checkout</b> &nbsp; <i class="fa fa-chevron-circle-right"></i></label>
        </div>
    </div><!-- .add_to_my_picks ends -->
</div><!-- .site_content ends -->
</div><!-- .site_content_and_menu ends -->
</div><!-- .main_body ends -->

<script>
    function ajax_qty(prod_id, incr_or_decr, qty_id) {
        let obj = new XMLHttpRequest;
        obj.onreadystatechange = function(){
            if(obj.readyState == 4){
                if (document.getElementById(qty_id)){
                    document.getElementById(qty_id).innerHTML = obj.responseText;
                }
            }
            //update the total amount:
            ajax_get_total_amount();
        }
 
        obj.open("GET","/ajax/ajax_qty.php?id="+prod_id+"&increase_or_decrease="+incr_or_decr);
        obj.send(null);    
    }

    function ajax_get_total_amount() {
        let obj = new XMLHttpRequest;
        obj.onreadystatechange = function(){
            if(obj.readyState == 4){
                if (document.getElementById("total_amount")){
                    document.getElementById("total_amount").innerHTML = obj.responseText;
                }
            }
        }
 
        obj.open("GET","/ajax/ajax_total_amount.php");
        obj.send(null);    
    }
</script>

<?php

Index_Segments::footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = Index_Segments::index_scripts(),$whatsapp_chat = "off");
        echo <<<HTML
            <!-- this div is only meant to bring up the footer section of cart page so that it's not covered by the fixed 'add_to_my_picks' div-->
            <div style="margin-top:45px"></div>
HTML;

?>