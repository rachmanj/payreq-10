@extends('templates.main')

@section('title_page')
  DNC Payreqs Sync  
@endsection

@section('breadcrumb_title')
    bucs
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <h5>Synchronizing between DNC Payreqs on Payreq-Support and Payreq-X</h5>
      </div>  <!-- /.card-header -->
      
      <div class="form-horizontal">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Last Synchronized</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" value="{{ $formated_last_sync }}" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Row Added</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" value="{{ $added }}" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Row Skipped</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" value="{{ $skipped }}" readonly>
            </div>
          </div>
        </div>
       
      </div>
      
    </div> <!-- /.card -->
  </div> <!-- /.col -->
</div>  <!-- /.row -->
@endsection
