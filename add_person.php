<?php include_once 'header.php';
 if(empty($_SESSION['user'])){echo "<meta http-equiv='refresh' content='0;url=index.php'/>";exit();} ?>
<script type="text/javascript">
$(function (){
       $(function (){
           $("div#regisDate").hide(0);
       }); 
       if($("#pertype").val() ==''){
       $("#pertype").change(function (){
           if($("#pertype").val()==1 || $("#pertype").val()==2){
                    $("#datepicker4").removeAttr("disabled");
                    $("#regisDate").show("fast"); 
                }else{
                    $("div#regisDate").hide(0);
                }
       });} else {
       if($("#pertype").val()==1 || $("#pertype").val()==2){
       $(function (){
                    $("#datepicker4").removeAttr("disabled");
                    $("#regisDate").show("fast"); 
       });
       $("#pertype").change(function (){
           if($("#pertype").val()==1 || $("#pertype").val()==2){
                    $("#datepicker4").removeAttr("disabled");
                    $("#regisDate").show("fast"); 
                }else{
                    $("div#regisDate").hide(0);
                }
       });
   }else{
       $("#pertype").change(function (){
           if($("#pertype").val()==1 || $("#pertype").val()==2){
                    $("#datepicker4").removeAttr("disabled");
                    $("#regisDate").show("fast"); 
                }else{
                    $("div#regisDate").hide(0);
                }
       });
   }
       }
    });
</script>
<script type="text/javascript">
function nextbox(e, id) {
    var keycode = e.which || e.keyCode;
    if (keycode == 13) {
        document.getElementById(id).focus();
        return false;
    }
}
</script>
<form class="navbar-form navbar-left" role="form" action='prcperson.php' enctype="multipart/form-data" method='post' onSubmit="return Check_txt()">

        <div class="row">
          <div class="col-lg-12">
              <?php if(isset($_REQUEST['method'])=='edit'){?>
            <h1><font color='blue'>  แก้ไขข้อมูลบุคลากร </font></h1> 
            <ol class="breadcrumb alert-success">
              <li><a href="index.php"><i class="fa fa-home"></i> หน้าหลัก</a></li>
              <li><a href="pre_person.php"><i class="fa fa-edit"></i> ข้อมูลพื้นฐาน</a></li>
              <li class="active"><i class="fa fa-edit"></i> แก้ไขข้อมูลบุคลากร</li>
              <?php }else{?>
            <h1><img src='images/adduser.ico' width='75'><font color='blue'>  เพิ่มข้อมูลบุคลากร </font></h1> 
            <ol class="breadcrumb alert-success">
              <li><a href="index.php"><i class="fa fa-home"></i> หน้าหลัก</a></li>
              <li class="active"><i class="fa fa-edit"></i> เพิ่มข้อมูลบุคลากร</li>
              <?php }?>
            </ol>
          </div>
      </div>
