<?php
include '../connection.php';
include( 'sessions.php' );
$teacher_id = $_POST[ 'teacher_id' ];
$my_message = $_POST[ 'my_message' ];

$session_id = $_SESSION[ 'id' ];
$query = mysqli_query( $con, 'select * from supervisor where id ='.$teacher_id )or die( mysqli_error() );
$row = mysqli_fetch_array( $query );
$name = $row[ 'fname' ].' '.$row[ 'lname' ];

$query1 = mysqli_query( $con, 'select * from supervisor where id ='.$session_id )or die( mysqli_error() );
$row1 = mysqli_fetch_array( $query1 );
$name1 = $row1[ 'fname' ].' '.$row1[ 'lname' ];

mysqli_query( $con, "insert into message (reciever_id,content,date_sended,sender_id,reciever_name,sender_name,sender_is_teacher,reciever_is_teacher) values('$teacher_id','$my_message',NOW(),'$session_id','$name','$name1',1,1)" )or die( mysqli_error() );
mysqli_query( $con, "insert into message_sent (reciever_id,content,date_sended,sender_id,reciever_name,sender_name,sender_is_teacher,reciever_is_teacher) values('$teacher_id','$my_message',NOW(),'$session_id','$name','$name1',1,1)" )or die( mysqli_error() );

?>