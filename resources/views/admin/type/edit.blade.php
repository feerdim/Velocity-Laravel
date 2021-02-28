@extends('layouts.app', ['title' => 'Type', 'subTitle' => 'Edit Type | ' . $type->name, 'link'=> 'admin.type.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-gamepad"></i> Edit Type</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.type.update', $type->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>TYPE NAME</label>
                            <input type="text" name="name" value="{{ old('name', $type->name) }}" placeholder="Ex: League, Challenge ..." class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>MINIMAL PLAYER</label>
                                    <input type="number" name="min_player" value="{{ old('min_player',$type->min_player) }}" placeholder="Input Minimal Player" class="form-control @error('min_player') is-invalid @enderror">
        
                                    @error('min_player')
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>MAXIMAL PLAYER</label>
                                    <input type="number" name="max_player" value="{{ old('max_player', $type->max_player) }}" placeholder="Input Maximal Player" class="form-control @error('max_player') is-invalid @enderror">
        
                                    @error('max_player')
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>CROWN PRICE</label>
                            <input type="number" name="crown_price" value="{{ old('crown_price', $type->crown_price) }}" placeholder="Input Crown Price" class="form-control @error('crown_price') is-invalid @enderror">

                            @error('crown_price')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control content @error('description') is-invalid @enderror" name="description" placeholder="Input Type Description" rows="10">{!! old('description', $type->description) !!}</textarea>
                            @error('description')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Rules</label>
                            <textarea class="form-control content @error('rules') is-invalid @enderror" name="rules" placeholder="Input Type Rules" rows="10">{!! old('rules', $type->rules) !!}</textarea>
                            @error('rules')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        

                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i> EDIT</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </section>

</div>

<script src="https://cdn.tiny.cloud/1/2rx9glkvn8nqrb7130arwbr3xe5gx8fzz84s240572abla61/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    var editor_config = {
        selector: "textarea.content",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
        relative_urls: false,
    };

    tinymce.init(editor_config);
</script>
@endsection