<?php
    if(isset($_REQUEST['method'])=='edit'){
        $edit_id=$_REQUEST['id'];
        $edit_per=  mysqli_query($db,"select * from emppersonal e1 left outer join educate e2 on e1.empno=e2.empno
            where e1.empno='$edit_id'");
        $edit_person=  mysqli_fetch_assoc($edit_per);
    }
?>
<div class="row">
          <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><img src='images/phonebook.ico' width='25'> ข้อมูลทั่วไป</h3>
                    </div>
                <div class="panel-body">
                    <div class="form-group"> 
                <label>รหัสพนักงาน &nbsp;</label>
                <input value='<?= isset($edit_person['pid'])?$edit_person['pid']:''?>' type="text" class="form-control" name="empid" id="empid" placeholder="รหัสพนักงาน" onkeydown="return nextbox(event, 'cidid')" required>
             	</div>
                    <div class="form-group"> 
                    <label>หมายเลขบัตรประชาชน &nbsp;</label>
                <input value='<?=isset($edit_person['idcard'])?$edit_person['idcard']:''?>' type="text" class="form-control" name="cidid" id="cidid" placeholder="หมายเลขบัตรประชาชน" maxlength="13" onkeydown="return nextbox(event, 'pname')" onKeyUp="javascript:inputDigits(this);" required>
             	</div><br>
                    <div class="form-group">
         			<label>คำนำหน้า &nbsp;</label>
 				<select name="pname" id="pname" required  class="form-control select2" style="width: 100%;" onkeydown="return nextbox(event, 'fname');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM pcode order by pname  ");
				 echo "<option value=''>--คำนำหน้า--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['pcode']==$edit_person['pcode']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['pcode']."' $selected>".$result['pname']." </option>";
				 } ?>
			 </select>
			 </div>
                    <div class="form-group"> 
                <label>ชื่อ &nbsp;</label>
                <input value='<?=isset($edit_person['firstname'])?$edit_person['firstname']:''?>' type="text" class="form-control" name="fname" id="fname" placeholder="ชื่อ" onkeydown="return nextbox(event, 'lname')" onKeyUp="javascript:inputString(this);" required>
             	</div>
                    <div class="form-group"> 
                <label>นามสกุล &nbsp;</label>
                <input value='<?=isset($edit_person['lastname'])?$edit_person['lastname']:''?>' type="text" class="form-control" name="lname" id="lname" placeholder="นามสกุล" onkeydown="return nextbox(event, 'sex')" onKeyUp="javascript:inputString(this);" required>
             	</div><br>
                <div class="form-group">
         			<label>เพศ &nbsp;</label>
 				<select name="sex" id="sex" required  class="form-control"  onkeydown="return nextbox(event, 'bday');">
                                    <?php if(!empty($edit_person['sex'])){
                                          if($edit_person['sex']==1){     ?>
                                 <option value='<?=$edit_person['sex']?>'>ชาย</option>
                                          <?php }else{?>
                                 <option value='<?=$edit_person['sex']?>'>หญิง</option>
                                    <?php }}?>
				<option value=''>เพศ</option>
                                <option value='1'> ชาย </option>
                                <option value='2'> หญิง </option>
				 </select>
			 </div>
                <div class="form-group"> 
                <label>วันเดือนปีเกิด &nbsp;</label>
                <?php 
 		if(!empty($_GET['method'])){
 			$take_date= $edit_person['birthdate'];
                        $take_date2= $edit_person['regis_date'];
                        }else{
                         $take_date=date('Y-m-d');   
                         $take_date2=date('Y-m-d');
                        }
 		?>
                <script type="text/javascript">
                $(function() {
                $( "#datepicker" ).datepicker("setDate", new Date('<?=$take_date?>')); //Set ค่าวัน
                $( "#datepicker2" ).datepicker("setDate", new Date()); //Set ค่าวัน
                $( "#datepicker3" ).datepicker("setDate", new Date()); //Set ค่าวัน
                $( "#datepicker4" ).datepicker("setDate", new Date('<?=$take_date2?>')); //Set ค่าวัน
                 });
                </script>
                <input name="bday" type="text" id="datepicker"  placeholder='รูปแบบ 22/07/2557' class="form-control"  value="<?= isset($take_date)?$take_date:''?>" required><br>
                </div>
                <div class="form-group"> 
                <label>ที่อยุ่บ้านเลขที่ &nbsp;</label>
                <input value='<?= isset($edit_person['address'])?$edit_person['address']:''?>' type="text" class="form-control" name="address" id="address" placeholder="บ้านเลขที่" onkeydown="return nextbox(event, 'hname')" required>
             	</div>
                <div class="form-group"> 
                <label>ชื่อหมู่บ้าน &nbsp;</label>
                <input value='<?= isset($edit_person['baan'])?$edit_person['baan']:''?>' type="text" class="form-control" name="hname" id="hname" placeholder="ชื่อหมู่บ้าน" onkeydown="return nextbox(event, 'postcode')">
                </div><br>
                    <?php include_once 'address.php';?>
                <div class="form-group"> 
                <label>รหัสไปรษณีย์ &nbsp;</label>
                <input value='<?= isset($edit_person['zipcode'])?$edit_person['zipcode']:''?>' type="text" class="form-control" name="postcode" id="postcode" placeholder="รหัสไปรษณีย์" maxlength="5" onkeydown="return nextbox(event, 'status')" onKeyUp="javascript:inputDigits(this);">
             	</div><br>
                <div class="form-group">
         			<label>สถานะภาพ &nbsp;</label>
 				<select name="status" id="status" required  class="form-control"  onkeydown="return nextbox(event, 'htell');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM empstatus order by status");
				 echo "<option value=''>--สถานะภาพ--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['status']==$edit_person['emp_status']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['status']."' $selected>".$result['statusname']." </option>";
				 } ?>
			 </select>
			 </div><br>
                <div class="form-group"> 
                <label>เบอร์โทรศัพท์บ้าน &nbsp;</label>
                <input value='<?= isset($edit_person['telephone'])?$edit_person['telephone']:''?>' type="text" class="form-control" name="htell" id="htell" placeholder="เบอร์โทรศัพท์บ้าน" maxlength="9" onkeydown="return nextbox(event, 'mtell')" onKeyUp="javascript:inputDigits(this);">
             	</div>
                <div class="form-group"> 
                <label>เบอร์โทรศัพท์มือถือ &nbsp;</label>
                <input value='<?= isset($edit_person['mobile'])?$edit_person['mobile']:''?>' type="text" class="form-control" name="mtell" id="mtell" placeholder="เบอร์โทรศัพท์มือถือ" maxlength="10" onkeydown="return nextbox(event, 'email')" onKeyUp="javascript:inputDigits(this);">
             	</div>
                <div class="form-group"> 
                <label>e-mail &nbsp;</label>
                <input value='<?= isset($edit_person['email'])?$edit_person['email']:''?>' type="text" class="form-control" name="email" id="email" placeholder="email" onkeydown="return nextbox(event, 'order')">
             	</div>
                <div class="form-group">
                <label>รูปถ่าย &nbsp;</label>
                <input type="file" name="image"  id="image" class="form-control"/>
                    </div>
                </div>
                </div>


          </div>
