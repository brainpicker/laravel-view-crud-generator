<?= "@extends('view-crud-generator::base')" . PHP_EOL ?>

<?= "@section('content')" . PHP_EOL ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">
                    {{ $modalName }}
                </h3>
                <button class="btn btn-default pull-right btn-xs" data-toggle="modal" data-target="#modalAdd{{ $studlySingular }}" id="add{{ $studlySingular }}Btn"><i class="fa fa-plus"></i></button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="{{ $singular }}_table">
                    <thead>
                    <tr>
@foreach($thead as $cell)
                        <td>{{ $cell }}</td>
@endforeach
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
<?= "@foreach($" . $plural . " as $" . $singular . ")" . PHP_EOL ?>
                    <tr id="row-id-{!! $tbodyRowId !!}">
@foreach ($tbody as $propertyName => $cell)
                        <td data-name="{{ $propertyName }}">{!! $cell !!}</td>
@endforeach
                        <td class="col-sm-1">
                            <div class="btn-group">
                                <button class="btn btn-default btn-xs edit-btn" data-target="#modalAdd{{ $studlySingular }}" onclick="editRow(this)"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-default btn-xs" id="delete{{ $studlySingular }}Btn"  onclick="deleteRow(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </td>
                        @{{ csrf_field() }}
                    </tr>
<?= "@endforeach" . PHP_EOL ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div><!--end col-lg-12 -->
    <div class="modal" tabindex="-1" role="dialog" id="modalAdd{{ $studlySingular }}">
        <div class="modal-dialog">
            <form class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add {{ $modalName }}</h4>
                    </div>
                    <div class="modal-body">
@foreach ($modal as $modalRow)
                        <div class="form-group">
                            <label for="{{ $modalRow['name'] }}" class="col-sm-2 control-label">{{ $modalRow['normalCase'] }}</label>
                            <div class="col-sm-10">
                                <input type="{{ $modalRow['type'] }}" class="form-control" id="{{ $modalRow['name'] }}" name="{{ $modalRow['name'] }}" placeholder="{{ $modalRow['normalCase'] }}">
                            </div>
                        </div>
@endforeach
                        <div class="alert alert-danger hidden" role="alert" id="div-error"></div>
                        <input class="hidden" type="hidden" name="modalRowId" id="modalRowId"/>
                    </div>
                    @{{ csrf_field() }}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save{{ $studlySingular }}Btn">Save</button>
                        <button type="button" class="btn btn-primary hidden" id="update{{ $studlySingular }}Btn">Update</button>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<?= "@endsection" . PHP_EOL ?>

<?= "@push('scripts')" . PHP_EOL ?>
@include('view-crud-generator::scripts')
<?= "@endpush" ?>
