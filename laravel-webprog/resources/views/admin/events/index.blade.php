@extends('adminPortal.masterfile')
@section('maintenance_title', 'Events')
@section('content')

<div id="destindex">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('event.create') }}" class="btn btn-success add_modal"> @{{ message }}</a>
            <select id="query_province" class="form-control" name="timeliness" required>
                <option value="0">All Provinces</option>
                <option value="1">Albay</option>
                <option value="2">Camarines Norte</option>
                <option value="3">Camarines Sur</option>
                <option value="4">Catanduanes</option>
                <option value="5">Masbate</option>
                <option value="6">Sorsogon</option>
            </select>
            <select id="query_municipality" class="form-control" name="timeliness" required>

            </select>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div id="table-container" class="panel-body">
                    @include('admin.events.table')       
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('modals')
    <div class="modal fade" tabindex="-1" role="dialog" id="modal">
        <div id="modal-dialog" class="modal-dialog  modal-md" role="document">
        
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog" style="display: none; padding-right: 17px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Are you sure you want to delete
                        this event?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('event.destroy', '__id')}}" id="delete_form" method="DELETE">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="DELETE">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('scripts')
<script>

        $(document).ready(function () {

            $('#dataTable').DataTable();

            // set up jQuery with the CSRF token, or else post routes will fail
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            
            $(document).on('change','#fk_event_province', function(e) {
                console.log(e);
                var province = e.target.value;
                console.log("province",province);
                $.ajax({
                    type: 'GET',
                    url: '{{ route("destination.index") }}' +"/create/" + province,
                    success: function(data) {
                        console.log("success");
                         $('#fkevent_municipality').empty();
                         // $('#fkdestination_barangays').empty();
                         $("#fkevent_municipality").append('<option disabled selected value>Select</option>');
                         console.log(data);
                        $.each(data, function(index,subcatObj){
                            console.log(index);
                            console.log(subcatObj.municipality);
                            $('#fkevent_municipality').append('<option  value="'+subcatObj.municipality_id+'">'+subcatObj.municipality+'</option>');
                        });
                    }
                });
            });
            //OPEN MODALS

            $('#table-container').on('click', '.delete', function (e) {
                var id = $(e.target).data('id');
                console.log('open_delete_modal');

                $('#delete_form')[0].action = $('#delete_form')[0].action.replace('__id', $(e.target).data('id'));
                $('#delete_modal').modal('show');
            });


            $('#table-container').on('click', '.edit', function(e) {
                $('#modal').removeClass('modal-warning modal-success');
                $('#modal').addClass('modal-primary');

                var id = $(e.target).data('id');
                console.log(id);
                console.log('open_edit_modal');
                $.ajax({
                    type: 'GET',
                    url: '{{ route("event.index") }}' +"/" + id + "/edit",
                    success: function(data) {
                        $('#modal-dialog').text('');
                        $('#modal-dialog').append(data);

                        $('#modal').modal('show');
                        // $('#strClientID').prop('disabled', true);
                    }
                });
            });

            $('#table-container').on('click', '.view', function(e) {
                $('#modal').removeClass('modal-success modal-primary');
                $('#modal').addClass('modal-warning');
                id = $(e.target).data('id');

                console.log(id);
                console.log('open_view_modal');
                $.ajax({
                    type: 'GET',
                    url: '{{ route("event.index") }}' +"/" + id,
                    success: function(data) {
                        $('#modal-dialog').text('');
                        $('#modal-dialog').append(data);
                        $('#modal').modal('show');
                }
              });
            });

            $('.add_modal').on('click', function(e) {
                e.preventDefault();
                $('#modal').removeClass('modal-warning modal-primary');
                $('#modal').addClass('modal-success');

                console.log('open_add_modal');
                $.ajax({
                    type: 'GET',
                    url: '{{ route("event.create") }}',
                    success: function(data) {
                        console.log(data);
                        $('#modal-dialog').text('');
                        $('#modal-dialog').append(data);
                        $('#modal').modal('show');
                    }
              });
            });

            // CRUD
            $(document).on('submit','#add_form', function(e) {
                e.preventDefault();
                console.log('add');
                
                var id = $('#id').val();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(data) {
                       if(data.alert=='success'){
                            $('#modal').modal('hide');
                            toastr.success(data.message);
                        }else {
                            toastr.error(data.message);
                        }
                        console.log(data);
                        loadTable();
                    },
                    error: function (jqXHR, status, err) {
                        console.log(err);
                    }
              });
                return false;
            });

            $(document).on('submit','#edit_form', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                console.log('edit');
                
                var id = $('#id').val();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(data) {
                       if(data.alert=='success'){
                            $('#modal').modal('hide');
                            toastr.success(data.message);
                        }else {
                            toastr.error(data.message);
                        }
                        console.log(data);
                        loadTable();
                    },
                    error: function (jqXHR, status, err) {
                        console.log(err);
                    }
              });
                return false;
            });

            $(document).on('submit','#delete_form', function(e) {
                e.preventDefault();
                console.log('delete');
                var form_data =  $('#delete_form').serialize();
                $.ajax({
                    type: 'DELETE',
                    url: $('#delete_form').attr('action'),
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                       if(data.alert=='success'){
                            $('#delete_modal').modal('hide');
                            toastr.success(data.message);
                        }else {
                            toastr.error(data.message);
                        }
                        console.log(data);
                        loadTable();
                    },
                    error: function (jqXHR, status, err) {
                        // console.log(err);
                        //toastr.error('Sorry it appears there was a problem deleting this destination');
                    }
              });
                return false;
            });

            $('#delete_modal').on('hidden.bs.modal', function(e){
                $('#delete_form')[0].action = '{{route('event.destroy', '__id')}}';
            });
            function loadTable(){
                $.ajax({
                    type: 'get',
                    url: "{{ route('event.table') }}",
                    dataType: 'html',
                    success:function(data)
                    {
                        $('#table-container').html(data);
                        $('#dataTable').DataTable();
                    }
                });
            }

            $(document).on('change','#query_province', function(e) {
                console.log(e);
                var province = e.target.value;
                console.log("change",province);
                province == 0 ? loadTable() : loadTableProvince(province);
                
            });

            //Load destinations where province == id
            function loadTableProvince(id){
                //var id = $('#id').val();
                var query_municipality = $('#query_municipality').val();
                loadMunicipalities(id, query_municipality);
                $.ajax({
                    type: 'get',
                    url: '{{ route('event.table') }}'+"/" + id,
                    dataType: 'html',
                    success:function(data)
                    {
                        $('#table-container').html(data);
                        $('#dataTable').DataTable();
                    }
                });
            }

            function loadMunicipalities(province, municipality) {
                selectMunicipalities(province, municipality);

                loadTableMunicipalities(province, municipality);
                
            }

            function loadTableMunicipalities(province, municipality) {
                console.log("Inside loadTableMunicipalities province", province);
                console.log("Inside loadTableMunicipalities municipality", municipality);
                
                if(municipality == null)
                {
                    console.log("null");
                    municipality = 0;
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ route("event.index") }}' +"/query/" + province + "/" + municipality,
                    dataType: 'html',
                    success:function(data)
                    {
                        console.log(data);
                        $('#table-container').html(data);
                        $('#dataTable').DataTable();
                    },
                    catch: function(data)
                    {
                        console.log("Error", data);
                    }
                });
            }



            $(document).on('change','#query_municipality', function(e) {
                console.log(e);
                var municipality = e.target.value;
                var query_province = $('#query_province').val();
                console.log("change municipality",municipality);
                console.log("Query_province", query_province);

                loadTableMunicipalities(query_province,municipality);
                
            });

            function selectMunicipalities(province, municipality)
            {
                //if first load municipality == undefined
                console.log("Inside selectMunicipalities province", province);
                console.log("Inside selectMunicipalities municipality", municipality);
                $.ajax({
                    type: 'GET',
                    url: '{{ route("destination.index") }}' +"/create/" + province,
                    success: function(data) {
                        console.log("success");
                         $('#query_municipality').empty();
                         $('#query_barangay').empty();
                         $("#query_municipality").append('<option value="0">Show All</option>');
                         //if first load - line of code to append a value in #query_municipality
                         console.log(data);
                        $.each(data, function(index,subcatObj){
                            console.log(index);
                            console.log(subcatObj.municipality);
                            $('#query_municipality').append('<option  value="'+subcatObj.municipality_id+'">'+subcatObj.municipality+'</option>');
                        });
                    }
                });
            }

            function loadTableMunicipalities(province, municipality) {
                console.log("Inside loadTableMunicipalities province", province);
                console.log("Inside loadTableMunicipalities municipality", municipality);
                
                if(municipality == null)
                {
                    console.log("null");
                    municipality = 0;
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ route("event.index") }}' +"/query/" + province + "/" + municipality,
                    dataType: 'html',
                    success:function(data)
                    {
                        console.log(data);
                        $('#table-container').html(data);
                        $('#dataTable').DataTable();
                    },
                    catch: function(data)
                    {
                        console.log("Error", data);
                    }
                });
            }

        });

</script>
@endsection

@section('vue-scripts')
<script>
    new Vue({
   el: '#destindex',
   data: {
   message: 'Add'
   }
});
</script>

@endsection