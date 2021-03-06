<?php @session_start(); ?>
<?php include 'connection/connect_i.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title>ระบบข้อมูลบุคคลากรโรงพยาบาล</title>
<LINK REL="SHORTCUT ICON" HREF="images/logo.png">
<!-- Bootstrap core CSS -->
<link href="option/css/bootstrap.css" rel="stylesheet">
<!--<link href="option/css2/templatemo_style.css" rel="stylesheet">-->
<!-- Add custom CSS here -->
<link href="option/css/sb-admin.css" rel="stylesheet">
<link rel="stylesheet" href="option/font-awesome/css/font-awesome.min.css">
<!-- Page Specific CSS -->
<link rel="stylesheet" href="option/css/morris-0.4.3.min.css">
<link rel="stylesheet" href="option/css/stylelist.css">
<script src="option/js/excellentexport.js"></script>
 
<!-- InstanceBeginEditable name="head" -->
    </head>
    <body>
<?php
if (empty($_SESSION['user'])) {
    echo "<meta http-equiv='refresh' content='0;url=index.php'/>";
    exit();
}
?>
<?php include 'option/function_date.php';
$empno = $_GET['id'];
if (!empty($_POST['id'])) {
    $empno = $_POST['id'];
} elseif ($_SESSION['Status'] == 'USER') {
    $empno = $_SESSION['user'];
}
$name_detial = mysqli_query($db,"select concat(p1.pname,e1.firstname,' ',e1.lastname) as fullname,
                            d1.depName as dep,p2.posname as posi,e1.empno as empno
                            from emppersonal e1 
                            inner join pcode p1 on e1.pcode=p1.pcode
                            inner join department d1 on e1.depid=d1.depId
                            INNER JOIN work_history wh ON wh.empno=e1.empno
                            inner join posid p2 on wh.posid=p2.posId
                            where e1.empno='$empno' and (wh.dateEnd_w='0000-00-00' or ISNULL(wh.dateEnd_w)) order by wh.his_id desc");
$method = isset($_POST['method'])?$_POST['method']:'';
if ($method == 'check_detial_leave' and !empty($_GET['check_date01'])) {
    $date01 = $_GET['check_date01'];
    $date02 = $_GET['check_date02'];

    $detial = mysqli_query($db,"SELECT * from work w1
                        inner join typevacation t1 on w1.typela=t1.idla
                        where enpid='$empno' and w1.begindate between '$date01' and '$date02' and w1.enddate between '$date01' and '$date02'
                            AND statusla='Y' order by w1.begindate desc");
    $detiatl = mysqli_query($db,"SELECT p.*,t.projectName,t.anProject,t.Beginedate,t.endDate,t.stantee,p2.PROVINCE_NAME,
                t.stdate,t.etdate
    from plan_out p 
        inner join training_out t on t.tuid=p.idpo
        inner join province p2 on p2.PROVINCE_ID=t.provenID
where p.empno='$empno' and p.status_out='Y' and (begin_date between '$date01' and '$date02') order by begin_date desc");
    $detial_tin = mysqli_query($db,"SELECT p.*,t.in1,t.in2 from plan p
                        inner join trainingin t on p.pjid=t.idpi
                        where type_id='$empno' and (bdate between '$date01' and '$date02') and (edate between '$date01' and '$date02') order by p.bdate desc");

} elseif(!empty($_GET['year'])){
        $y = $_GET['year'] - 543;
        $Y = $y - 1;
        $detial = mysqli_query($db,"SELECT * from work w1
                        inner join typevacation t1 on w1.typela=t1.idla
                        where enpid='$empno' AND statusla='Y'  and w1.begindate BETWEEN '$Y-10-01' and '$y-09-30' order by w1.begindate desc");
        $detiatl = mysqli_query($db,"SELECT p.*,t.projectName,t.anProject,t.Beginedate,t.endDate,t.stantee,p2.PROVINCE_NAME,
                t.stdate,t.etdate
    from plan_out p 
        inner join training_out t on t.tuid=p.idpo
        inner join province p2 on p2.PROVINCE_ID=t.provenID
where p.empno='$empno' and p.status_out='Y' and (begin_date between '$Y-10-01' and '$y-09-30') order by begin_date desc");
    $detial_tin = mysqli_query($db,"SELECT p.*,t.in1,t.in2 from plan p
                        inner join trainingin t on p.pjid=t.idpi
                        where type_id='$empno' and (bdate between '$Y-10-01' and '$y-09-30') and (edate between '$Y-10-01' and '$y-09-30') order by p.bdate desc");

    }else{
        if($date >= $bdate and $date <= $edate){
    $detial = mysqli_query($db,"SELECT * from work w1
                        inner join typevacation t1 on w1.typela=t1.idla
                        where enpid='$empno' AND statusla='Y'  and w1.begindate BETWEEN '$y-10-01' and '$Yy-09-30' order by w1.begindate desc");
    $detiatl = mysqli_query($db,"SELECT p.*,t.projectName,t.anProject,t.Beginedate,t.endDate,t.stantee,p2.PROVINCE_NAME,
                t.stdate,t.etdate
    from plan_out p 
        inner join training_out t on t.tuid=p.idpo
        inner join province p2 on p2.PROVINCE_ID=t.provenID
where p.empno='$empno' and p.status_out='Y' and (begin_date between '$y-10-01' and '$Yy-09-30') order by begin_date desc");
    $detial_tin = mysqli_query($db,"SELECT p.*,t.in1,t.in2 from plan p
                        inner join trainingin t on p.pjid=t.idpi
                        where type_id='$empno' and (bdate between '$y-10-01' and '$Yy-09-30') and (edate between '$y-10-01' and '$Yy-09-30') order by p.bdate desc");

    }else{
    $detial = mysqli_query($db,"SELECT * from work w1
                        inner join typevacation t1 on w1.typela=t1.idla
                        where enpid='$empno' AND statusla='Y'  and w1.begindate BETWEEN '$Y-10-01' and '$y-09-30' order by w1.begindate desc");
    $detiatl = mysqli_query($db,"SELECT p.*,t.projectName,t.anProject,t.Beginedate,t.endDate,t.stantee,p2.PROVINCE_NAME,
                t.stdate,t.etdate
    from plan_out p 
        inner join training_out t on t.tuid=p.idpo
        inner join province p2 on p2.PROVINCE_ID=t.provenID
where p.empno='$empno' and p.status_out='Y' and (begin_date between '$Y-10-01' and '$y-09-30') order by begin_date desc");
    $detial_tin = mysqli_query($db,"SELECT p.*,t.in1,t.in2 from plan p
                        inner join trainingin t on p.pjid=t.idpi
                        where type_id='$empno' and (bdate between '$Y-10-01' and '$y-09-30') and (edate between '$Y-10-01' and '$y-09-30') order by p.bdate desc");

}}
$NameDetial = mysqli_fetch_assoc($name_detial);

include_once ('option/funcDateThai.php');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลบุคลากร</h3>
            </div>
            <div class="panel-body">
              <?php  require_once('option/library/mpdf60/mpdf.php'); //ที่อยู่ของไฟล์ mpdf.php ในเครื่องเรานะครับ
ob_start(); // ทำการเก็บค่า html นะครับ*/
?>
                <table  id="datatable" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><font size="3">ชื่อ นามสกุล :
                            <?= $NameDetial['fullname']; ?>
                            <br />
                            ตำแหน่ง :
<?= $NameDetial['posi']; ?>
                            <br />
                            ฝ่าย-งาน :
<?= $NameDetial['dep']; ?>
                            <br />
<br>
                            </font></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="panel panel-primary"> 
                                <div class="panel-heading">
                                    <h3 class="panel-title">ข้อมูลการลา</h3>
                                </div>
                                <div class="panel-body">
                                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class="divider" rules="rows" frame="below">
                                        <?php if(!empty($_POST['year'])){ ?>
                                        <tr>
                                            <td colspan="9" align="center"> ปีงบประมาณ <?= $_POST['year']?></td>
                                        </tr>
                                        <?php }?>
                                                <?php if ($method == 'check_detial_leave' and !empty($_POST['check_date01'])) { ?>
                                            <tr>
                                                <td colspan="9" align="center">ตั้งแต่วันที่
    <?= DateThai1($date01); ?>
                                                    ถึง
    <?= DateThai1($date02); ?></td>
                                            </tr>
<?php } ?>
                                        <tr align="center" bgcolor="#898888">
                                            <td align="center" width="10%"><b>ลำดับ</b></td>
                                            <td align="center" width="20%"><b>เลขที่ใบลา</b></td>
                                            <td align="center" width="20%"><b>ประเภทการลา</b></td>
                                            <td align="center" width="20%"><b>ตั้งแต่</b></td>
                                            <td align="center" width="20%"><b>ถึง</b></td>
                                            <td align="center" width="10%"><b>จำนวนวัน</b></td>
                                        </tr>
                                                    <?php
                                                    $i = 1;
                                                    while ($result = mysqli_fetch_assoc($detial)) {
                                                        ?>
                                            <tr>
                                                <td align="center"><?= $i ?></td>
                                                <td align="center"><?= $result['leave_no']?></td>
                                                <td align="center"><?= $result['nameLa']; ?></td>
                                                <td align="center"><?= DateThai1($result['begindate']); ?></td>
                                                <td align="center"><?= DateThai1($result['enddate']); ?></td>
                                                <td align="center"><?= $result['amount']; ?></td>
                                            </tr>
    <?php $i++;
}
?>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">ข้อมูลการไปราชการ</h3>
                                </div>
                                <div class="panel-body">
                                   <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class="divider" rules="rows" frame="below">
                                        <tr align="center" bgcolor="#898888">
                                            <td width="5%" align="center"><b>ลำดับ</b></td>
                                            <td width="55%" align="center"><b>โครงการ</b></td>
                                            <td width="20%" align="center"><b>เข้าร่วมตั้งแต่</b></td>
                                            <td width="20%" align="center"><b>วันเดินทาง</b></td>
                                        </tr>
<?php
$i = 1;
while ($result2 = mysqli_fetch_assoc($detiatl)) {
    ?>
                                            <tr>
                                                <td align="center"><?= $i ?></td>
                                                <td><?= $result2['projectName']; ?></td>
                                                <td align="center"><?= DateThai1($result2['Beginedate']); ?><br> ถึง <br><?= DateThai1($result2['endDate']); ?></td>
                                                <td align="center"><?= DateThai1($result2['stdate']); ?><br> ถึง <br><?= DateThai1($result2['etdate']); ?></td>
                                            </tr>
                                                <?php $i++;
                                            }
                                            ?>
                                     </table> 
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">ข้อมูลการอบรมภายใน</h3>
                                </div>
                                <div class="panel-body">
                                    <!--<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class="divider" rules="rows" frame="below">-->
                                    <table align="center" width="100%" border="0">
                                    <tr align="center" bgcolor="#898888">
                                        <td align="center" width="5%"><b>ลำดับ</b></td>
                                        <td width="40%" align="center"><b>ชื่อโครงการ</b></td>
                                        <td align="center" width="25%"><b>ตั้งแต่</b></td>
                                        <td align="center" width="25%"><b>ถึง</b></td>
                                        <td align="center" width="5%"><b>ชั่วโมง</b></td>
                                    </tr>
                                    <?php
                                    $i = 1;
                                    while ($result = mysqli_fetch_assoc($detial_tin)) {
                                        ?>
                                        <tr>
                                            <td align="center" width="5%"><?= $i ?></td>
                                            <td width="40%"><?= $result['in2']; ?></td>
                                            <td align="center" width="25%"><?= DateThai1($result['bdate']); ?></td>
                                            <td align="center" width="25%"><?= DateThai1($result['edate']); ?></td>
                                            <td align="center" width="5%"><?= $result['amount']; ?></td>
                                        </tr>

    <?php $i++;
}
?>
                                </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
        </div>
    </div>
</div>
</div>
        <?php
$time_re=  date('Y');
$html = ob_get_contents();
ob_clean();
$pdf = new mPDF('tha2','A4','11','');
$pdf->autoScriptToLang = true;
$pdf->autoLangToFont = true;
$pdf->SetDisplayMode('fullpage');

$pdf->WriteHTML($html, 2);
$pdf->Output("MyPDF/conclude_total$empno_$time_re.pdf");
echo "<meta http-equiv='refresh' content='0;url=MyPDF/conclude_total$empno_$time_re.pdf' />";

include_once 'footeri.php';?>