</div>

    <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><img src='images/work.ico' width='25'> ข้อมูลการปฏิบัติงาน</h3>
                    </div>
                <div class="panel-body">
                       <?php if(empty($_REQUEST['method'])){?>
                    <div class="form-group"> 
                <label>คำสั่งเลขที่ &nbsp;</label>
                <input value='<?= isset($edit_person['empcode'])?$edit_person['empcode']:''?>' type="text" class="form-control" name="order" id="order" placeholder="เลขที่คำสั่ง" onkeydown="return nextbox(event, 'position')">
             	</div>
                  <div class="form-group">
         			<label>ตำแหน่ง &nbsp;</label>
 				<select name="position" id="position" required  class="form-control select2" style="width: 100%;" onkeydown="return nextbox(event, 'dep');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM posid order by posId");
				 echo "<option value=''>--ตำแหน่ง--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['posId']==$edit_person['posid']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['posId']."' $selected>".$result['posname']." </option>";
				 } ?>
			 </select>
			 </div>
                    <div class="form-group">
         			<label>หน่วยงาน &nbsp;</label>
 				<select name="dep" id="dep" required  class="form-control select2" style="width: 100%;" onkeydown="return nextbox(event, 'line');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM department order by depId");
				 echo "<option value=''>--หน่วยงาน--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['depId']==$edit_person['depid']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['depId']."' $selected>".$result['depName']." </option>";
				 } ?>
			 </select></div>
                    <div class="form-group">
         			<label>สายงาน &nbsp;</label>
 				<select name="line" id="line" required  class="form-control" onkeydown="return nextbox(event, 'pertype');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM empstuc order by Emstuc");
				 echo "<option value=''>--สายงาน--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['Emstuc']==$edit_person['empstuc']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['Emstuc']."' $selected>".$result['StucName'] ."</option>";
				 } ?>
			 </select>
			 </div>
                       <?php }?>
                    <div class="form-group">
         			<label>ประเภทพนักงาน &nbsp;</label>
 				<select name="pertype" id="pertype" required  class="form-control"  onkeydown="return nextbox(event, 'educat');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM emptype order by EmpType");
				 echo "<option value=''>--ประเภทพนักงาน--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['EmpType']==$edit_person['emptype']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['EmpType']."' $selected>".$result['TypeName'] ."</option>";
				 } ?>
			 </select>
			 </div>
                    <div class="form-group" id="regisDate">
                        <label>วันที่เข้ารับราชการ &nbsp;</label>
                        <input name="regis_date" id="datepicker4" type="text" class="form-control" disabled="disable">
                    </div>
                       <?php if(empty($_REQUEST['method'])){?>
                    <div class="form-group">
         			<label>วุฒิการศึกษาที่บรรจุ &nbsp;</label>
 				<select name="educat" id="educat" required  class="form-control"  onkeydown="return nextbox(event, 'swday');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM education order by education");
				 echo "<option value=''>--วุฒิการศึกษาที่บรรจุ--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['education']==$edit_person['education']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['education']."' $selected>".$result['eduname']." </option>";
				 } ?>
			 </select>
			 </div>
                    <div class="form-group"> 
                <label>วันที่เริ่มปฏิบัติงาน &nbsp;</label>
                <?php
 		if(!empty($_GET['method'])){
 			$dateBegin=$edit_person['dateBegin'];
 			edit_date($dateBegin);
                        }
 		?>
                <input value='<?= isset($dateBegin)?$dateBegin:''?>' type="text" id="datepicker2"  placeholder='รูปแบบ 22/07/2557' class="form-control" name="swday" id="swday" onkeydown="return nextbox(event, 'teducat')">
             	</div>
                       <?php }?>
                </div>
              </div>
          </div>
