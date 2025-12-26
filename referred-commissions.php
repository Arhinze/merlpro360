<?php

include_once("/home/u590828029/domains/aguanit.com/public_html/views/Dashboard_Segments.php");

if($data){
    //that means user is logged in
    Dashboard_Segments::header();
    
        echo "<div class='dashboard_div' style='padding:12px'><h1>Referred Commissions: </h1><hr /><div style='line-height:23px;font-size:18px'>";

        $ref_com_stmt = $pdo->prepare("SELECT * FROM miners WHERE referred_by = ? LIMIT ?, ?");
        $ref_com_stmt->execute([$data->username, 0, 1000]);
        $ref_com_data = $ref_com_stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($ref_com_data)>0){
            $total_ref_com = count($ref_com_data);

            echo "<hr />Total: <b>", $total_ref_com, " $$token_name</b> earned as referral commission.<br /><br />
            <a href='/dashboard' style='color:$site_color_light'>View other earnings &nbsp;<i class='fa fa-angle-right'></i> </a><br /><br />";

        } else {
            echo "Sorry, No commisions yet. Kindly share your referral link to prospective users in order to receive a commision when they sign up.";
        }  
        
        echo "</div></div>";
        Dashboard_Segments::footer();
    }else {
        header("location:/login");
    }