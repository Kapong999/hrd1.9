<?php include_once 'head.php'; 
if (empty($_SESSION['user'])) {
    echo "<meta http-equiv='refresh' content='0;url=index.php'/>";
    exit();
}
if(isset($_GET['id'])){
$empno = $_GET['id'];
$name_detial = mysqli_query($db,"select concat(p1.pname,e1.firstname,' ',e1.lastname) as fullname,
                            d1.depName as dep,p2.posname as posi,e1.empno as empno, e2.TypeName as typename,e2.EmpType as emptype
                            from emppersonal e1 
                            inner join pcode p1 on e1.pcode=p1.pcode
                            inner JOIN work_history wh ON wh.empno=e1.empno
                            inner join department d1 on wh.depid=d1.depId
                            inner JOIN posid p2 ON p2.posId=wh.posid
                            inner join emptype e2 on e2.EmpType=wh.emptype
                            where e1.empno='$empno' and (wh.dateEnd_w='0000-00-00' or ISNULL(wh.dateEnd_w))");
$NameDetial = mysqli_fetch_assoc($name_detial);
}
    $method=isset($_GET['method'])?$_GET['method']:'';
    $method_id= isset($_GET['method_id'])?$_GET['method_id']:'';
if($method=='edit_sign'){
    $sql=mysqli_query($db,"select * from fingerprint where empno=$empno and finger_id=$method_id");
}elseif ($method=='edit_late') {
    $sql=mysqli_query($db,"select * from late where empno=$empno and late_id=$method_id");
}$detial= mysqli_fetch_assoc($sql);
include_once ('option/funcDateThai.php');
?>
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php if($method=='exp_sign' or $method=='exp_late'){echo 'บันทึกการชี้แจง';}else{echo 'บันทึกการลงเวลา';}?></h3>
            </div>
            <div class="panel-body">

<font size="3">ชื่อ นามสกุล :
                            <?= $NameDetial['fullname']?>
                            <br />
                            ตำแหน่ง :
<?= $NameDetial['posi']?>
                            <br />
                            ฝ่าย-งาน :
<?= $NameDetial['dep']?>
                            <br />
                            ประเภทพนักงาน :
<?= $NameDetial['typename']?>
                            <p />
                            <form class="navbar-form navbar-left" role="form" action='prcscan.php' enctype="multipart/form-data" method='post' onSubmit="return Check_txt()">                          

<?php if($method=='sign' or $method=='edit_sign'){?>
                            <div class="form-group"> 
                             <?php  
 		if(!empty($method)){
 			$forget_date = isset($detial['forget_date'])?$detial['forget_date']:'';
                        } else {
                        $forget_date = date('Y-m-d');
                        }
 		?>
                <script type="text/javascript">
                $(function() {
                $( "#datepicker" ).datepicker("setDate", new Date('<?=$forget_date?>')); //Set ค่าวัน
                 });
                </script>       
                            <label>วันที่ลืมลงเวลา &nbsp;</label>
                            <input type="text" class="form-control" name="forget_date" id="datepicker" placeholder="วันที่ลืมลงเวลา" required>
                            </div><p>
                            <div class="form-group"><?php if(!empty($detial['work_scan'])){$cheched='checked';}else{$cheched='';}?> 
                                <label><input value='N' type="checkbox" class="" name="work_scan" id="work_scan" placeholder="ลืมลงเวลามาทำงาน" <?= $cheched?>>&nbsp; ลืมลงเวลามาทำงาน </label>
                            </div><p>
                            <div class="form-group"><?php if(!empty($detial['finish_work_scan'])){$cheched2='checked';}else{$cheched2='';}?>  
                            <label><input value='N' type="checkbox" class="" name="finish_work_scan" id="finish_work_scan" placeholder="ลืมลงเวลากลับ" <?= $cheched2?>>&nbsp; ลืมลงเวลากลับ </label>
                            </div>
                            <p>
                                <?php if($method=='edit_sign'){?>
                        <div class="form-group">
                <label>เหตุผล</label>
                <input value="<?php if(!empty($detial['reason_forget'])){echo $detial['reason_forget'];}?>" type="text" name="reason_forget" class="form-control" placeholder="ชี้แจงเหตุผล" required>
                        </div>
                            <div class="form-group">
                <label>เอกสารใบชี้แจง</label>
                <input value="" type="file" name="image" class="form-control" placeholder="เอกสารใบชี้แจง">
                        </div>
                <input type="hidden" name="method" id="method" value="edit_scan">
                <input type="hidden" name="id" id="id" value="<?=$detial['finger_id']?>">
                                <?php }else{?>
                             <input type="hidden" name="method" id="method" value="add_scan">
                                <?php }?>
                             <input type="hidden" name="empno" id="empno" value="<?=$empno?>">
<?php }elseif ($method=='late' or $method=='edit_late') {?>
                             <?php  
 		if(!empty($method)){
                        $late_date = isset($detial['late_date'])?$detial['late_date']:'';
                        } else {
                        $late_date = date('Y-m-d');
                        }
 		?>
                <script type="text/javascript">
                $(function() {
                $( "#datepicker" ).datepicker("setDate", new Date('<?=$late_date?>')); //Set ค่าวัน
                 });
                </script>
                            <div class="form-group col-xs-12"> 
                            <label>วันที่สาย &nbsp;</label>
                            <input type="text" class="form-control" name="late_date" id="datepicker" placeholder="วันที่ลงเวลาสาย" required>
                            </div><p>
                <div class="form-group col-xs-6"> 
                    <label for="take_hour">เวลา&nbsp;</label> 
                <select name="take_hour" id="take_hour" class="form-control" required>
                    <option value="">ชั่วโมง</option>
                    <?php for($i=0;$i<=23;$i++){
                        if((!empty($detial['late_time']))and($i== substr($detial['late_time'],0,2))){$selected='selected';}else{$selected='';}
                        if($i<10){
                        echo "<option value='0".$i."' $selected>0".$i."</option>";    
                        }else{
                        echo "<option value='".$i."' $selected>".$i."</option>";}
                    }?>
                </select>
                </div>
                    <div class="form-group col-xs-6"><label for="take_minute">&nbsp;</label> 
                <select name="take_minute" id="take_minute" class="form-control" required>
                    <option value="">นาที</option>
                    <?php for($i=0;$i<=59;$i++){
                        if((!empty($detial['late_time']))and($i== substr($detial['late_time'],3,2))){$selected='selected';}else{$selected='';}
                    if($i<10){
                        echo "<option value='0".$i."' $selected>0".$i."</option>";    
                        }else{
                        echo "<option value='".$i."' $selected>".$i."</option>";}
                    }?>
                </select>
                    </div><p>
                            <?php if(!empty($detial['late'])){$late=$detial['late'];}?>
                            <div class="form-group"><label for="take_minute">&nbsp;สายจริง</label>
                                <input type="radio" name="late_true" value="Y" <?php if(!empty($detial['late']) and $late=='Y'){echo $checked='checked';}else{echo $checked='';}?>>
                            </div>
                            <div class="form-group"><label for="take_minute">&nbsp;ไม่สายจริง</label>
                                <input type="radio" name="late_true" value="N" 
                                    <?php if(!empty($detial['late'])){if($late=='N'){echo $checked='checked';}else{echo $checked='';}}else{?> checked<?php }?>>
                            </div>
                            <?php if($method=='edit_late'){?>
                        <div class="form-group">
                <label>เหตุผล</label>
                <input value="<?php if(!empty($detial['reason_late'])){echo $detial['reason_late'];}?>" type="text" name="reason_late" class="form-control" placeholder="ชี้แจงเหตุผล" required>
                        </div>
                            <div class="form-group">
                <label>เอกสารใบชี้แจง</label>
                <input value="" type="file" name="image" class="form-control" placeholder="เอกสารใบชี้แจง">
                        </div>
                <input type="hidden" name="method" id="method" value="edit_late">
                <input type="hidden" name="id" id="id" value="<?=$detial['late_id']?>">
                                <?php }else{?>
                             <input type="hidden" name="method" id="method" value="add_late">
                                <?php }?>
                             <input type="hidden" name="empno" id="empno" value="<?=$empno?>">
                    
<?php }elseif ($method=='exp_sign') {
    mysqli_query($db,"update fingerprint set see='Y' where empno=$empno and finger_id=".$_GET['scan_id']."");
    $sql=mysqli_query($db,"select * from fingerprint where empno=$empno and finger_id=".$_GET['scan_id']."");
    $detial_scan=mysqli_fetch_assoc($sql);?>
            ลืมลงเวลาวันที่ <?= DateThai1($detial_scan['forget_date'])?><p>
            โดยลืมลงเวลา <?php if(!empty($detial_scan['work_scan'])){echo 'มาทำงาน';} echo ' &nbsp;&nbsp;'; if(!empty($detial_scan['finish_work_scan'])){echo 'เลิกงาน';}?>
            <div class="form-group">
                <label>เหตุผล</label>
                <input type="text" name="reason_forget" class="form-control" placeholder="ชี้แจงเหตุผล" required>
            </div>
            <input type="hidden" name="method" id="method" value="exp_scan">
            <input type="hidden" name="empno" id="empno" value="<?=$empno?>">
            <input type="hidden" name="id" id="id" value="<?=$_GET['scan_id']?>">
<?php }elseif ($method=='exp_late') {
    mysqli_query($db,"update late set see='Y' where empno=$empno and late_id=".$_GET['late_id']."");
    $sql=mysqli_query($db,"select * from late where empno=$empno and late_id=".$_GET['late_id']."");
    $detial_late=mysqli_fetch_assoc($sql);?>
            ลงเวลาสายวันที่ <?= DateThai1($detial_late['late_date'])?>
            ลงเวลา <?=$detial_late['late_time']?> น.
            <div class="form-group">
                <label>เหตุผล</label>
                <input type="text" name="reason_late" class="form-control" placeholder="ชี้แจงเหตุผล" required>
            </div>
            <input type="hidden" name="method" id="method" value="exp_late">
            <input type="hidden" name="empno" id="empno" value="<?=$empno?>">
            <input type="hidden" name="id" id="id" value="<?=$_GET['late_id']?>">
<?php }?>
            <input type="hidden" name="popup" value="true">
                             <div align="center"><input class="btn btn-success" type="submit" name="Submit" id="Submit" value="บันทึก"></div>
                             </form>                            
        </div>
    </div>
</div>
<?php include_once 'footeri.php'; ?>