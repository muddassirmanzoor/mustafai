<?php
// including the database connection file
include_once("config.php");
if ($_POST){
    $mobileno = mysqli_real_escape_string($mysqli, $_POST['mobileno']);
	$name = mysqli_real_escape_string($mysqli, $_POST['name']);
	$fathername = mysqli_real_escape_string($mysqli, $_POST['fathername']);
	$emailaddress = mysqli_real_escape_string($mysqli, $_POST['emailaddress']);	
	$phouseno = mysqli_real_escape_string($mysqli, $_POST['phouseno']);
	$pstreetno = mysqli_real_escape_string($mysqli, $_POST['pstreetno']);
	$parea = mysqli_real_escape_string($mysqli, $_POST['parea']);	
	$punit = mysqli_real_escape_string($mysqli, $_POST['punit']);
	$ptehsil = mysqli_real_escape_string($mysqli, $_POST['ptehsil']);
	$pdistrict = mysqli_real_escape_string($mysqli, $_POST['pdistrict']);	
	$pprovince = mysqli_real_escape_string($mysqli, $_POST['pprovince']);
	$thouseno = mysqli_real_escape_string($mysqli, $_POST['thouseno']);
	$tstreetno = mysqli_real_escape_string($mysqli, $_POST['tstreetno']);
	$tarea = mysqli_real_escape_string($mysqli, $_POST['tarea']);	
	$tunit = mysqli_real_escape_string($mysqli, $_POST['tunit']);
	$ttehsil = mysqli_real_escape_string($mysqli, $_POST['ttehsil']);
	$tdistrict = mysqli_real_escape_string($mysqli, $_POST['tdistrict']);	
	$tprovince = mysqli_real_escape_string($mysqli, $_POST['tprovince']);
	$profession = mysqli_real_escape_string($mysqli, $_POST['profession']);
	$whatsappno = mysqli_real_escape_string($mysqli, $_POST['whatsappno']);	
	
	//updating the table
	$query = "UPDATE users SET 
		    `name`='$name',
		    `emailaddress`='$emailaddress',
		    `mobileno`='$mobileno',
		    `fathername`='$fathername',
		    `profession`='$profession',
		    `whatsappno`='$whatsappno',
		    `phouseno`='$phouseno',
		    `pstreetno`='$pstreetno',
            `parea`='$parea',
		    `punit`='$punit',
		    `ptehsil`='$ptehsil',
		    `pdistrict`='$pdistrict',
		    `pprovince`='$pprovince',
		    `thouseno`='$thouseno',
	        `tstreetno`='$tstreetno',
            `tarea`='$tarea',
	        `tunit`='$tunit',
	        `ttehsil`='$ttehsil',
	        `tdistrict`='$tdistrict',
	        `tprovince`='$tprovince'
		    WHERE `mobileno`='$mobileno'";
	$result = mysqli_query($mysqli, $query);
    header("Location: thanku.html");
}
else{
    // echo "Get";
}

$id= $_GET['id'];

//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM users WHERE mobileno=$id");

while($res = mysqli_fetch_array($result))
{
    $mobileno =     $res['mobileno'];
    $name =         $res['name'];
	$fathername =   $res['fathername'];
	$emailaddress = $res['emailaddress'];	
	$profession =   $res['profession'];
	$whatsappno =   $res['whatsappno'];	
	$saaddress =    $res['saddress'];	
	$phouseno =     $res['phouseno'];
	$pstreetno =    $res['pstreetno'];
	$parea =        $res['parea'];	
	$punit =        $res['punit'];
	$ptehsil =      $res['ptehsil'];
	$pdistrict =    $res['pdistrict'];	
	$pprovince =    $res['pprovince'];
	$sataddress =    $res['staddress'];	
	$thouseno =     $res['thouseno'];
	$tstreetno =    $res['tstreetno'];
	$tarea =        $res['tarea'];	
	$tunit =        $res['tunit'];
	$ttehsil =      $res['ttehsil'];
	$tdistrict =    $res['tdistrict'];	
	$tprovince =    $res['tprovince'];
	
	echo "$tprovince";
}
?>
<html>

