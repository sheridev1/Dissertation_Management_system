<?php
include 'sessions.php';
if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: admin.php' );
    exit();
}
if ( isset( $_POST[ 'imgclick' ] ) ) {

    include '../connection.php';
    $target_dir = '../images/users/';
    $target_file = $target_dir . basename( $_FILES[ 'img' ][ 'name' ] );
    $uploadOk = 1;
    $imageFileType = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
    // Check if image file is a actual image or fake image
    if ( isset( $_POST[ 'submit' ] ) ) {
        $check = getimagesize( $_FILES[ 'img' ][ 'tmp_name' ] );
        if ( $check !== false ) {
            $error = 'File is an image - ' . $check[ 'mime' ] . '.';
            $uploadOk = 1;
        } else {
            echo 'File is not an image.';
            $uploadOk = 0;
        }
    }

    // Check file size
    if ( $_FILES[ 'img' ][ 'size' ] > 5000000 ) {
        $error = 'Sorry, your file is too large.';
        $uploadOk = 0;
    }
    // Allow certain file formats
    if (
        $imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
        && $imageFileType != 'gif'
    ) {
        $error = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ( $uploadOk == 0 ) {
        $msg = "<script>alert('" . $error . "');</script>";
        // if everything is ok, try to upload file
    } else {
        $sql = 'select username from student where id=' . $_SESSION[ 'id' ];
        $result = $con->query( $sql );
        if ( $result->num_rows > 0 ) {
            // output data of each row
            while ( $row = $result->fetch_assoc() ) {
                $uss = $row[ 'username' ];
            }
        }
        $imgsrc = $target_dir . $_SESSION[ 'id' ] . $uss . '.' . $imageFileType;
        if ( move_uploaded_file( $_FILES[ 'img' ][ 'tmp_name' ], $imgsrc ) ) {
            $sql = "UPDATE `student` SET `img`='" . $imgsrc . "' WHERE id=" . $_SESSION[ 'id' ];
            if ( $con->query( $sql ) ) {
                $_SESSION[ 'img' ] = $imgsrc;
                $msg = "<script>alert('Changes Saved!');</script>";
                $file = $_POST[ 'previmg' ];

            } else
            die( $con->error );

        }
    }
}
if ( isset( $_POST[ 'fname' ] ) ) {
    include '../connection.php';
    $fname = $_POST[ 'fname' ];
    $lname = $_POST[ 'lname' ];
    $country = $_POST[ 'country' ];
    $university = $_POST[ 'university' ];
    $username = $_POST[ 'username' ];
    $pass = $_POST[ 'password' ];

    $qual = $_POST[ 'qual' ];
    $email = $_POST[ 'em' ];
    $gender = $_POST[ 'gender' ];
    $sql = "UPDATE `student` set `fname`='" . $fname . "',`lname`='" . $lname . "',`university`='" . $university . "',`country`='" . $country . "',`username`='" . $username . "',`pass`='" . md5($pass) . "',`email`='" . $email . "',`gender`='" . $gender . "',`qualifications`='" . $qual . "' WHERE id=" . $_SESSION[ 'id' ];
    if ( $con->query( $sql ) == true ) {
        $_SESSION[ 'fullname' ] = $fname . ' ' . $lname;
        $_SESSION[ 'university' ] = $university;
        $msg = "<script>alert('Changes Saved!');</script>";
    } else {
        die( $con->error );
        $msg = "<script>alert('Error!');</script>";
    }
}
$sql = 'SELECT * FROM `student`  where id=' . $_SESSION[ 'id' ];
$result = $con->query( $sql );
$row = $result->fetch_assoc()
?>
<?php
include 'header.php';
?>

<div class = 'dashboard-headline'>
<h3>Profile</h3>

<!-- Breadcrumbs -->
<nav id = 'breadcrumbs' class = 'dark'>
<ul>
<li><a href = '/'>Home</a></li>
<li><a href = '/userlogin.php'>Dashboard</a></li>
<li>Profile</li>
</ul>
</nav>
</div>

<!-- Row -->
<div class = 'row'>

<!-- Dashboard Box -->
<div class = 'col-xl-12'>
<div class = 'dashboard-box margin-top-0'>

<!-- Headline -->
<div class = 'headline'>
<h3><i class = 'icon-feather-folder-plus'></i>Update Profile</h3>
</div>

<form method = 'post' enctype = 'multipart/form-data' action = '#'>
<input type = 'hidden' name = 'userid' value = "<?php $_SESSION['id'] ?>" />
<div class = 'content with-padding padding-bottom-10'>
<div class = 'row'>
<div class = 'col-xl-6'>
<div class = 'submit-field'>
<h5>First Name</h5>
<input type = 'text' class = 'with-border' required name = 'fname' placeholder = 'First name'
value = "<?php echo $row['fname'] ?>" />
</div>
</div>

<div class = 'col-xl-6'>
<div class = 'submit-field'>
<h5>Last Name</h5>
<input type = 'text' class = 'with-border' required name = 'lname' placeholder = 'Last name'
value = "<?php  echo $row['lname'] ?>" />
</div>
</div>

<div class = 'col-xl-6'>
<div class = 'submit-field'>
<select class = 'selectpicker with-border' name = 'gender' data-size = '7' title = 'Gender'>
<option <?php if ( $row[ 'gender' ] == 'male' )
echo 'selected';
?> value = 'male'> Male
</option>
<option <?php if ( $row[ 'gender' ] == 'female' )
echo 'selected';
?> value = 'female'> Female
</option>
</select>
</div>
</div>

<div class = 'col-xl-6'>
<div class = 'submit-field'>
<select class = 'selectpicker with-border' required name = 'country' data-size = '7'
title = 'Country'>
<option value = ''>Select Country</option>
<option value = 'Afganistan'>Afghanistan</option>
<option value = 'Albania'>Albania</option>
<option value = 'Algeria'>Algeria</option>
<option value = 'American Samoa'>American Samoa</option>
<option value = 'Andorra'>Andorra</option>
<option value = 'Angola'>Angola</option>
<option value = 'Anguilla'>Anguilla</option>
<option value = 'Antigua &amp; Barbuda'>Antigua &amp;
Barbuda</option>
<option value = 'Argentina'>Argentina</option>
<option value = 'Armenia'>Armenia</option>
<option value = 'Aruba'>Aruba</option>
<option value = 'Australia'>Australia</option>
<option value = 'Austria'>Austria</option>
<option value = 'Azerbaijan'>Azerbaijan</option>
<option value = 'Bahamas'>Bahamas</option>
<option value = 'Bahrain'>Bahrain</option>
<option value = 'Bangladesh'>Bangladesh</option>
<option value = 'Barbados'>Barbados</option>
<option value = 'Belarus'>Belarus</option>
<option value = 'Belgium'>Belgium</option>
<option value = 'Belize'>Belize</option>
<option value = 'Benin'>Benin</option>
<option value = 'Bermuda'>Bermuda</option>
<option value = 'Bhutan'>Bhutan</option>
<option value = 'Bolivia'>Bolivia</option>
<option value = 'Bonaire'>Bonaire</option>
<option value = 'Bosnia &amp; Herzegovina'>Bosnia &amp;
Herzegovina</option>
<option value = 'Botswana'>Botswana</option>
<option value = 'Brazil'>Brazil</option>
<option value = 'British Indian Ocean Ter'>British Indian Ocean Ter</option>
<option value = 'Brunei'>Brunei</option>
<option value = 'Bulgaria'>Bulgaria</option>
<option value = 'Burkina Faso'>Burkina Faso</option>
<option value = 'Burundi'>Burundi</option>
<option value = 'Cambodia'>Cambodia</option>
<option value = 'Cameroon'>Cameroon</option>
<option value = 'Canada'>Canada</option>
<option value = 'Canary Islands'>Canary Islands</option>
<option value = 'Cape Verde'>Cape Verde</option>
<option value = 'Cayman Islands'>Cayman Islands</option>
<option value = 'Central African Republic'>Central African Republic</option>
<option value = 'Chad'>Chad</option>
<option value = 'Channel Islands'>Channel Islands</option>
<option value = 'Chile'>Chile</option>
<option value = 'China'>China</option>
<option value = 'Christmas Island'>Christmas Island</option>
<option value = 'Cocos Island'>Cocos Island</option>
<option value = 'Colombia'>Colombia</option>
<option value = 'Comoros'>Comoros</option>
<option value = 'Congo'>Congo</option>
<option value = 'Cook Islands'>Cook Islands</option>
<option value = 'Costa Rica'>Costa Rica</option>
<option value = 'Cote DIvoire'>Cote D\'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                                    <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>University</h5>
                                <input type="text" class="with-border" required name="university"
                                    placeholder="University" value="<?php  echo $row['university']?>" />
                            </div>
                        </div>
                        
                        <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>Qualification</h5>
                                <input type="text" class="with-border" required name="qual"
                                    placeholder="Qualification" value="<?php  echo $row['qualifications']?>" />
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="submit-field">
                                <h5>Email</h5>
                                <input type="text" class="with-border" required name="em"
                                    placeholder="Qualification" value="<?php  echo $row['email']?>" />
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>User Name</h5>
                                <input readonly type="text" class="with-border" required name="username"
                                    placeholder="Username" value="<?php  echo $row['username']?>" />
                            </div>
                        </div>
                        
                        <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>Password</h5>
                                <input type="password" class="with-border" required name="password"
                                    placeholder="....." value="<?php  echo $row['pass']?>" />
                            </div>
                        </div>



                        <div class="col-xl-12">

                            <button type="submit" style='color:white' name="submit"
                                class="button ripple-effect big margin-top-30">Update</button>
                        </div>
            </form>
            <form enctype="multipart/form-data"  method="post"  action="#">  
            <input type="hidden" name="userid" value="'.$_SESSION[ 'id' ].'"/>
     <input type="hidden" name="previmg" value="'.$row[ 'img' ].'"/>
     <input type="hidden" name="imgclick" value="'.$_SESSION[ 'id' ].'"/>
     <div class="col-xl-12">
                            <div class="submit-field">
                                
     <input required type="file" name="img"/>
                            </div>
                        </div>

     <button type="submit" style='color:white' name="submit"
                                class="button ripple-effect big margin-top-30">Update Image</button>
</form>


        </div>
    </div>

</div>

</div>
</div>


<?php include 'footer.php';
?>