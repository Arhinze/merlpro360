<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/config/paystack.php");

$customer_id = $user_unique_id;
$customer_email = "example@abc.com";
$total_amount = 0;
$cart_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id =  ? AND qty > ? LIMIT ?, ?");
$cart_stmt->execute([$customer_id, 0, 0, 25]);
$cart_data = $cart_stmt->fetchAll(PDO::FETCH_OBJ);
$cart_count = count($cart_data);
$proceed_to_pay = '<div class="long_action_button" style="background-color:green;box-shadow: 0 0 6px #888 inset;width:fit-content;padding:9px 18px"><label for="proceed_to_pay"><b>Proceed to pay</b> &nbsp; <i class="fa fa-chevron-circle-right"></i></label></div>';

if(isset($_POST["shipping_details"])){
    $shipping_name = htmlentities($_POST["name"]);
    $shipping_phone_number = htmlentities($_POST["phone_number"]);
    $shipping_address = htmlentities($_POST["address"]);
    $shipping_state = htmlentities($_POST["customer_state"]);
    $shipping_lga = htmlentities($_POST["lga"]);
    $shipping_postal_code = htmlentities($_POST["postal_code"]);
}

$new_user_data = "false";
if($cart_count > 0) {//that means cart is not empty
    foreach($cart_data as $cc){
        $p_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $p_stmt->execute([$cc->product_id]);
        $p_data = $p_stmt->fetch(PDO::FETCH_OBJ);

        $total_amount += $p_data->price*$cc->qty;
    }

    //create a user if this user doesn't exist yet:
    if (isset($_POST["email"])) {
        $customer_email = htmlentities($_POST["email"]);
        $new_user_stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_email =  ? LIMIT ?, ?");
        $new_user_stmt->execute([$customer_email, 0, 1]);
        $new_user_data = $new_user_stmt->fetch(PDO::FETCH_OBJ);

        if($new_user_data) {//that means user already exist
            if($new_user_data->unique_id == $user_unique_id) {//that means the verified user is logged in ~~~ update shipping details and continue . .
                $update_user_stmt = $pdo->prepare("UPDATE customers SET customer_realname = ?, customer_email = ?, phone_number = ?, `address` = ?, `state` = ?, LGA = ?, postal_code = ? WHERE unique_id =  ?");
    
                $update_user_stmt->execute([htmlentities($_POST["name"]), htmlentities($_POST["email"]), htmlentities($_POST["phone_number"]), htmlentities($_POST["address"]), htmlentities($_POST["customer_state"]), htmlentities($_POST["lga"]), htmlentities($_POST["postal_code"]), $customer_id]);
    
                //echo "customer updated";
            } else {//verified owner of the email not yet logged in
                echo "<div class='invalid' id='email_in_use'>Email already in use. <b><a href='/login'>Login</a></b> to your account to continue</div>";
                $proceed_to_pay = '<div class="long_action_button" style="background-color:#888;box-shadow: 0 0 6px #888 inset;width:fit-content;padding:9px 18px" onclick="show_div('."'email_in_use'".')"><b>Proceed to pay</b> &nbsp; <i class="fa fa-chevron-circle-right"></i></div>';
                
                $shipping_name = "";
                $shipping_phone_number = "";
                $shipping_address = "";
                $shipping_lga = "";
                $shipping_state = "";
                $shipping_postal_code = "";
            }
        } else {//this is a new user, create(insert)
            $insert_user_stmt = $pdo->prepare("INSERT INTO customers(date_joined, customer_realname,customer_email, unique_id, phone_number, `address`, `state`, LGA, postal_code) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insert_user_stmt->execute([date("Y-m-d H:i:s", time()), htmlentities($_POST["name"]), htmlentities($_POST["email"]), $user_unique_id, htmlentities($_POST["phone_number"]), htmlentities($_POST["address"]), htmlentities($_POST["customer_state"]), htmlentities($_POST["lga"]), htmlentities($_POST["postal_code"])]);

            //echo "customer inserted";
        }
    }
    //call out user details:
    $new_user_stmt = $pdo->prepare("SELECT * FROM customers WHERE unique_id =  ? LIMIT ?, ?");
    $new_user_stmt->execute([$customer_id, 0, 1]);
    $new_user_data = $new_user_stmt->fetch(PDO::FETCH_OBJ);

    if($new_user_data){
        $shipping_name = $new_user_data->customer_realname;
        $shipping_phone_number = $new_user_data->phone_number;
        $shipping_address = $new_user_data->address;
        $shipping_lga = $new_user_data->LGA;
        $shipping_state = $new_user_data->state;
        $shipping_postal_code = $new_user_data->postal_code;
    }
}

if(isset($_POST["total_amount"])) {//paystack initialization starts
        //Initialize Paystack:
        $total_amount = (int)htmlentities($_POST["total_amount"]);
        $customer_email = $new_user_data->customer_email;
        
        
        //generate random refx_id:
        $code_array = [0,1,2,3,4,5,6,7,8,9];
        shuffle($code_array);
        $code_out = "";
        
        $arr = [0,1,2,3,4,5];
        shuffle($arr);
        
        foreach($arr as $a){
            $code_out .= $code_array[$a];
        }

        //UPDATE orders_processor
        $refx_stmt = $pdo->prepare("UPDATE orders_processor SET attempted_payment_date = ?, my_refx_id = ? WHERE customer_id = ?");
        $refx_stmt->execute([date("Y-m-d H:i:s", time()), $code_out, $user_unique_id]);
          
        $url = "https://api.paystack.co/transaction/initialize";
        
        $fields = [
          'email' => $customer_email,
          'amount' => $total_amount*100,
          'callback_url' => "$site_url/success.php?total_amount=$total_amount&refx=$code_out&unique_id=$user_unique_id",
          'metadata' => ["cancel_action" => "$site_url/failure.php"]
        ];
        
        $fields_string = http_build_query($fields);
        
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".$SecretKey,
          "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
    
        $response = json_decode($result, true);
        if ($response['status']) {
            header("Location: " . $response['data']['authorization_url']);
        } else {
            echo "Payment failed, try again.";
        }
    
        //email admin on attempted deposit:
        /*$mail_admin = $cm->send_quick_mail($sender, "A user is attempting a deposit of $dep_amount", $attempted_deposit_message); 
        check_mail_status($mail_admin);
        mail->clearAddresses(); */
    } //paystack initialization ends
Index_Segments::header(); 
?>

<div class='invalid' style="display:none" id='email_in_use'>Email already in use. <b><a href='/login'>Login</a></b> to your account to continue</div>

<div class="main_body"><!-- .main_body starts -->
    <div style='margin:12px;font-weight:bold'><a href='/cart' style='color:#000'><i class='fa fa-angle-left' style='font-size:18px'></i></a>&nbsp; Order Confirmation</div>
    <div class="below_product_images"><!-- .below_product_images starts -->
    <div style='margin:12px;font-weight:bold'><i class='fa fa-file-text-o' style='font-size:18px'></i>&nbsp; Item details</div>
        <div class="multiple_product_div_container"><!-- .multiple_product_div_container starts -->
            <div class="multiple_product_div"><!-- .flex_div starts(.multiple_product_div) --> 
<?php
if ($cart_count > 0) {//that means user has an item or more in cart -- list them out:
    foreach($cart_data as $cart_d) {
        $prod_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $prod_stmt->execute([$cart_d->product_id]);
        $cart_prod_data = $prod_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach($cart_prod_data as $cpd) {
            $short_description = substr($cpd->description,0,36)."... ";
?>
                <!-- order summary -->
                <div class="deal_div" style="width:100px"><!-- .deal_div starts --> 
                    <a href ="/product/<?=$cpd->product_url?>" style="color:inherit"><!-- start of link to product page -->
                    <img src="/static/images/<?=$cpd->image1?>" class="deal_img" style="width:100px"/>   
                    <div class="below_deal_img"><!-- .below_deal_img starts -->
                        <div class="topselling_choice_and_title">
                            <span>
                                <?=$cpd->product_name?>
                            </span>
                        </div> 
                        <div style="font-size:12px">
                            <p><span class="deal_price_black" style="font-size:12px">NG N<?=number_format($cpd->price)?></span></p>
                            <p>X <?=$cart_d->qty?></p>
                            <p><b style="color:green">NG N<?=$cpd->price*$cart_d->qty?></b></p>
                        </div>
                    </div><!-- .below_deal_img ends -->
                    </a><!-- end of link to product page -->
                </div><!-- .deal_div ends -->
   
<?php
        }
    }
?>
            </div><!-- .flex_div(.multiple_product_div) ends -->
        </div><!-- .multiple_product_div_container ends -->
                    <!-- Anniversary Deals end -->
        </div><!-- .below_product_images ends -->

        <div class="below_product_images">
            <div style='margin:12px;font-weight:bold'><i class='fa fa-motorcycle ' style='font-size:18px'></i>&nbsp; Shipping Address</div>

            <div style="font-size:12px;margin:12px">
                <p style="margin-bottom:6px"><b><?=$shipping_name?> +234<?=$shipping_phone_number?></b></p>
                <?=$shipping_address?> <?=$shipping_lga?> LGA, <?=ucfirst($shipping_state)?> State, Nigeria, <?=$shipping_postal_code?>
            </div>
        </div>

    <div class="below_product_images"><!-- just here to add a decorated line beneath these divs above; None of the items beneath here is visible -->
        <form method="post" action="">
            <input type="hidden" name="total_amount" value="<?=$total_amount?>"/>
            <input type="hidden" name="user_id" value="<?=$cutomer_id?>"/>
            <input type="submit" style="display:none" id="proceed_to_pay"/>
        </form>
    </div>

<?php
} else {//if user has no item in cart:
    echo "<div style='font-weight:bold;text-align:center;margin:24px 6px'>Sorry, No item is in your cart. Kindly add Item to cart to continue.</div>";
}
?>

    <div class="add_to_my_picks" style="display:flex;padding-bottom:18px"><!-- .add_to_my_picks starts -->
        <div style="margin:9px 15px 0 12%;font-weight:bold">
            <span style="color:green">NG N<span id="total_amount"><?=number_format($total_amount)?></span> &nbsp; <!--<i class="fa fa-angle-up"></i>--></span>
        </div>
        <?=$proceed_to_pay?>
    </div><!-- .add_to_my_picks ends -->
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