<head>
            <meta charset="utf-8">
	<title>Mustafai Tehreek Pakistan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="colorlib.com">

    <!-- MATERIAL DESIGN ICONIC FONT -->
    <link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.css">

    <!-- DATE-PICKER -->
    <link rel="stylesheet" href="vendor/date-picker/css/datepicker.min.css">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="css/style.css">
        <style>
        input{
            font-family: Jameel Noori Nastaleeq;
            font-size: 14;
        }
        .urdu-font{
            font-family: Jameel Noori Nastaleeq;
            font-size: 16px;
        }
        .hide{
            display:none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form name="form1" method="post" action="index.php" id="wizard">
            <!-- SECTION 1 -->
            <h4></h4>
            <section>
                <div class="inner">
                    <a href="#" class="avartar">
                        <img src="images/avartar.png" alt="">
                    </a>
                    <div class="form-row form-group">
                        <div class="form-holder">
                            
                        </div>
                        <div class="form-holder">
                            <select onchange="ChangeLanguage()" class="form-control urdu-font" id="language">
                                <option>اردو</option>
                                <option>English</option>
                            </select>
                            <i class="zmdi zmdi-caret-down small"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="name" placeholder="Full Name"
                                value="<?php echo $name;?>">
                            <i class="zmdi zmdi-account"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="fathername" placeholder="Father Name"
                                value="<?php echo $fathername;?>">
                            <i class="zmdi zmdi-account"></i>
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="whatsappno" placeholder="Whatsapp No"
                                value="<?php echo $whatsappno;?>">
                            <i class="zmdi zmdi-whatsapp"></i>
                        </div>
                        <div class="form-holder">
                            <input readonly type="text" class="form-control" name="mobileno" placeholder="Phone No"
                                value="<?php echo $mobileno;?>">
                            <i class="zmdi zmdi-smartphone-android"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="email" class="form-control" name="emailaddress" placeholder="Email"
                                value="<?php echo $emailaddress;?>">
                            <i class="zmdi zmdi-email small"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="profession"
                                placeholder="Enter your profession" value="<?php echo $profession;?>">
                            <i class="zmdi zmdi-graduation-cap"></i>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Present Address -->
            <h4></h4>
            <section class="section-3">
                <div class="inner">
                    <div class="form-row">
                        <h1 style="text-align: center;"><u id="taddress"></u></h1>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input readonly type="text" class="form-control" name="sataddress" placeholder="Address"
                                value="<?php echo $sataddress;?>">
                            <i class="zmdi zmdi-pin-drop"></i>
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="thouseno" placeholder="House No"
                                value="<?php echo $thouseno;?>">
                            <i class="zmdi zmdi-navigation"></i>
                        </div>
                        <div class="form-holder">
                            <input type="text" class="form-control" name="tstreetno" placeholder="Street No"
                                value="<?php echo $tstreetno;?>">
                            <i class="zmdi zmdi-walk"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="tarea" placeholder="Area/Mohallah/Moza"
                                value="<?php echo $tarea;?>">
                            <i class="zmdi zmdi-turning-sign"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="tunit" placeholder="Unit/Union Council"
                                value="<?php echo $tunit;?>">
                            <i class="zmdi zmdi-map"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="ttehsil" placeholder="Tehsil"
                                value="<?php echo $ttehsil;?>">
                            <i class="zmdi zmdi-pin-drop"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="tdistrict" placeholder="Enter District"
                                value="<?php echo $tdistrict;?>">
                            <i class="zmdi zmdi-pin"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="select">
                            <div class="form-holder">
                                 <input type="hidden" class="form-control" id="tprovince" name="tprovince" hidden>
                                <div class="select-control urdu-font" id="divtprovince">
                                    <?php if ($tprovince == 'Punjab') echo 'Punjab'; elseif ($tprovince == 'Sindh') echo 'Sindh'; elseif ($tprovince == 'Balochistan') echo 'Balochistan'; elseif ($tprovince == 'Azad Jammu Kashmir') echo 'Azad Jammu Kashmir'; elseif ($tprovince == 'Gilgit Baltistan') echo 'Gilgit Baltistan'; elseif ($tprovince == 'Khyber Pakhtoonkhwa') echo 'Khyber Pakhtoonkhwa'; else echo'Province'; ?>
                                </div>
                                <i class="zmdi zmdi-caret-down "></i>
                            </div>
                            <ul class="dropdown">
                                <li rel="Azad Jammu Kashmir">Azad Jammu Kashmir</li>
                                <li rel="Balochistan">Balochistan</li>
                                <li rel="Gilgit Baltistan">Gilgit Baltistan</li>
                                <li rel="Khyber Pakhtoonkhwa">Khyber Pakhtoonkhwa</li>
                                <li rel="Punjab">Punjab</li>
                                <li rel="Sindh">Sindh</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!--Permanent Address -->
            <h4></h4>
            <section>
                <div class="inner">
                    <a href="#" class="avartar">
                        <!--<img src="images/avartar.png" alt="">-->
                    </a>
                    <div class="form-row">
                        <h1 style="text-align: center;"><u id="paddress"></u></h1>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input readonly type="text" class="form-control" name="saaddress" placeholder="Address"
                                value="<?php echo $saaddress;?>">
                            <i class="zmdi zmdi-pin-drop"></i>
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="phouseno" placeholder="House No"
                                value="<?php echo $phouseno;?>">
                            <i class="zmdi zmdi-navigation"></i>
                        </div>
                        <div class="form-holder">
                            <input type="text" class="form-control" name="pstreetno" placeholder="Street No"
                                value="<?php echo $pstreetno;?>">
                            <i class="zmdi zmdi-walk"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="parea" placeholder="Area/Mohallah/Moza"
                                value="<?php echo $parea;?>">
                            <i class="zmdi zmdi-turning-sign"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="punit" placeholder="Unit/Union Council"
                                value="<?php echo $punit;?>">
                            <i class="zmdi zmdi-map"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="ptehsil" placeholder="Tehsil"
                                value="<?php echo $ptehsil;?>">
                            <i class="zmdi zmdi-pin-drop"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-holder">
                            <input type="text" class="form-control" name="pdistrict" placeholder="Enter District"
                                value="<?php echo $pdistrict;?>">
                            <i class="zmdi zmdi-pin"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="select">
                            <div class="form-holder">
                                 <input type="hidden" class="form-control" id="pprovince" name="pprovince" hidden>
                                <div class="select-control urdu-font" id="divpprovince">
                                    <?php if ($pprovince == 'Punjab') echo 'Punjab'; elseif ($pprovince == 'Sindh') echo 'Sindh'; elseif ($pprovince == 'Balochistan') echo 'Balochistan'; elseif ($pprovince == 'Azad Jammu Kashmir') echo 'Azad Jammu Kashmir'; elseif ($pprovince == 'Gilgit Baltistan') echo 'Gilgit Baltistan'; elseif ($pprovince == 'Khyber Pakhtoonkhwa') echo 'Khyber Pakhtoonkhwa'; else echo'Province'; ?>
                                </div>
                                <i class="zmdi zmdi-caret-down "></i>
                            </div>
                            <ul class="dropdown">
                                <li rel="Azad Jammu Kashmir">Azad Jammu Kashmir</li>
                                <li rel="Balochistan">Balochistan</li>
                                <li rel="Gilgit Baltistan">Gilgit Baltistan</li>
                                <li rel="Khyber Pakhtoonkhwa">Khyber Pakhtoonkhwa</li>
                                <li rel="Punjab">Punjab</li>
                                <li rel="Sindh">Sindh</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>

    <!-- JQUERY STEP -->
    <script src="js/jquery.steps.js"></script>
    <script src="js/main.js"></script>

    <!-- DATE-PICKER -->
    <script src="vendor/date-picker/js/datepicker.js"></script>
    <script src="vendor/date-picker/js/datepicker.en.js"></script>
    
    <script>
        ChangeLanguage();
        $("#language").on("change", function(){
            ChangeLanguage();
        });
        function ChangeLanguage(){
            var language =  $('#language').val();
            if(language == "English"){
                $("i").removeClass("hide");
                $("input[type='text']").css("text-align","left");
                $("input[type='email']").css("text-align","left");
                $("select").css("text-align","left");
                
                //placeHolder
                $('input[name="mobileno"]').attr("placeholder", "Mobile Number");
                $('input[name="name"]').attr("placeholder", "Full Name");
                $('input[name="fathername"]').attr("placeholder", "Father Name");
                $('input[name="saaddress"]').attr("placeholder", "Address");
                $('input[name="whatsappno"]').attr("placeholder", "Whatsapp no");
                $('input[name="emailaddress"]').attr("placeholder", "Email");
                $('input[name="profession"]').attr("placeholder", "Profession");
                $('input[name="phouseno"]').attr("placeholder", "House No");
                $('input[name="pstreetno"]').attr("placeholder", "Street No");
                $('input[name="parea"]').attr("placeholder", "Area/Mohallah/Village");
                $('input[name="punit"]').attr("placeholder", "Unit/Union Council");
                $('input[name="ptehsil"]').attr("placeholder", "Tehsil");
                $('input[name="pdistrict"]').attr("placeholder", "District");
                $("#paddress").html('Permanent Address');
                $('input[name="thouseno"]').attr("placeholder", "House No");
                $('input[name="tstreetno"]').attr("placeholder", "Street No");
                $('input[name="tarea"]').attr("placeholder", "Area/Mohallah/Village");
                $('input[name="tunit"]').attr("placeholder", "Unit/Union Council");
                $('input[name="ttehsil"]').attr("placeholder", "Tehsil");
                $('input[name="tdistrict"]').attr("placeholder", "District");
                $("#taddress").html('Present Address');
            }
            else{
                $("i").addClass("hide");
                $("input[type='text']").css("text-align","right");
                $("input[type='email']").css("text-align","right");
                $("select").css("text-align","right");
                
                //placeHolder
                $('input[name="mobileno"]').attr("placeholder", "فون نمبر");
                $('input[name="name"]').attr("placeholder", "نام");
                $('input[name="fathername"]').attr("placeholder", "والد کا نام");
                $('input[name="saaddress"]').attr("placeholder", "مکمل پتہ");
                $('input[name="profession"]').attr("placeholder", "پیشہ");
                $('input[name="whatsappno"]').attr("placeholder", "واٹس ایپ نمبر");
                $('input[name="emailaddress"]').attr("placeholder", "ای میل ایڈریس");
                $('input[name="phouseno"]').attr("placeholder", "مکان نمبر");
                $('input[name="pstreetno"]').attr("placeholder", "گلی نمبر");
                $('input[name="parea"]').attr("placeholder", "ایریا/محلہ/گاؤں");
                $('input[name="punit"]').attr("placeholder", "یونٹ/یونین کونسل");
                $('input[name="ptehsil"]').attr("placeholder", "تحصیل");
                $('input[name="pdistrict"]').attr("placeholder", "ضلع");
                $("#paddress").html('مستقل پتہ')
                $('input[name="thouseno"]').attr("placeholder", "مکان نمبر");
                $('input[name="tstreetno"]').attr("placeholder", "گلی نمبر");
                $('input[name="tarea"]').attr("placeholder", "ایریا/محلہ/گاؤں");
                $('input[name="tunit"]').attr("placeholder", "یونٹ/یونین کونسل");
                $('input[name="ttehsil"]').attr("placeholder", "تحصیل");
                $('input[name="tdistrict"]').attr("placeholder", "ضلع");
                $("#taddress").html('موجودہ پتہ')
            }
                $("input").addClass("urdu-font");
                $("#paddress").addClass("urdu-font");
                $("#taddress").addClass("urdu-font");
        }
    </script>
    
</body>

</html>