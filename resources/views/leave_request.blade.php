@extends('layouts.app')

@section('title', 'Leave Request')

@section('content')
<div class="container">
  <div class="row">
    <!-- show Remaining Leave Day -->
    <div class="col-md-8 col-md-offset-2">

      @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {!! session('success_message') !!}
        </div>
      @endif

      @if(Session::has('unsuccess_message'))
        <div class="alert alert-danger alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {!! session('unsuccess_message') !!}
        </div>
      @endif


      <h2>Leave Request</h2>
      <hr>

      <div class="row">
        <div class="form-group col-md-5">
          <label for="Leave_Type">Your remaining leave days</label>
          <ul style="list-style-type: none;">
            <div class="col-md-6 col-offset-2">
              <li>Annual leave</li>
              <li>Personal leave</li>
              <li>Sick leave</li>
            </div>
              <li>{{ (int) $remain_annual }} days {{ ($remain_annual - (int) $remain_annual) * 8 }} hours</li>
              <li>{{ (int) $remain_personal }} days {{ ($remain_personal - (int) $remain_personal) * 8 }} hours</li>
              <li>{{ (int) $remain_sick }} days {{ ($remain_sick - (int) $remain_sick) * 8}} hours</li>
          </ul>
          <form action="/leave_request_history">
          <button type="submit" class="btn btn-default">Leave History</button>
          </form>
        </div>
      </div>
      <hr>

      <div class="row">
          <div class="form-group col-md-3">
            <label for="Leave_Type">Leave Type</label>
          </div>
      </div>

      <form  action="/timesheet/addLeave" method="post">
      {{ csrf_field() }}

      <div class="row">
        <div class="form-group col-md-3">
          <!-- Dropdown -->
          <div class="form-group">
            <select class="form-control" id="sel1" name="leave_type">
              <option selected="true" disabled="disabled">--------------------</option>
              <option>Annual Leave</option>
              <option>Personal Leave</option>
              <option>Sick Leave</option>
            </select>
          </div>
        </div>
      </div>

      <!-- error -->
      <div class="row">
        <div class="form-group col-md-3">
          @if ($errors->has('leave_type'))
            <div class="alert alert-danger nopadding">
              <strong>{{ $errors->first('leave_type') }}</strong>
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <!-- datepicker From-->
        <div class="form-group col-md-3">
          <!-- @if ($errors->has('from'))
            <div class="alert alert-danger">
              <strong>{{ $errors->first('from') }}</strong>
            </div>
          @endif -->
          <label for="date">From</label>
          <div class="input-group date" data-provide="datepicker"  data-date-format="yyyy-mm-dd" data-date-autoclose="true">
            <input type="text" class="form-control" name ="from" id="startDate">
            <div class="input-group-addon">
              <span class="glyphicon glyphicon-th"></span>
            </div>
          </div>
        </div>
        <!-- datepicker To-->
        <div class="form-group col-md-3">
          <!-- @if ($errors->has('to'))
            <div class="alert alert-danger">
              <strong>{{ $errors->first('to') }}</strong>
            </div>
          @endif -->
          <label for="date">To</label>
          <div class="input-group date" data-provide="datepicker"  data-date-format="yyyy-mm-dd" data-date-autoclose="true">
            <input type="text" class="form-control" name ="to" id="endDate">
            <div class="input-group-addon">
              <span class="glyphicon glyphicon-th"></span>
            </div>
          </div>
        </div>
      </div>

      <!-- error -->
      <div class="row">
        <div class="form-group col-md-3">
          @if ($errors->has('from'))
            <div class="alert alert-danger nopadding">
              <strong>{{ $errors->first('from') }}</strong>
            </div>
          @endif
        </div>
        <div class="form-group col-md-3">
          @if ($errors->has('to'))
            <div class="alert alert-danger nopadding">
              <strong>{{ $errors->first('to') }}</strong>
            </div>
          @endif
        </div>
      </div>

      <div class="row" id="type">
        <div class="form-group col-md-3">
          <select class="form-control" name="type" id="time">
            <option value="fullDay">Full day</option>
            <option value="specificTime">Specific time</option>
          </select>
        </div>
      </div>

      <div class="row" id="hours">
        <div class="col-md-12">
          <label for="">Time (hrs)</label>
        </div>
        <div class="form-group col-md-2">
          <select class="form-control" name="startHour" id="startHour">
            <option value="9" selected>09:00</option>
            @for ($i = 10; $i <= 17; $i++)
              <option value="{{ $i }}">{{ $i }}:00</option>
            @endfor
          </select>
        </div>
        <div class="form-group col-md-2">
          <select class="form-control" name="endHour" id="endHour">
            @for ($i = 10; $i <= 17; $i++)
              <option value="{{ $i }}">{{ $i }}:00</option>
            @endfor
            <option value="18" selected>18:00</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <label for="">Purpose</label>
          <textarea name ="purpose" class="form-control" name="name" rows="4" cols="80" style="resize: none"></textarea>
        </div>
      </div>
      <br>
      <!-- error -->
      <div class="row">
        <div class="form-group col-md-3">
          @if ($errors->has('purpose'))
            <div class="alert alert-danger nopadding">
              <strong>{{ $errors->first('purpose') }}</strong>
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-12">
          <label for="Remark">Remark</label><br>
          1. พนักงานจะใช้สิทธิ์ลาได้ เมื่อผู้บังคับบัญชาอนุมัติก่อนเท่านั้น โดยต้องปฏิบัติตามระเบียบข้อบังคับของบริษัทอย่างเคร่งครัด<br>
          2. การลากิจทุกประเภทและลาพักร้อนต้องลาล่วงหน้าอย่างน้อย 3 วัน (ยกเว้นกรณีฉุกเฉิน คือ งานศพบิดา มารดา คู่สมรส บุตร ที่สามารถแจ้งขออนุมัติ
      ผู้บังคับบัญชาก่อนแล้วต้องเขียนใบลาทันทีที่กลับมาเริ่มงาน)<br>
          3. โปรดระบุเหตุผลที่ขอลาให้ชัดเจนว่ามีธุรกิจจำเป็นเรื่องอะไร จำเป็นมากน้อยเพียงใด<br><br>
          <button type="submit" class="btn btn-default">Submit</button>
        </div>
      </div>
    </form>
      <!-- 2block -->
    </div>
    <!-- container all -->
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {

  $('#hours').hide();

  $('#time').change(function() {
    if($('#time').val() == 'specificTime') {
      $('#hours').show();
    }
    else {
      $('#hours').hide();
    }
  });

  $('#startHour').change(function() {
    if($('#startHour') >= $('#endHour')) {
      $('#endHour').val(18);
    }
  });

  $('#endHour').change(function() {
    if($('#endHour').val() <= $('#startHour').val()) {
      $('#startHour').val(9);
    }
  });

  $('.input-group.date').datepicker()
    .on('changeDate', () => {
      if($('#startDate').val() != $('#endDate').val()) {
        $('#startHour').val(9);
        $('#endHour').val(18);
        $('#time').val('fullDay');
        $('#type').hide();
        $('#hours').hide();
      }
      else {
        $('#type').show();
      }
    });

});
</script>
@endsection
