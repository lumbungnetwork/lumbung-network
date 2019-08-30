<form class="login100-form validate-form" method="post" action="/adm/admin">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        @if($getData != null)
        <?php
            $user_type = 'Master Admin';
            if($getData->user_type == 3){
                $user_type = 'Admin';
            }
        ?>
        {{ csrf_field() }}
            @if($type == 1)
                <div class="row">
                    <div class="col-md-6 pr-1">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="email"  value="{{$getData->email}}">
                        </div>
                    </div>
                    <div class="col-md-6 pl-1">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" disabled="" class="form-control" value="{{$user_type}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="{{$getData->name}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Re-Password</label>
                            <input type="password" class="form-control" name="repassword">
                        </div>
                    </div>
                    <input type="hidden" name="cekId" value="{{$getData->id}}" >
                    <input type="hidden" name="type" value="{{$type}}" >
                </div>
            @endif
            @if($type == 2)
                <div class="row">
                    <div class="col-md-6 pr-1">
                        <div class="form-group">
                            <label>email</label>
                            <input type="text" disabled="" class="form-control"  value="{{$getData->email}}">
                        </div>
                    </div>
                    <div class="col-md-6 pl-1">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" disabled="" class="form-control" value="{{$user_type}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" disabled="" value="{{$getData->name}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="cekId" value="{{$getData->id}}" >
                    <input type="hidden" name="type" value="{{$type}}" >
                </div>
            @endif
        @else 
            No Data
        @endif
    </div>
    
    <div class="modal-footer" style="margin-right: 10px;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Confirm</button>
    </div>
</form>   