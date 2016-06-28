<script type="text/javascript">
    var cells = [
@foreach($successModel as $cell)
        {!! '\'' . $cell['property'] . '\',' !!}
@endforeach
    ];

    $("#save{{ $studlySingular }}Btn").on('click', function() {
        clearErrors();
        $.ajax({
            dataType: "json",
            jsonp: false,
            type: "POST",
            url: "{!! $storeAction !!}" + '?_token=@{{ csrf_token() }}',
            data: $("#modalAdd{{ $studlySingular }}").find('form').serializeArray(),
            success: function(response, textStatus, jqXHR) {
                $("#modalAdd{{ $studlySingular }}").modal('hide');
{{--$("#modalAdd{{ $studlySingular }}").find('form')[0].reset();--}}
                var {{ $singular }} = jQuery.parseJSON(jqXHR.responseText);
                var {{ $singular }}_row = '';
                cells.forEach(function(cell) {
                   if ({{ $singular }}[cell] == undefined) {
                       {{ $singular }}_row += '<td>&nbsp;</td>';
                   } else {
                       {{ $singular }}_row += '<td>' + {{ $singular }}[cell] +'</td>';
                   }
                });

                {{ $singular }}_row += '<td>'
                    + '<div class="btn-group">'
                        + '<button class="btn btn-default btn-xs edit-btn" data-target="#modalAdd{{ $studlySingular }}" onclick="editRow(this)"><i class="fa fa-pencil"></i></button>'
                        + '<button class="btn btn-default btn-xs" id="delete{{ $studlySingular }}Btn" onclick="deleteRow(this)"><i class="fa fa-times"></i></button>'
                    + '</div>'
                + '</td>';
                $("#{{ $singular }}_table").find('tbody')
                    .append($('<tr id="row-id-' + {{ $singular }}.id + '">')
                    .append({{ $singular }}_row)
                );
            },
            error: function(jqXHR, textStatus, errorThrown){
                var errors = jQuery.parseJSON(jqXHR.responseText);
                $.each(errors, function(key, value) {
                    $("#" + key).closest('div.form-group').addClass('has-error');
                    $divError = $("#div-error");
                    $divError.text(value[0]);
                    $divError.show();
                    return false;
                });
            }
        });
    });

    $("#update{{ $studlySingular }}Btn").on('click', function() {
        clearErrors();
        var updateUrl = '{!! $updateAction !!}' + '?_token=@{{ csrf_token() }}';
        $.ajax({
            dataType: "json",
            jsonp: false,
            type: "PATCH",
            url: updateUrl.replace(':id', $("#modalRowId").val()),
            data: $("#modalAdd{{ $studlySingular }}").find('form').serializeArray(),
            success: function(response, textStatus, jqXHR) {
                $("#modalAdd{{ $studlySingular }}").modal('hide');
                var row = $("#row-id-" + $("#modalRowId").val());
                cells.forEach(function(cell) {
                    if ($("#" + cell).length !== 0) {
                        rowTd = $(row).find('[data-name="' + cell + '"]');
                        $(rowTd).text($("#" + cell).val());
                    }
                });
                $("#modalAdd{{ $studlySingular }}").find('form')[0].reset();
            },
            error: function(jqXHR, textStatus, errorThrown){
                var errors = jQuery.parseJSON(jqXHR.responseText);
                $.each(errors, function(key, value) {
                    $("#" + key).closest('div.form-group').addClass('has-error');
                    $divError = $("#div-error");
                    $divError.text(value[0]);
                    $divError.show();
                    return false;
                });
            }
        });
    });

    function clearErrors() {
        $("div.form-group").removeClass("has-error");
        $("#div-error").hide();
    }

    function editRow(e) {
        $("#save{{ $studlySingular }}Btn").addClass('hidden');
        $("#update{{ $studlySingular }}Btn").removeClass('hidden');
        $("#modalAdd{{ $studlySingular }}").modal('show');
        $("#modalRowId").val($(e).closest('tr').attr('id').replace('row-id-',''));
@foreach ($editModel as $row)
        if ($(e).closest('tr').children()[{{ $row['index'] }}] != undefined) {
            $("#{{ $row['property'] }}").val($(e).closest('tr').children()[{{ $row['index'] }}].textContent);
        }
@endforeach
    }

    function deleteRow(e) {
        clearErrors();
        var deleteUrl = "{!! $deleteAction !!}";
        $.ajax({
            dataType: "json",
            jsonp: false,
            type: "DELETE",
            url: deleteUrl.replace(':id', $(e).closest('tr').attr('id').replace('row-id-','') + '?_token=@{{ csrf_token() }}'),
            success: function(response, textStatus, jqXHR) {
                responseJson = jQuery.parseJSON(jqXHR.responseText);
                id = responseJson['id'];
                $("#row-id-" + id).remove();
            },
            error: function(jqXHR, textStatus, errorThrown){
                var errors = jQuery.parseJSON(jqXHR.responseText);
                $.each(errors, function(key, value) {
                    $("#" + key).closest('div.form-group').addClass('has-error');
                    $divError = $("#div-error");
                    $divError.text(value[0]);
                    $divError.show();
                    return false;
                });
            }
        });
    }

    $("#add{{ $studlySingular }}Btn").on("click", function() {
        $("#save{{ $studlySingular }}Btn").removeClass('hidden');
        $("#update{{ $studlySingular }}Btn").addClass('hidden');
        $("#modalAdd{{ $studlySingular }}").find('form')[0].reset();
    });

</script>
