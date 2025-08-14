<?php
ob_start();
include 'config.php';
include 'word.php';
require('fpdf181/fpdf.php');



// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sanitize input parameters
$selected_class = $_GET['class'];
$selected_year = $_GET['year'];



// Validate required parameters
if(empty($selected_class) || empty($selected_year)) {
    die("Error: Class and year parameters are required");
}

// Get all students in the selected class and year ordered by roll number
$students_query = mysqli_query($conn,
    "SELECT * FROM student
     WHERE class = '$selected_class' AND year = '$selected_year'
     ORDER BY roll ASC") or die("Database error: " . mysqli_error($conn));

// Check if any students found
if(mysqli_num_rows($students_query) == 0) {
    die("No students found for Class: $selected_class, Year: $selected_year");
}




// Initialize PDF
class PDF_Rotate extends FPDF {
    var $angle = 0;

    function Rotate($angle, $x = -1, $y = -1) {
        if($x == -1) $x = $this->x;
        if($y == -1) $y = $this->y;
        if($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;
        if($angle != 0) {
            $angle *= M_PI/180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x*$this->k;
            $cy = ($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage() {
        if($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
}

class PDF extends PDF_Rotate {
    function RotatedText($x, $y, $txt, $angle) {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
}

#Create PDF instance
$pdf = new PDF('P','mm','A4');
$image1 = "fpdf181/logos.png";
$image2 = "fpdf181/signeture1.png";


// Process each student
while($student = mysqli_fetch_assoc($students_query)) {
    $stu_id = $student['id'];


    if($stu_id !='' ){


        $result = mysqli_query($conn,"SELECT * FROM student where id='$stu_id' order by id DESC") or die(mysqli_error());

        $tresult=mysqli_num_rows($result);

        if (isset($tresult) && $tresult!=null) {

            while($info = mysqli_fetch_array( $result )) {

                $studentName=$info['name'];
                $sid=$info['id'];
                $student_code=$info['studentID'];
                $father=$info['fname'];
                $mather=$info['mname'];
                $mobile=$info['mobile'];
                $year=$info['year'];
                $class=$info['class'];
                $cl=$info['class'];
                $roll=$info['roll'];
                $address=$info['address'];
                $student_type=$info['student_type'];
                $group=$info['student_group'];
                $birth_registration_id=$info['birth_registration_id'];
                $pdistrict=$info['pdistrict'];
                $religion=$info['religion'];
                $gender=$info['male'];
                $dates=$info['dates'];
                $dates= $dates ? date("d-m-Y",strtotime($dates)) : 'N/A';

            }


            $ts = mysqli_query($conn,"SELECT COUNT(id) FROM student where year='$year' AND class='$class'") or die(mysqli_error());
            while($ts_in = mysqli_fetch_array( $ts )) {
                $total_student = $ts_in["COUNT(id)"];
            }



            $positionfind = mysqli_query($conn,"SELECT * FROM ( SELECT s.*, @rank := @rank + 1 rank FROM ( SELECT student_id, sum(1tutorial+2tutorial+3tutorial+1classtest+2classtest+3classtest+evaluation_exam+half_year_exam+final_exam) TotalPoints FROM new_result WHERE class='$class' and year = '$year' GROUP BY student_id ) s, (SELECT @rank := 0) init ORDER BY TotalPoints DESC ) r WHERE student_id = '$sid'");
            while($positionx = mysqli_fetch_array( $positionfind )) {
                $position = convert_number_to_words($positionx['rank']);
            }

            $positionfinde = mysqli_query($conn,"SELECT * FROM ( SELECT s.*, @rank := @rank + 1 rank FROM ( SELECT student_id, sum(1tutorial+1classtest+evaluation_exam) TotalPoints FROM new_result WHERE class='$class' and year = '$year' GROUP BY student_id ) s, (SELECT @rank := 0) init ORDER BY TotalPoints DESC ) r WHERE student_id = '$sid'");
            while($positionxe = mysqli_fetch_array( $positionfinde )) {
                $positione = convert_number_to_words($positionxe['rank']);
            }

            $positionfindh = mysqli_query($conn,"SELECT * FROM ( SELECT s.*, @rank := @rank + 1 rank FROM ( SELECT student_id, sum(2tutorial+2classtest+half_year_exam) TotalPoints FROM new_result WHERE class='$class' and year = '$year' GROUP BY student_id ) s, (SELECT @rank := 0) init ORDER BY TotalPoints DESC ) r WHERE student_id = '$sid'");
            while($positionxh = mysqli_fetch_array( $positionfindh )) {
                $positionh = convert_number_to_words($positionxh['rank']);
            }

            $positionfindf = mysqli_query($conn,"SELECT * FROM ( SELECT s.*, @rank := @rank + 1 rank FROM ( SELECT student_id, sum(3tutorial+3classtest+final_exam) TotalPoints FROM new_result WHERE class='$class' and year = '$year' GROUP BY student_id ) s, (SELECT @rank := 0) init ORDER BY TotalPoints DESC ) r WHERE student_id = '$sid'");
            while($positionxf = mysqli_fetch_array( $positionfindf )) {
                $positionf = convert_number_to_words($positionxf['rank']);
            }



        }
    }



// Create PDF instance
// $pdf = new PDF('P','mm','A4');
// $image1 = "fpdf181/logos.png";
// $image2 = "fpdf181/signeture1.png";

    $pdf->AddPage();


    $pdf->Cell(190,1,'',0,1);

    $pdf->SetFont('Arial','B',18);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell( 35, 20, ' ', 0, 0, 'C' );
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(110,7,' ',0,0,'C');

    $pdf->SetFont('Arial','B',5.5);
    $pdf->Cell(14,7,' ',0,0);
    $pdf->Cell(18,7,' ',0,0);
    $pdf->Cell(13,7,' ',0,1);



    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,5,'',0,0,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(110,5,'',0,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(14,7,'',0,0);
    $pdf->Cell(18,7,'',0,0);
    $pdf->Cell(13,7,'',0,1);





    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,5,'',0,0,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(110,5,' ',0,0,'C');

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(14,7,' ',0,0);
    $pdf->Cell(18,7,' ',0,0);
    $pdf->Cell(13,7,' ',0,1);


    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,5,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(110,5,' ',0,0,'C');

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(14,7,'',0,0);
    $pdf->Cell(18,7,'',0,0);
    $pdf->Cell(13,7,'',0,1);



    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,6,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(110,5,' ',0,0,'C');
    $pdf->SetFont('Arial','B',8);

    $pdf->Cell(14,7,' ',0,0);
    $pdf->Cell(18,7,' ',0,0);
    $pdf->Cell(13,7,' ',0,1);


    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,5,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(110,5,' ',0,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(14,7,' ',0,0);
    $pdf->Cell(18,7,' ',0,0);
    $pdf->Cell(13,7,' ',0,1);



    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,5,'',0,0,'L');
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(110,9,'ACADEMIC TRANSCRIPT-'.$year,0,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(14,7,' ',0,0);
    $pdf->Cell(18,7,' ',0,0);
    $pdf->Cell(13,7,' ',0,1);










// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(100,5,"Student Name: $studentName",0,0,'L');
// $pdf->SetFont('Arial','B',12);
// $pdf->Cell(55,5,'',0,0,'C');
// $pdf->SetFont('Arial','B',8);

// $pdf->Cell(35,5,'   ',1,1);



    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(100,5,"Student Id No. $student_code",0,0,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(45,5,'',0,0,'C');
    $pdf->SetFont('Arial','B',8);

    $pdf->Cell(14,7,'',0,0);
    $pdf->Cell(18,7,'',0,0);
    $pdf->Cell(13,7,'',0,1);

    $pdf->Cell( 180,2,'', 0,1,'L');

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(100,5,"Student Name: $studentName",0,0,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(45,5,'',0,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,' ', 0,0,'L');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 13,5,' ', 0,1,'L');



    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(100,5,"Fathers Name: $father",0,0,'L');
    $pdf->Cell(45,5,'',0,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,"", 0,0,'L');
    $pdf->Cell( 13,5,'', 0,1,'L');

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(100,5,"Mothers Name: $mather",0,0,'L');
    $pdf->Cell(45,5,'',0,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,'', 0,0,'L');
    $pdf->Cell( 13,5,'', 0,1,'L');
//

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(50,5,"Type of Student: $student_type",0,0,'L');

    if ($class=="nine business studies" or $class=="nine science" or $class=="nine humanities") {
        $class="nine";
    }elseif ($class=="ten science" or $class=="ten humanities" or $class=="ten business studies") {
        $class="ten";
    }
    $class=ucfirst($class);


    $pdf->Cell(49,5,"Class : $class",0,0,'L');


    $pdf->Cell(46,5,"Class Roll: $roll",0,0,'L');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,' ', '0',0,'L');
    $pdf->Cell( 13,5,' ', '0,0',1,'L');


    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(50,5,"Group: $group",0,0,'L');

    $pdf->Cell(49,5,"Date of Birth: $dates",0,0,'L');
    $pdf->Cell(46,5,"Mobile Number: $mobile",0,0,'L');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,'', '0',0,'L');
    $pdf->Cell( 13,5,'', '0,0',1,'L');


// Same switch block
    $order_by_case = "";

    switch (strtolower($selected_class)) {
        case 'nursery':
            $order = [101, 107, 109, 111, 112, 113, 180, 181];
            break;
        case 'one':
        case 'two':
            $order = [101, 107, 109, 150, 111, 112, 113, 180, 181];
            break;
        case 'three':
        case 'four':
        case 'five':
            $order = [101, 107, 109, 150, 127, 111, 112, 113];
            break;
        case 'six':
        case 'seven':
        case 'eight':
            $order = [101, 102, 107, 108, 109, 150, 127, 111, 112, 113, 154, 134];
            break;
        case 'ten science':
            $order = [101, 102, 107, 108, 109, 150, 111, 112, 113, 154, 136, 137, 138, 126];
            break;
        default:
            $order = [];
    }

// ✅ Now print the $order array
// echo '<pre>';
// foreach ($order as $sub_code) {
//     print_r($sub_code);
//     print_r($selected_class);
//     echo "\n----------\n";
// }
// echo '</pre>';

// return 0;


    if (!empty($order)) {
        $order_by_case = "CASE sub_code ";
        foreach ($order as $index => $code) {
            $order_by_case .= "WHEN $code THEN $index ";
        }
        $order_by_case .= "ELSE " . count($order) . " END";
    } else {
        $order_by_case = "sub_code"; // fallback to normal sorting
    }

// Now the SQL query
    $query = "SELECT * FROM new_result WHERE student_id='$stu_id' AND class='$selected_class' ORDER BY $order_by_case";

    $new_result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $nresult11 = mysqli_num_rows($new_result);

// $new_result11 = mysqli_query($conn,"SELECT * FROM new_result where student_id='$stu_id' ORDER BY FIELD(sub_code, 101, 107, 109, 111, 112, 113, 180, 181)") or die(mysqli_error());
// $nresult11=mysqli_num_rows($new_result11);
    $result11="Passed";

    if (isset($nresult11) && $nresult11!=null) {

        while($ninfo11 = mysqli_fetch_array( $new_result11 )) {


            $sub_name11=$ninfo11['sub_name'];
            $sub_code11=$ninfo11['sub_code'];
            $tutorial11=$ninfo11['1tutorial'];
            $tutorial22=$ninfo11['2tutorial'];
            $tutorial33=$ninfo11['3tutorial'];
            $classtest11=$ninfo11['1classtest'];
            $classtest22=$ninfo11['2classtest'];
            $classtest33=$ninfo11['3classtest'];
            $subject_type11=$ninfo11['subject_type'];
            $sub_mark11=$ninfo11['sub_mark'];


            $dates11=$ninfo11['dates'];

            $dates11=date("d-m-Y",strtotime($dates11));



            $final_exam11=$ninfo11['final_exam'];


            $final_exam_grade11=suject_grade($tutorial33+$classtest33+$final_exam11,$sub_mark11);


            if (isset($final_exam_grade11) && $final_exam_grade11=="F" ) {
                $result11="N/A";
            }






        }


    }
















    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(50,5,"Religion: $religion",0,0,'L');
    $pdf->Cell(49,5,"Place of Birth: $pdistrict",0,0,'L');
    $pdf->Cell(46,5,"Result: $result11",0,0,'L');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,'', '0',0,'L');
    $pdf->Cell( 13,5,'', '0,0',1,'L');


    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(99,5,"Birth Registration Id Number: $birth_registration_id",0,0,'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(46,5,"Place Best on Result: $position",0,0,'L');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell( 32,5,'', 0,0,'L');
    $pdf->Cell( 13,5,'', 0,1,'L');
    if($class == 'Nine' or $class == 'Ten'){
        $pdf->Cell( 190,5,'', 0,1,'L');
    }else{
        $pdf->Cell( 190,4,'', 0,1,'L');
    }



    $religion = strtolower($religion);
    $gender = strtolower($gender);



// $pdf->Cell(65,5,"Evaluation Exam GPA: $evaluation_exam_point",1,0,'L');
// $pdf->Cell(65,5,"Half Yearly Exam GPA: $half_year_point",1,0,'L');
// $pdf->Cell(60,5,"Test Exam GPA/ Final Exam GPA: $final_exam_point",1,1,'L');





//$pdf->Cell( 190,2,'', 0, 1);





    if($class == 'Nine' or $class == 'Ten'){

        $pdf->Cell(5,6,'','L,T',0,'L');
        $pdf->Cell(55,6,'','L,T',0,'L');
        $pdf->Cell(12,6,'','L,T',0,'L');
        $pdf->Cell(60,6,'HALF YEAR EXAM',1,0,'C');
        $pdf->Cell(60,6,'FINAL EXAM',1,1,'C');

        $pdf->Cell(5,20,'Sl.','L,R',0,'L');
        $pdf->Cell(55,20,'Name of Subjects','L,R',0,'L');
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(12,20,$pdf->RotatedText(76,133,'Subject Code',90),'L,R',0,'L');

        $pdf->Cell(12,20,$pdf->RotatedText(89,133,'MCQ',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(101,133,'Written',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(114,133,'Practical',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(125,133,'TOTAL',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(136,133,'GPA',90),'T,R,B',0,'L');


        $pdf->Cell(12,20,$pdf->RotatedText(148,133,'MCQ',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(160,133,'Written',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(173,133,'Practical',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(185,133,'TOTAL',90),'T,R,B',0,'L');
        $pdf->Cell(12,20,$pdf->RotatedText(196,133,'GPA',90),'T,R,B',1,'L');



    }else{

        $pdf->Cell(5,36,'Sl.',1,0,'L');
        $pdf->Cell(55,36,'Name of Subjects',1,0,'L');
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(12,36,$pdf->RotatedText(76,143,'Subject Code',90),'T,R,B',0,'L');

        $pdf->Cell(12,36,$pdf->RotatedText(89,143,'1ST CLASS TEST',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(101,143,'1ST CONTINUOUS ASSESSMENT',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(114,143,'HALF YEAR EXAM',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(125,143,'TOTAL',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(136,143,'HALF YEAR EXAM POINT',90),'T,R,B',0,'L');


        $pdf->Cell(12,36,$pdf->RotatedText(148,143,'2ND CLASS TEST',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(160,143,'2ND CONTINUOUS ASSESSMENT',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(173,143,'FINAL EXAM',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(185,143,'TOTAL',90),'T,R,B',0,'L');
        $pdf->Cell(12,36,$pdf->RotatedText(196,143,'FINAL EXAM POINT',90),'T,R,B',1,'L');


    }





    if($stu_id !='' ){
        $sn=0;

        $evaluationArray=array();

        $half_yearArray=array();

        $final_examArray=array();


        $eban1 = 0;
        $hban1 = 0;
        $fban1 = 0;

        $eeng1 = 0;
        $heng1 = 0;
        $feng1 = 0;

        $query = "SELECT * FROM new_result WHERE student_id='$stu_id' AND class='$selected_class' ORDER BY $order_by_case";

        $new_result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $nresult = mysqli_num_rows($new_result);

        // $new_result = mysqli_query($conn,"SELECT * FROM new_result where student_id='$stu_id' ORDER BY FIELD(sub_code, 101, 107, 109, 111, 112, 113, 180, 181)") or die(mysqli_error());
        // $nresult=mysqli_num_rows($new_result);

        $eee_bangla_grade=0;
        $half_bangla_grade=0;
        $final_bangla_garde=0;

        $eee_english_grade=0;
        $half_english_grade=0;
        $final_english_garde=0;

        $ee11=0;
        $haf11 =0;
        $fianl11=0;
        $bn=0;
        $bsub_mark=0;

        $ee22=0;
        $haf22 =0;
        $fianl22=0;
        $en=0;
        $esub_mark=0;

        $optional22=0;

        $epass=0;
        $hpass=0;
        $fpass=0;










        if (isset($nresult) && $nresult!=null) {

            while($ninfo = mysqli_fetch_array( $new_result )) {



                $sn++;
                $sub_name=$ninfo['sub_name'];
                $sub_code=$ninfo['sub_code'];

                $evulation=$ninfo['evulation'];
                $half=$ninfo['half'];
                $final=$ninfo['final'];







                if($evulation == 'No'){
                    $tutorial1='';
                    $classtest1='';
                    $evaluation_exam='';
                    $etotal='';

                }else{
                    $tutorial1=$ninfo['1tutorial'];
                    $classtest1=$ninfo['1classtest'];
                    $evaluation_exam=$ninfo['evaluation_exam'];
                    $etotal=$tutorial1+$classtest1+$evaluation_exam;

                }

                if($half == 'No'){
                    $tutorial2='';
                    $classtest2='';
                    $half_year_exam='';
                    $htotal='';
                }else{
                    $half_year_exam=$ninfo['half_year_exam'];
                    $tutorial2=$ninfo['2tutorial'];
                    $classtest2=$ninfo['2classtest'];
                    $htotal=$half_year_exam+$tutorial2+$classtest2;
                }

                if($final == 'No'){

                    $tutorial3='';
                    $classtest3='';
                    $final_exam='';
                    $ftotal='';
                }else{
                    $tutorial3=$ninfo['3tutorial'];
                    $classtest3=$ninfo['3classtest'];
                    $final_exam=$ninfo['final_exam'];
                    $ftotal=$tutorial3+$classtest3+$final_exam;
                }

                $subject_type=$ninfo['subject_type'];
                $sub_mark=$ninfo['sub_mark'];

                $dates=$ninfo['dates'];

                $dates=date("d-m-Y",strtotime($dates));



                if ($subject_type=='bangla') {
                    $bn+=1;
                    $ee11 +=$tutorial1+$classtest1+$evaluation_exam;
                    $haf11 +=$tutorial2+$classtest2+$half_year_exam;
                    $fianl11 +=$tutorial3+$classtest3+$final_exam;
                    $bsub_mark+=$sub_mark;

                }

                if ($subject_type=='english') {
                    $en+=1;
                    $ee22 +=$tutorial1+$classtest1+$evaluation_exam;
                    $haf22 +=$tutorial2+$classtest2+$half_year_exam;
                    $fianl22 +=$tutorial3+$classtest3+$final_exam;
                    $esub_mark+=$sub_mark;

                }



                if ($subject_type=='bangla') {

                    if ($bn==1) {
                        $eban1 = $etotal;
                        $hban1 = $htotal;
                        $fban1 = $ftotal;

                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(5,8,"$sn",1,0,'L');


                        $pdf->Cell(55,8,"$sub_name",1,0,'L');

                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(12,8,"$sub_code",1,0,'C');



                        $pdf->Cell(12,8,"$classtest2",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                        $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',0,'C');


                        $pdf->Cell(12,8,"$classtest3",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                        $pdf->Cell(12,8,"$final_exam",1,0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',1,'C');





                    }

                    if ($bn==2) {


                        $eee_bangla_grade=suject_grade($ee11,$bsub_mark);
                        $half_bangla_grade=suject_grade($haf11,$bsub_mark);
                        $final_bangla_garde=suject_grade($fianl11,$bsub_mark);

                        if ($eee_bangla_grade=='F') {
                            $epass=2;
                        }
                        if ($half_bangla_grade=='F') {
                            $hpass=2;
                        }

                        if ($final_bangla_garde=='F') {
                            $fpass=2;
                        }

                        $etotal += $eban1;
                        $htotal += $hban1;
                        $ftotal += $fban1;

                        $htotal = ($htotal == 0 ? '' : $htotal);
                        $ftotal = ($ftotal == 0 ? '' : $ftotal);


                        array_push($evaluationArray, $eee_bangla_grade);

                        array_push($half_yearArray, $half_bangla_grade);
                        array_push($final_examArray, $final_bangla_garde);



                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(5,8,"$sn",1,0,'L');

                        $pdf->Cell(55,8,"$sub_name",1,0,'L');

                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(12,8,"$sub_code",1,0,'C');


                        $pdf->Cell(12,8,"$classtest2",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                        $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                        $pdf->Cell(12,8,"$htotal",'L,R,B',0,'C');
                        if($half == 'No'){
                            $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                            $pdf->Cell(12,8,"$half_bangla_grade",'L,R,B',0,'C');
                        }



                        $pdf->Cell(12,8,"$classtest3",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                        $pdf->Cell(12,8,"$final_exam",1,0,'C');
                        $pdf->Cell(12,8,"$ftotal",'L,R,B',0,'C');

                        if($final == 'No'){
                            $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                            $pdf->Cell(12,8,"$final_bangla_garde",'L,R,B',1,'C');
                        }






                    }





                }


                if ($subject_type=='english') {

                    if ($en==1) {
                        $eeng1 = $etotal;
                        $heng1 = $htotal;
                        $feng1 = $ftotal;

                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(5,8,"$sn",1,0,'L');

                        $pdf->Cell(55,8,"$sub_name",1,0,'L');


                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(12,8,"$sub_code",1,0,'C');


                        $pdf->Cell(12,8,"$classtest2",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                        $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',0,'C');


                        $pdf->Cell(12,8,"$classtest3",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                        $pdf->Cell(12,8,"$final_exam",1,0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',0,'C');
                        $pdf->Cell(12,8,"",'L,R,T',1,'C');



                    }

                    if ($en==2) {


                        $eee_english_grade=suject_grade($ee22,$esub_mark);
                        $half_english_grade=suject_grade($haf22,$esub_mark);
                        $final_english_garde=suject_grade($fianl22,$esub_mark);

                        if ($eee_english_grade=='F') {
                            $epass=2;
                        }
                        if ($half_english_grade=='F') {
                            $hpass=2;
                        }

                        if ($final_english_garde=='F') {
                            $fpass=2;
                        }

                        $etotal += $eeng1;
                        $htotal += $heng1;
                        $ftotal += $feng1;

                        $htotal = ($htotal == 0 ? '' : $htotal);
                        $ftotal = ($ftotal == 0 ? '' : $ftotal);

                        array_push($evaluationArray, $eee_english_grade);

                        array_push($half_yearArray, $half_english_grade);
                        array_push($final_examArray, $final_english_garde);


                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(5,8,"$sn",1,0,'L');

                        $pdf->Cell(55,8,"$sub_name",1,0,'L');

                        $pdf->SetFont('Arial','B',7);
                        $pdf->Cell(12,8,"$sub_code",1,0,'C');


                        $pdf->Cell(12,8,"$classtest2",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                        $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                        $pdf->Cell(12,8,"$htotal",'L,R,B',0,'C');
                        if($half == 'No'){
                            $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                            $pdf->Cell(12,8,"$half_english_grade",'L,R,B',0,'C');
                        }



                        $pdf->Cell(12,8,"$classtest3",1,0,'C');
                        $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                        $pdf->Cell(12,8,"$final_exam",1,0,'C');
                        $pdf->Cell(12,8,"$ftotal",'L,R,B',0,'C');
                        if($final == 'No'){
                            $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                            $pdf->Cell(12,8,"$final_english_garde",'L,R,B',1,'C');
                        }




                    }





                }









                if ($subject_type=='normal') {


                    if($class == 'Nine' or $class == 'Ten'){

                        if($classtest1 > 10 and $tutorial1 > 23){
                            $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;  } else {  $e_total_marks = 0;   }

                        if($classtest2 < 10 || $tutorial2 < 23){
                            $h_total_marks = 0; } else {   $h_total_marks = $tutorial2+$classtest2+$half_year_exam;  }

                        if($classtest3 < 10 || $tutorial3 < 23){
                            $f_total_marks = 0; } else {   $f_total_marks = $tutorial3+$classtest3+$final_exam;  }

                    }else{
                        $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                        $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                        $f_total_marks = $tutorial3+$classtest3+$final_exam;
                    }

                    $evaluation_exam_grade=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade);
                    array_push($half_yearArray, $half_year_exam_grade);
                    array_push($final_examArray, $final_exam_grade);

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');

                    $pdf->Cell(55,8,"$sub_name",1,0,'L');



                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(12,8,"$sub_code",1,0,'C');


                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade",'L,R,B',0,'C');
                    }


                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade",'L,R,B',1,'C');
                    }



                }


                if ($subject_type=='islam' && ( $religion=='islam' or $religion == null)) {

                    if($class == 'Nine' or $class == 'Ten'){

                        if($classtest1 > 10 and $tutorial1 > 23){
                            $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;  } else {  $e_total_marks = 0;   }

                        if($classtest2 < 10 || $tutorial2 < 23){
                            $h_total_marks = 0; } else {   $h_total_marks = $tutorial2+$classtest2+$half_year_exam;  }

                        if($classtest3 < 10 || $tutorial3 < 23){
                            $f_total_marks = 0; } else {   $f_total_marks = $tutorial3+$classtest3+$final_exam;  }

                    }else{
                        $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                        $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                        $f_total_marks = $tutorial3+$classtest3+$final_exam;
                    }

                    $evaluation_exam_grade=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade);
                    array_push($half_yearArray, $half_year_exam_grade);
                    array_push($final_examArray, $final_exam_grade);

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');

                    $pdf->Cell(55,8,"$sub_name",1,0,'L');



                    $pdf->SetFont('Arial','B',7);

                    $pdf->Cell(12,8,"$sub_code",1,0,'C');


                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade",'L,R,B',0,'C');
                    }

                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade",'L,R,B',1,'C');
                    }




                }

                if ($subject_type=='hindu' && $religion=='hindu' ) {


                    if($class == 'Nine' or $class == 'Ten'){

                        if($classtest1 > 10 and $tutorial1 > 23){
                            $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;  } else {  $e_total_marks = 0;   }

                        if($classtest2 < 10 || $tutorial2 < 23){
                            $h_total_marks = 0; } else {   $h_total_marks = $tutorial2+$classtest2+$half_year_exam;  }

                        if($classtest3 < 10 || $tutorial3 < 23){
                            $f_total_marks = 0; } else {   $f_total_marks = $tutorial3+$classtest3+$final_exam;  }

                    }else{
                        $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                        $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                        $f_total_marks = $tutorial3+$classtest3+$final_exam;
                    }

                    $evaluation_exam_grade=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade);
                    array_push($half_yearArray, $half_year_exam_grade);
                    array_push($final_examArray, $final_exam_grade);

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');

                    $pdf->Cell(55,8,"$sub_name",1,0,'L');



                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(12,8,"$sub_code",1,0,'C');

                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade",'L,R,B',0,'C');
                    }

                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade",'L,R,B',1,'C');
                    }

                }
                if ($subject_type=='christian' && $religion=='christian' ) {


                    if($class == 'Nine' or $class == 'Ten'){

                        if($classtest1 > 10 and $tutorial1 > 23){
                            $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;  } else {  $e_total_marks = 0;   }

                        if($classtest2 < 10 || $tutorial2 < 23){
                            $h_total_marks = 0; } else {   $h_total_marks = $tutorial2+$classtest2+$half_year_exam;  }

                        if($classtest3 < 10 || $tutorial3 < 23){
                            $f_total_marks = 0; } else {   $f_total_marks = $tutorial3+$classtest3+$final_exam;  }

                    }else{
                        $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                        $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                        $f_total_marks = $tutorial3+$classtest3+$final_exam;
                    }

                    $evaluation_exam_grade=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade);
                    array_push($half_yearArray, $half_year_exam_grade);
                    array_push($final_examArray, $final_exam_grade);

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');

                    $pdf->Cell(55,8,"$sub_name",1,0,'L');



                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(12,8,"$sub_code",1,0,'C');

                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade",'L,R,B',0,'C');
                    }

                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade",'L,R,B',1,'C');
                    }

                }



                if ($subject_type=='homescience' && $gender=='female' ) {

                    $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                    $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                    $f_total_marks = $tutorial3+$classtest3+$final_exam;


                    $evaluation_exam_grade=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade);
                    array_push($half_yearArray, $half_year_exam_grade);
                    array_push($final_examArray, $final_exam_grade);

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');

                    $pdf->Cell(55,8,"$sub_name",1,0,'L');



                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(12,8,"$sub_code",1,0,'C');


                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade",'L,R,B',0,'C');
                    }

                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade",'L,R,B',1,'C');
                    }

                }


                if ($subject_type=='agriculture' && ($gender=='male' or $gender !='female') ) {

                    $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                    $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                    $f_total_marks = $tutorial3+$classtest3+$final_exam;


                    $evaluation_exam_grade=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade);
                    array_push($half_yearArray, $half_year_exam_grade);
                    array_push($final_examArray, $final_exam_grade);

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');

                    $pdf->Cell(55,8,"$sub_name",1,0,'L');



                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(12,8,"$sub_code",1,0,'C');

                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade",'L,R,B',0,'C');
                    }

                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade",'L,R,B',1,'C');
                    }

                }



                if($subject_type=='optional'){

                    $optional22=1;

                    if($class == 'Nine' or $class == 'Ten'){

                        if($classtest1 > 10 and $tutorial1 > 23){
                            $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;  } else {  $e_total_marks = 0;   }

                        if($classtest2 < 10 || $tutorial2 < 23){
                            $h_total_marks = 0; } else {   $h_total_marks = $tutorial2+$classtest2+$half_year_exam;  }

                        if($classtest3 < 10 || $tutorial3 < 23){
                            $f_total_marks = 0; } else {   $f_total_marks = $tutorial3+$classtest3+$final_exam;  }

                    }else{
                        $e_total_marks = $tutorial1+$classtest1+$evaluation_exam;
                        $h_total_marks = $tutorial2+$classtest2+$half_year_exam;
                        $f_total_marks = $tutorial3+$classtest3+$final_exam;
                    }

                    $evaluation_exam_grade1=suject_grade($e_total_marks,$sub_mark);
                    $half_year_exam_grade1=suject_grade($h_total_marks,$sub_mark);
                    $final_exam_grade1=suject_grade($f_total_marks,$sub_mark);


                    if ($evaluation_exam_grade1=='F') {
                        $epass=2;
                    }
                    if ($half_year_exam_grade1=='F') {
                        $hpass=2;
                    }

                    if ($final_exam_grade1=='F') {
                        $fpass=2;
                    }


                    array_push($evaluationArray, $evaluation_exam_grade1);
                    array_push($half_yearArray, $half_year_exam_grade1);
                    array_push($final_examArray, $final_exam_grade1);

                    $pdf->SetFont('Arial','B',7);

                    $pdf->Cell(192,5,"Optional Subject :",1,1,'L');

                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(5,8,"$sn",1,0,'L');
                    $pdf->Cell(55,8,"$sub_name",1,0,'L');
                    $pdf->SetFont('Arial','B',7);

                    $pdf->Cell(12,8,"$sub_code",1,0,'C');


                    $pdf->Cell(12,8,"$classtest2",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial2",1,0,'C');
                    $pdf->Cell(12,8,"$half_year_exam",1,0,'C');
                    $pdf->Cell(12,8,"$htotal",1,0,'C');
                    if($half == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',0,'C'); }else{
                        $pdf->Cell(12,8,"$half_year_exam_grade1",'L,R,B',0,'C');
                    }

                    $pdf->Cell(12,8,"$classtest3",1,0,'C');
                    $pdf->Cell(12,8,"$tutorial3",1,0,'C');
                    $pdf->Cell(12,8,"$final_exam",1,0,'C');
                    $pdf->Cell(12,8,"$ftotal",1,0,'C');
                    if($final == 'No'){
                        $pdf->Cell(12,8,"",'L,R,B',1,'C'); }else{
                        $pdf->Cell(12,8,"$final_exam_grade1",'L,R,B',1,'C');
                    }



                }




            }



        }
    }






    $total_subject=count($evaluationArray);

    $e_sum=array_sum($evaluationArray);
    $h_sum=array_sum($half_yearArray);
    $f_sum=array_sum($final_examArray);

    if ($optional22==1) {
        $total_subject=$total_subject-1;
        $e_sum-=2;
        $h_sum-=2;
        $f_sum-=2;
    }

    $e_grade=round($e_sum/$total_subject,2) > 5 ? 5: round($e_sum/$total_subject,2);
    $h_grade=round($h_sum/$total_subject,2) > 5 ? 5: round($h_sum/$total_subject,2);
    $f_grade=round($f_sum/$total_subject,2) > 5 ? 5: round($f_sum/$total_subject,2);

    if ($epass==2) {
        $e_grade='N/A';
    }
    if ($hpass==2) {
        $h_grade='N/A';
    }
    if ($fpass==2) {
        $f_grade='N/A';
    }






    $pdf->Cell( 190,3,'', 0,1,'L');

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(60,5,"Half-Yearly Exam Details",1,0,'L');
    $pdf->Cell(70,5,"Final Exam Details/Test Exam Details",1,1,'L');

// $pdf->Cell(60,5,"Half-Yearly Exam Details",1,0,'L');
// $pdf->Cell(10,5,'',1,1,'L');

    $totals = mysqli_query($conn,"SELECT sum(1tutorial+1classtest+evaluation_exam) as evulation, sum(2tutorial+2classtest+half_year_exam) as half, sum(3tutorial+3classtest+final_exam) as final FROM new_result where student_id='$sid' and class = '$cl' ");
    while($total = mysqli_fetch_array( $totals )) {
        $evulationx = $total['evulation'];
        $halfx = $total['half'] == 0 ? 'N/A' : $total['half'] ;
        $finalx = $total['final'] == 0 ? 'N/A' : $total['final'] ;
    }


    if (isset($e_grade)) {

        $e_grade = $e_grade != 'N/A' ? number_format($e_grade, 2) : $e_grade;
        $f_grade = $f_grade != 'N/A' ? number_format($f_grade, 2) : $f_grade;
        $h_grade = $h_grade != 'N/A' ? number_format($h_grade, 2) : $h_grade;

        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(50,5,"Total Marks","L",0,'L');
        $pdf->Cell(10,5,"$halfx","R",0,'L');
        $pdf->Cell(60,5,"Total Marks","L",0,'L');
        $pdf->Cell(10,5,"$finalx","R",1,'L');

        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(50,5,"Half Yearly Exam GPA","L",0,'L');
        $pdf->Cell(10,5,"$h_grade","R",0,'L');
        $pdf->Cell(60,5,"Test / Final Exam GPA","L",0,'L');
        $pdf->Cell(10,5,"$f_grade","R",1,'L');
    }

    $pdf->SetFont('Arial','B',7);

    if($e_grade == "N/A"){
        $positione = "N/A";
    }
    if($h_grade == "N/A"){
        $positionh = "N/A";
    }
    if($f_grade == "N/A"){
        $positionf = "N/A";
    }
// total student
    $pdf->Cell(50,5,"Total Students","L",0,'L');
    $pdf->Cell(10,5,"$total_student","R",0,'L');
    $pdf->Cell(60,5,"Total Students","L",0,'L');
    $pdf->Cell(10,5,"$total_student","R",1,'L');

    $pdf->Cell(50,5,"Meritocracy","BL",0,'L');
    $pdf->Cell(10,5,"$positionh","RB",0,'L');
    $pdf->Cell(60,5,"Meritocracy","BL",0,'L');
    $pdf->Cell(10,5,"$positionf","RB",1,'L');

    $pdf->Cell( 190,5,'', 0, 1);







    $pdf->SetFont('Arial','B',8);
    $pdf->Cell( 100,5,"Date of Publication of Results: $dates", 0, 0,'L');
    $pdf->Cell( 90,5,' ', 0, 1,'R');






}
function suject_grade($getMark,$subMark){



    $persent90 =(90/100)*$subMark;

    $persent80 =(80/100)*$subMark;

    $persent70 =(70/100)*$subMark;

    $persent60 =(60/100)*$subMark;

    $persent50 =(50/100)*$subMark;

    $persent40 =(40/100)*$subMark;

    $persent33 =(33/100)*$subMark;


    if ($persent90<=$getMark) {
        return "4.0";
    }elseif ($persent80<=$getMark) {
        return "3.5";
    }elseif ($persent70<=$getMark) {
        return "3";
    }elseif ($persent60<=$getMark) {
        return "2.5";
    }elseif ($persent50<=$getMark) {
        return "2.0";
    }elseif ($persent40<=$getMark) {
        return "1.5";
    }elseif ($persent33<=$getMark) {
        return "1.0";
    }else{
        return "F";
    }




}

ob_end_clean();
// Output PDF
//$pdf->Output("Class_{$selected_class}_Year_{$selected_year}_Transcripts.pdf", 'D');
$pdf->Output('I', 'transcript.pdf');
?>
