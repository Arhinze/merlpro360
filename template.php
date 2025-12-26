<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

class Index_Segments{
    public static function main_header($site_name = SITE_NAME_SHORT) {
        echo <<<HTML
            <div class="headers"> <!-- start of .headers --> 
                <div class="site_logo_div">
                    <img src="/static/images/logo.png" class="site_logo"/>
                </div>
                <h3 class="site_name">
                    <a href="/">Bilo<span style="color:#ff9100">Online</span><!--site_name--></a>
                </h3>
                <div class="header_search">
                    <input type="text" placeholder="search for .." class="header_input"/>
                </div>                       
                <div class="header_shopping_cart">
                    <i class="fa fa-shopping-cart"></i>
                </div> 
            </div> <a name="#top"></a> <!-- end of .headers --> 
HTML;
    }
    
    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $Hi_user = "", $title=SITE_NAME){
        
        $main_header = Index_Segments::main_header();
        $css_version = filemtime($_SERVER["DOCUMENT_ROOT"]."/static/style.css");

        if (isset($_GET["ref"])) {
            $ref = htmlentities($_GET["ref"]);

            if(isset($_COOKIE["ref"])){
                //delete existing referer cookie
                setcookie("ref", $ref, time()-(24*3600), "/");
            }

            //set new referer cookie:
            setcookie("ref", $ref, time()+(12*3600), "/");
        }

        echo <<<HTML
        <!doctype html>
        <html lang="en">
        <head>
          
            <link rel="stylesheet" href="/static/style.css?$css_version"/>
            <link rel="icon" type="image/x-icon" href="/static/images/logo.png"/>
            <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito|Hammersmith+One|Trirong|Arimo|Prompt"/>
            
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                
            <title>$title</title>
              
        </head>
        <body>
            $main_header
            <div class="menu_div">  
                <div class="menu">
                    <div class="all_categories"><i class="fa fa-bars"></i>&nbsp; All Categories &nbsp; <i class="fa fa-angle-down"></i></div>

                    <div class="menu_item" style="margin-left:180px"><a href="">Women</a></div>

                    <div class="menu_item"><a href="">Men</a></div>

                    <div class="menu_item"><a href="">Home</a></div>

                    <div class="menu_item"><a href="">Sports</a></div>

                    <div class="menu_item"><a href="">Jewelry</a></div>

                    <div class="menu_item"><a href="">Industrial</a></div>

                    <div class="menu_item"><a href="">Electronics</a></div>

                    <div class="menu_item"><a href="">Kids</a></div>

                    <div class="menu_item"><a href="">Bags</a></div>

                    <div class="menu_item"><a href="">Toy</a></div>

                    <div class="menu_item"><a href="">Crafts</a></div>

                    <div class="menu_item"><a href="">Beauty</a></div>
                    
                    <div class="menu_item"><a href="">Automotive</a></div>
                    
                    <div class="menu_item"><a href="">Garden</a></div>
                    
                    <div class="menu_item"><a href="">Office</a></div>

                    <div class="menu_item"><a href="">Health</a></div>

                    <div class="menu_item"><a href="">Baby</a></div>

                    <div class="menu_item"><a href="">Household</a></div>

                    <div class="menu_item"><a href="">Musical</a></div>

                    <div class="menu_item"><a href="">Appliances</a></div>

                    <div class="menu_item"><a href="">Food</a></div>

                    <div class="menu_item"><a href="">Books</a></div>

                    <div class="menu_item"><a href="">More &nbsp; <i class="fa fa-angle-down"></i></a></div>

                    <!--<div class="menu_item"><a href=""><i class="fa fa-user"></i>&nbsp; Sign Up</a></div>-->
                </div> 
            </div> 
HTML;
       }
                
        public static function body($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){
            $site_name_uc = strtoupper($site_name);
            echo <<<HTML
                <div class="main_body">
                    


                <!-- Everything about the page goes here -->
                
                </div><!--.main_body end-->
HTML;
       }
                                                                
       public static function index_scripts(){
        echo <<<HTML
                                                                
        <!-- Footer - index_scripts -->
        <script>
            function show_div(vari) {
                if (document.getElementById(vari).style.display == "none") {
                    document.getElementById(vari).style.display = "block";
                } else if (document.getElementById(vari).style.display == "block") {
                    document.getElementById(vari).style.display = "none";
                }
            }
                                                                                         
            const collection = document.getElementsByClassName("invalid");
                                                                                                 
            for (let i=0; i < collection.length; i++){
                //collection[i].style = "display:none";
                                                                                                            
                var innerHT = collection[i].innerHTML;
                                                                                            
                var newInnerHT = innerHT + "<span style='float:right;margin:4px 18px'><i class='fa fa-times' onclick='hide_invalid_div()'></i></span>";
                          
                collection[i].innerHTML = newInnerHT;
            }
                                                                                           
            function hide_invalid_div() {
                //const collection = document.getElementsByClassName("invalid");
                i = 0;
                for (i=0; i<collection.length; i++){
                    collection[i].style.display = "none";
                }  
            }
                                                                
            //Implementing multi-line placeholder for textarea html documents
            var textAreas = document.getElementsByTagName('textarea');
                                                                
            Array.prototype.forEach.call(textAreas, function(elem) {
                elem.placeholder = elem.placeholder.replace(/\\n/g, '\\n');
            });
                                                                
            function show_bt_input_div(){
                document.getElementById("bt_input_div").style.display = "block";
            }
                                                                        
            function close_bt_input_div(){
                document.getElementById("bt_input_div").style.display = "none";
            }
                                                                    
            function calculate_total(){
                total_num = document.getElementById("total_number").value;
                amt_for_each = document.getElementById("amount_to_pay_each_person").value;
                total_amount = Number(total_num) * Number(amt_for_each);
                                                                    
                document.getElementById("total_to_transfer_text").innerHTML = "<div style='margin:12px 3px'>Total cost of transaction: <b><i class='fa fa-naira'></i>N "+total_amount.toString()+"</b></div>";
                                                                
                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        if (document.getElementById("current_balance_text")){
                            document.getElementById("current_balance_text").innerHTML = obj.responseText;
                        }
                    }
                }
                                                                        
                obj.open("GET","/ajax/ajax_cb.php?total_="+total_amount);
                obj.send(null);
                                                                
                //disable button and allow only when total_amount < current balance and amt_for_each > 100
                button_status = document.getElementById("proceed_to_pay_button");
                current_balance_text = document.getElementById("current_balance_text");
                if((Number((current_balance_text.innerHTML).replace("N", "")) >= total_amount) & (amt_for_each >= 10)) {
                    button_status.style="background-color:#333131";
                    button_status.disabled = false;
                } else {
                    button_status.style="background-color:#888";
                    button_status.disabled = true;
                }
                                                                
                //turn current balance text green or red depending on if it's > or < than total_amount
                if(Number((current_balance_text.innerHTML).replace("N", "")) >= total_amount) {
                    current_balance_text.style="color:green";
                } else {
                    current_balance_text.style="color:red";
                }
            }
                                                                
        </script>
        HTML;
        }
                                                                
                                                                
        public static function footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = ""){ 
                                                                            
            $index_scripts = Index_Segments::index_scripts();    
                                                                
        echo <<<HTML
        <br/><br/><br/><br/><br/><br/><br/><br/>
            <div class="footer"><!-- .footer starts --> 
                <div class="footer_site_name">Bilo<span style="color:#ff9100">Online</span></div>

                <div>This is your best online shop. We sell wholesale, retail, and single units to individuals. Enjoy free shipping.</div>
                
                <div class="footer_fa_links">
                    <i class="fa fa-facebook"></i> &nbsp;
                    <i class="fa-brands fa-tiktok"></i> &nbsp;
                    <i class="fa fa-instagram"></i> &nbsp;
                    <i class="fa fa-youtube-play"></i> &nbsp;
                </div>

                <div class="footer_heading">Shop</div>
                Terms & Conditions
                Sitemap
                Press

                <div class="footer_heading">Support</div>
                Documentation
                Help Center
                General FAQs
                
                <div class="footer_heading">Newsletter</div>
                Get 20% off for your first order by joining to our newsletter.
                
                 
                
                <div style="border-top:1px dotted #888;margin-top:15px;padding:15px 0;text-align:center">© 2025 Bilo Privacy Policy All rights reserved. Designed & developed by BILO Tech Team</div>
            </div><!-- .footer ends -->                                                          
            $index_scripts
            $additional_scripts
        </body>
        </html>    
HTML;
    }
}
?>