</div>
       <?php if(empty($_REQUEST['method'])){?>
    <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><img src='images/Student.ico' width='25'> ข้อมูลการศึกษา</h3>
                    </div>
                <div class="panel-body">
                    <div class="form-group">
         			<label>วุฒิการศึกษา &nbsp;</label>
 				<select name="teducat" id="teducat" class="form-control"  onkeydown="return nextbox(event, 'major');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM education order by education");
				 echo "<option value=''>--วุฒิการศึกษา--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['education']==$edit_person['educate']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['education']."' $selected>".$result['eduname']." </option>";
				 } ?>
			 </select>
			 </div>
                    <div class="form-group"> 
                <label>สาขา/วิชาเอก &nbsp;</label>
                <input value='<?= isset($edit_person['major'])?$edit_person['major']:''?>' type="text" class="form-control" name="major" id="major" placeholder="สาขา/วิชาเอก" onkeydown="return nextbox(event, 'inst')">
             	</div>
                    <div class="form-group"> 
                <label>สถาบันที่จบ &nbsp;</label>
                <input value='<?= isset($edit_person['institute'])?$edit_person['institute']:''?>' type="text" class="form-control" name="inst" id="inst" placeholder="ชื่อสถาบัน" onkeydown="return nextbox(event, 'Graduation')">
             	</div>
                    <div class="form-group"> 
                <label>วันที่จบการศึกษา &nbsp;</label>
                <?php
 		if(!empty($_GET['method'])){
 			$enddate=$edit_person['enddate'];
 			edit_date($enddate);
                        }
 		?>
                <input value='<?= isset($enddate)?$enddate:''?>' type="text" id="datepicker3"  placeholder='รูปแบบ 22/07/2557' class="form-control" name="Graduation" id="Graduation" onkeydown="return nextbox(event, 'statusw')">
                    
                </div>
                </div>


          </div>
</div></div>
   <?php }?>
    <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><img src='images/Other.ico' width='25'> ข้อมูลอื่นๆ</h3>
                    </div>
                <div class="panel-body">
                    <div class="form-group">
         			<label>สถานะการทำงาน &nbsp;</label>
 				<select name="statusw" id="statusw" required  class="form-control"  onkeydown="return nextbox(event, 'reason');"> 
				<?php	$sql = mysqli_query($db,"SELECT *  FROM emstatus order by statusid");
				 echo "<option value=''>--สถานะการทำงาน--</option>";
				 while( $result = mysqli_fetch_assoc( $sql ) ){
          if($result['statusid']==$edit_person['status']){$selected='selected';}else{$selected='';}
				 echo "<option value='".$result['statusid']."' $selected>".$result['statusname'] ."</option>";
				 } ?>
			 </select>
                    </div><br>
                    <div class="form-group"> 
                <label>เหตุผลการลาออก/สถานที่ย้ายไป/มาช่วยราชการ/ไปช่วยราชการ &nbsp;</label>
                <TEXTAREA value='' NAME="reason" id="reason"  class="form-control" onkeydown="return nextbox(event, 'movedate')"><?= isset($edit_person['empnote'])?$edit_person['empnote']:''?></TEXTAREA>
                    </div><br>
                    
                    <div class="form-group"> 
                <label>วันที่ ย้าย/ลาออก/ไปช่วยราชการ &nbsp;</label>
                <input value='<?= isset($edit_person['dateEnd'])?$edit_person['dateEnd']:''?>' type="date" class="form-control" name="movedate" id="movedate" placeholder="รูปแบบ 2015-01-31" onkeydown="return nextbox(event, 'Submit')">
             	</div>
                </div>
                </div>


          </div>
</div>
    <?php if(isset($_REQUEST['method'])=='edit'){?>
    <input type="hidden" name="method" id="method" value="edit">
    <input type="hidden" name="edit_id" id="edit_id" value="<?=$edit_person['empno']?>">
   <input class="btn btn-warning" type="submit" name="Submit" id="Submit" value="แก้ไข">
   <?php }else{?> 
   <input type="hidden" name="method" id="method" value="add_person">
   <input class="btn btn-success" type="submit" name="Submit" id="Submit" value="บันทึก">
   <?php }?>
</form>
<?php include_once 'footeri.php';?>
         