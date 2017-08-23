<div style="background-color:#f5f8fa;">
<table style="width:100%;" bgcolor = "#000" >
  <rowgroup>
    <col width="10%">
  </rowgroup>
  <tr>
    <th>
      <div style="text-align:center; align-items: center; ">
          <font size="5" color = "white"><b>Playtorium Solution Company Limited</b></font>
      </div>
    </th>
  </tr>
</table>
<br>
<br>
<table style="width:80%; margin: auto;" >
  <tr>
    <th>
        <font size="3" ><b>เรียน ผู้บังคับบัญชาในสายงาน ของบริษัท Playtorium Solution Company Limited</b></font>
    </th>
  </tr>
  <tr>
    <th>
      <br>
      <font size="3"><b>เรื่อง ยืนยันการขอ ของ {{$data[0]->first_name}} {{$data[0]->last_name}}</b></font>
    </th>
  </tr>
  <tr>
    <td>
      <br>
      &emsp;&emsp;&emsp;
      @if( $type=='fullDay' )
        <font size="3" >มีความประสงค์จะขอ{{$leave_type}} เหตุผลในการลา คือ {{$purpose}} ตั้งแต่วันที่ {{$date_from}} {{$month_from}} {{$year_from}} ถึงวันที่
        {{$date_to}} {{$month_to}} {{$year_to}} รวม {{$leave_day}} วัน </font>
      @else
        <font size="3" >มีความประสงค์จะขอ{{$leave_type}} เหตุผลในการลา คือ {{$purpose}} ตั้งแต่วันที่ {{$date_from}} {{$month_from}} {{$year_from}} เวลา {{$startHour}}:00 น. ถึงวันที่
        {{$date_to}} {{$month_to}} {{$year_to}} เวลา {{$endHour}}:00 น. รวม {{$leave_times}} ชั่วโมง </font>
      @endif
    </td>
  </tr>
</table>
<br>
<html>
<head>
<style>
table, th, td {
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
</style>
</head>
<body>

<table style="width:60%; margin: auto;">
  <colgroup>
    <col width="50%">
  </colgroup>
  <tr>
    <td><font size="3" color = "#6E6E6E">ชื่อ</font></td>
    <td><font size="3" color = "#6E6E6E">:  {{$data[0]->first_name}} {{$data[0]->last_name}}</font></td>
  </tr>
  <tr>
    <td><font size="3" color = "#6E6E6E">รหัสประจำตัว</font></td>
    <td><font size="3" color = "#6E6E6E">:  {{$data[0]->id}}</font></td>
  </tr>
  <tr>
    <td><font size="3" color = "#6E6E6E">ตำแหน่ง</font></td>
    <td><font size="3" color = "#6E6E6E">:  {{$data[0]->role}}</font></td>
  </tr>
  <tr>
    <td><font size="3" color = "#6E6E6E">ฝ่าย</font></td>
    <td><font size="3" color = "#6E6E6E">:  {{$data[0]->department}}</font></td>
  </tr>
</table>
  <br>
  <table style="width:80%;margin: auto;" >
    <tr>
      <td>
        &emsp;&emsp;&emsp;
        <font size="3">ทั้งนี้ปัจจุบัน คุณ {{$data[0]->first_name}} {{$data[0]->last_name}} มีสิทธิในการ{{$leave_type}}ตามระเบียบบริษัทประจำปี {{$year_from}} ดังนี้:</font>
      </td>
    </tr>
  </table>
  <br>
<table style="width:60%;margin: auto;">
  <colgroup>
    <col width="50%">
  </colgroup>
  <tr>
    <td><font size="3" color = "#6E6E6E">สิทธิที่ได้รับประจำปี</font></td>
    <td><font size="3" color = "#6E6E6E">:  {{$line1}} วัน</font></td>
  </tr>
  <tr>
    <td><font size="3" color = "#6E6E6E">ขอสิทธิไปแล้วโดยไม่รวมลาครั้งนี้</font></td>
    <td><font size="3" color = "#6E6E6E">:  {{(int)$line2}} วัน {{ ($line2 - (int) $line2) * 8 }} ชั่วโมง</font></td>
  </tr>
  <tr>
    <td><font size="3" color = "#6E6E6E">คงเหลือสิทธิที่ใช้ได้ </font></td>
    <td><font size="3" color = "#6E6E6E">:  {{(int)$line3}} วัน {{ ($line3 - (int) $line3) * 8 }} ชั่วโมง</font></td>
  </tr>
</table>
</body>
</html>
<br>
<br>
<br>
  <table style="width:100%;"  >
    <tr>
      <th>
        <form method="get" action= {{$accept_path}}>
        <button type="submit" class="btn btn-primary" style="background-color:#008CBA;padding:7px 100px;color:#FFF;font-weight:900; display:block; margin: auto; border-radius:10px" >
                ACCEPT
        </button>
        </form>
      </th>
      <td>
        <form method="get" action= {{$reject_path}}>
        <button type="submit" class="btn btn-primary" style="background-color:#e6e6e6;padding:7px 100px;color:#000;font-weight:900; display:block; margin: auto; border-radius:10px" >
                REJECT
        </button>
        </form>
      </td>
    </tr>
    <tr>
      <br>
    </tr>
  </table>
</div>
